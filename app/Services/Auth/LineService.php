<?php

namespace App\Services\Auth;

use App\Exceptions\Auth\InvalidCallbackDataException;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

// https://developers.line.biz/en/docs/line-login/integrate-line-login/#receiving-the-authorization-code-or-error-response-with-a-web-app
class LineService
{
    private $httpClient;
    private $lineConfig;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->lineConfig = config('services.line');
    }

    public function getAuthorizeEndpoint(): string
    {
        $state = Str::random(32);
        $nonce = Str::random(32);

        $query = Arr::query([
            'response_type' => 'code',
            'client_id' => config('services.line.client_id'),
            'redirect_uri' => config('services.line.redirect'),
            'state' => $state,
            'nonce' => $nonce,
            'scope' => 'profile openid email'
        ]);

        Session::put('line.state', $state);

        return config('services.line.authorize_endpoint') . '?' . $query;
    }

    public function getLineToken($code)
    {
        $response = $this->httpClient->request('POST', $this->lineConfig['token_endpoint'], [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => route('line_callback'),
                'client_id' => $this->lineConfig['client_id'],
                'client_secret' => $this->lineConfig['client_secret']
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
        $response = $this->httpClient->request('GET', $this->lineConfig['profile_url'], [
            'headers' => $headers
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function verifyLineToken($token)
    {
        $response = $this->httpClient->request('POST', $this->lineConfig['verify_token_url'], [
            'form_params' => [
                'id_token' => $token,
                'client_id' => $this->lineConfig['client_id']
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
                'client_id' => $this->lineConfig['client_id'],
                'client_secret' => $this->lineConfig['client_secret']
            ]
        ]);
    }
}
