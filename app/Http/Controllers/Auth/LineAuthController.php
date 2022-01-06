<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\Auth\InvalidCallbackDataException;
use App\Exceptions\Auth\LoginException;
use App\Exceptions\Auth\AccountNotExistException;
use App\Exceptions\Auth\AccountExistException;
use App\Services\Auth\UserService;
use App\Services\Auth\LineService;
use Auth;

class LineAuthController extends Controller
{
    private $userService, $lineService;

    public function __construct(UserService $userService, LineService $lineService)
    {
        $this->userService = $userService;
        $this->lineService = $lineService;
    }

    public function lineLoginCallback(Request $request)
    {
        $session = $request->session();
        $state = $session->get('line.state');
        if ($state !== $request->state) {
            throw new InvalidCallbackDataException("Invalid State");
        }

        $error = $request->input('error', false);
        if ($error) {
            throw new InvalidCallbackDataException('Line Login error: ' . $error);
        }

        $lineToken = $this->lineService->getLineToken($request->code);
        $tokenVerify = $this->lineService->verifyLineToken($lineToken['id_token']);
        $userInfo = $this->userService->getUserByLineId($tokenVerify['sub']);

        switch (explode("-", $state)[0]) {
            case 'register':
                return $this->handleRegister($userInfo, $tokenVerify, $lineToken);
            case 'login':
                return $this->handleLogin($userInfo, $tokenVerify, $lineToken);
            default:
                throw new LoginException("Login failed");
        };
    }

    private function handleRegister($userInfo, $tokenVerify, $lineToken)
    {
        if ($userInfo) {
            throw new AccountExistException();
        }

        $userInfo = $this->userService->create([
            'name' => $tokenVerify['name'],
            'line_id' => $tokenVerify['sub'],
            'password' => Hash::make($tokenVerify['sub']),
            'token_type' => $lineToken['token_type'],
            'access_token' => $lineToken['access_token'],
            'refresh_token' => $lineToken['refresh_token']
        ]);

        return $this->handleLogin($userInfo, $tokenVerify, $lineToken);
    }

    private function handleLogin($userInfo, $tokenVerify, $lineToken)
    {
        if (!$userInfo) {
            throw new AccountNotExistException();
        }

        if (!Auth::attempt(['id' => $userInfo['id'], 'password' => $tokenVerify['sub']])) {
            throw new LoginException("Login failed");
        }

        $this->userService->update($userInfo['id'], [
            'access_token' => $lineToken['access_token'],
            'refresh_token' => $lineToken['refresh_token']
        ]);

        return redirect()->route('default');
    }
}
