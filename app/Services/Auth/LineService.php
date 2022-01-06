<?php

namespace App\Services\Auth;

use Illuminate\Support\Str;
use App\Exceptions\Auth\InvalidCallbackDataException;
use GuzzleHttp\Client;

// https://developers.line.biz/en/docs/line-login/integrate-line-login/#receiving-the-authorization-code-or-error-response-with-a-web-app
class LineService
{
    protected $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getLoginBaseUrl($type, $request)
    {
        $state = $type . '-' . Str::random(32);

        $url = config('line.authorize_base_url') . '?';
        $url .= 'response_type=code';
        $url .= '&client_id=' . config('line.channel_id');
        $url .= '&redirect_uri=' . route('line_callback');
        $url .= '&state=' . $state;
        $url .= '&scope=profile%20openid';

        $request->session()->put('line.state', $state);

        return $url;
    }

    public function getLineToken($code)
    {
        $response = $this->httpClient->request('POST', config('line.get_token_url'), [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => route('line_callback'),
                'client_id' => config('line.channel_id'),
                'client_secret' => config('line.secret')
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
        $response = $this->httpClient->request('GET', config('line.get_user_profile_url'), [
            'headers' => $headers
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function verifyLineToken($token)
    {
        $response = $this->httpClient->request('POST', config('line.verify_token_url'), [
            'form_params' => [
                'id_token' => $token,
                'client_id' => config('line.channel_id')
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
        $response = $this->httpClient->request('GET', config('line.verify_token_url'), [
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
        $this->httpClient->request('POST', config('line.revoke_url'), [
            'form_params' => [
                'access_token' => $accessToken,
                'client_id' => config('line.channel_id'),
                'client_secret' => config('line.secret')
            ]
        ]);
    }
}
