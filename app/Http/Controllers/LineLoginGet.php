<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Line\Provider;

class LineLoginGet
{
    // public function __invoke()
    // {
    //     $state = Str::random(32);
    //     $nonce = Str::random(32);

    //     $query = Arr::query([
    //         'response_type' => 'code',
    //         'client_id' => config('services.line.client_id'),
    //         'redirect_uri' => config('services.line.redirect'),
    //         'state' => $state,
    //         'nonce' => $nonce,
    //         'scope' => 'profile openid email'
    //     ]);

    //     $url = 'https://access.line.me/oauth2/v2.1/authorize?' . $query;

    //     Session::put('line.state', $state);
    //     Session::put('line.nonce', $nonce);

    //     return redirect()->away($url);
    // }

    public function __invoke()
    {
        /** @var Provider $lineProvider */
        $lineProvider = Socialite::driver('line');
        $lineProvider->setScopes(['profile', 'openid']);
        return $lineProvider->redirect();
    }
}
