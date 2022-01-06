<?php

namespace App\Services\Auth;

use Illuminate\Support\Str;
use App\Exceptions\Auth\InvalidCallbackDataException;
use GuzzleHttp\Client;

// https://developers.line.biz/en/docs/line-login/integrate-line-login/#receiving-the-authorization-code-or-error-response-with-a-web-app
class LineService
{
    private $httpClient;
    private $lineConfig;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->lineConfig = config('line');
    }

    public function getLoginBaseUrl($type, $request)
    {
        $state = $type . '-' . Str::random(32);

        $url = $this->lineConfig['authorize_base_url'] . '?';
        $url .= 'response_type=code';
        $url .= '&client_id=' . $this->lineConfig['channel_id'];
        $url .= '&redirect_uri=' . route('line_callback');
        $url .= '&state=' . $state;
        $url .= '&scope=profile%20openid';

        $request->session()->put('line.state', $state);

        return $url;
    }

    public function getLineToken($code)
    {
        $response = $this->httpClient->request('POST', $this->lineConfig['get_token_url'], [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => route('line_callback'),
                'client_id' => $this->lineConfig['channel_id'],
                'client_secret' => $this->lineConfig['secret']
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getUserProfile($token)
    {
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        $response = $this->httpClient->request('GET', $this->lineConfig['get_user_profile_url'], [
            'headers' => $headers
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function verifyLineToken($token)
    {
        $response = $this->httpClient->request('POST', $this->lineConfig['verify_token_url'], [
            'form_params' => [
                'id_token' => $token,
                'client_id' => $this->lineConfig['channel_id']
            ]
        ]);

        $res = json_decode($response->getBody()->getContents(), true);
        if (isset($res->error)) {
            throw new InvalidCallbackDataException("Verify Line id_token error: " . $res->error_description);
        }

        return $res;
    }

    public function verifyAccessToken($accessToken)
    {
        $response = $this->httpClient->request('GET', $this->lineConfig['verify_token_url'], [
            'query' => [
                'access_token' => $accessToken
            ]
        ]);

        $res = json_decode($response->getBody()->getContents(), true);
        if (isset($res->error)) {
            throw new InvalidCallbackDataException("Verify Line id_token error: " . $res->error_description);
        }

        return $res;
    }

    public function revokeAccessToken($accessToken)
    {
        $this->httpClient->request('POST', $this->lineConfig['revoke_url'], [
            'form_params' => [
                'access_token' => $accessToken,
                'client_id' => $this->lineConfig['channel_id'],
                'client_secret' => $this->lineConfig['secret']
            ]
        ]);
    }
}
