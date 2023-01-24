<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\HydraService;
use App\Services\Auth\LineService;
use App\Services\Auth\UserService;
use Auth;

class LoginController extends Controller
{
    protected $userService, $lineService, $hydraService;

    public function __construct(LineService $lineService, UserService $userService, HydraService $hydraService)
    {
        $this->userService = $userService;
        $this->lineService = $lineService;
        $this->hydraService = $hydraService;
    }

    public function main()
    {
        if (Auth::user()) {
            return redirect()->route('default');
        }

        return view('authPage', [
            'title' => 'Sign in',
            'authUrl' => $this->hydraService->getLoginBaseUrl(),
            'lineLoginUrl' => $this->lineService->getAuthorizeEndpoint(),
            'homeUrl' => route('default')
        ]);
    }

    public function signUp()
    {
        return view('authPage', [
            'title' => 'Sign up',
            'authUrl' => $this->hydraService->getLoginBaseUrl(),
            'lineLoginUrl' => $this->lineService->getAuthorizeEndpoint(),
            'homeUrl' => route('default')
        ]);
    }

    public function destroy()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $this->lineService->revokeAccessToken($user->access_token);
        $this->userService->update($user['id'], [
            'access_token' => null,
            'refresh_token' => null
        ]);
        Auth::logout();
        return redirect()->route('default');
    }
}
