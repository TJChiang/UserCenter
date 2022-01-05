<?php

namespace App\Services\Auth;

use Crypt;
use GuzzleHttp\Client;

// https://developers.line.biz/en/docs/line-login/integrate-line-login/#receiving-the-authorization-code-or-error-response-with-a-web-app
class LineService
{
    public function getLoginBaseUrl($type)
    {
        $url = config('line.authorize_base_url') . '?';
        $url .= 'response_type=code';
        $url .= '&client_id=' . config('line.channel_id');
        $url .= '&redirect_uri=' . route('line_callback');
        $url .= '&state=' . Crypt::encrypt(['type' => $type, 'random_time' => time()]);
        $url .= '&scope=profile%20openid';

        return $url;
    }

    public function getLineToken($code)
    {
        $client = new Client();
        $response = $client->request('POST', config('line.get_token_url'), [
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

    // Array
    // (
    //     [userId] => abcd
    //     [displayName] => Nature
    //     [pictureUrl] => https://profile.line-scdn.net/0hWCA0iIbzCGx8SSBMou93O0AMBgELZw4kBHwVAgsZV1hXeBpvRy5GDlpOAg9VeBs8QHoVAwtAUg4F
    // )
    public function getUserProfile($token)
    {
        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
        $response = $client->request('GET', config('line.get_user_profile_url'), [
            'headers' => $headers
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function verifyLineToken($token)
    {
        $client = new Client();
        $response = $client->request('POST', config('line.verify_token_url'), [
            'form_params' => [
                'id_token' => $token,
                'client_id' => config('line.channel_id')
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function verifyAccessToken($accessToken)
    {
        $client = new Client();
        $response = $client->request('GET', config('line.verify_token_url'), [
            'query' => [
                'access_token' => $accessToken
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function revokeAccessToken($accessToken)
    {
        $client = new Client();
        $client->request('POST', config('line.revoke_url'), [
            'form_params' => [
                'access_token' => $accessToken,
                'client_id' => config('line.channel_id'),
                'client_secret' => config('line.secret')
            ]
        ]);
    }
}
