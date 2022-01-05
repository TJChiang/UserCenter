<?php

namespace App\Services\Auth;

use GuzzleHttp\Client;

class HydraService
{
    public function getLoginBaseUrl()
    {
        $url = config('hydra.public.authorize_base_url') . '?';
        $url .= 'client_id=auth-code-client';
        $url .= '&response_type=code';
        $url .= '&redirect_uri=http://127.0.0.1:5555/callback';
        $url .= '&state=development';
        $url .= '&scope=openid';

        return $url;
    }
}
