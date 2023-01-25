<?php

namespace App\Http\Controllers;

use App\Exceptions\Auth\AccountNotExistException;
use App\Exceptions\Auth\InvalidCallbackDataException;
use App\Services\Auth\LineService;
use App\Services\Auth\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use SocialiteProviders\Line\Provider;

class LineCallbackGet
{
    public function __construct(private UserService $userService, private LineService $lineService)
    {
    }

    public function __invoke(Request $request)
    {
        /** @var Provider $lineProvider */
        $lineProvider = Socialite::driver('line');

        try {
            $userInfo = $lineProvider->user();
        } catch (InvalidStateException) {
            throw new InvalidCallbackDataException('Invalid state.');
        }

        return response()->json($userInfo);
    }

    // public function __invoke(Request $request)
    // {
    //     $session = $request->session();
    //     $state = $session->get('line.state');
    //     if ($state !== $request->state) {
    //         throw new InvalidCallbackDataException("Invalid State");
    //     }

    //     $error = $request->input('error');
    //     if ($error) {
    //         throw new InvalidCallbackDataException('Line Login error: ' . $error);
    //     }

    //     $lineToken = $this->lineService->getLineToken($request->code);
    //     $tokenVerify = $this->lineService->verifyLineToken($lineToken['id_token']);
    //     $userInfo = $this->userService->getUserByLineId($tokenVerify['sub']);

    //     return response()->json([
    //         'scope' => $lineToken['scope'],
    //         'token_type' => $lineToken['token_type'],
    //         'access_token' => $lineToken['access_token'],
    //         'id_token' => $lineToken['id_token'],
    //         'id_token_payload' => $tokenVerify,
    //         'user_info' => $userInfo,
    //     ]);
    //     // return $this->handleLogin($userInfo, $tokenVerify, $lineToken);
    // }

    private function handleLogin($userInfo, $tokenVerify, $lineToken)
    {
        if (!$userInfo) {
            throw new AccountNotExistException();
        }

        if (!Auth::attempt(['id' => $userInfo['id'], 'password' => $tokenVerify['sub']])) {
            throw new Exception("Login failed");
        }

        $this->userService->update($userInfo['id'], [
            'access_token' => $lineToken['access_token'],
            'refresh_token' => $lineToken['refresh_token']
        ]);

        return redirect()->route('default');
    }
}
