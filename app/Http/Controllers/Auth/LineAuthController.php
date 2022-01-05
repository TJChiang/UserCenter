<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\Auth\UserService;
use App\Services\Auth\LineService;
use Auth;
use Exception;
use Illuminate\Support\Facades\Session;

class LineAuthController extends Controller
{
    protected $userService, $lineService;

    public function __construct(UserService $userService, LineService $lineService)
    {
        $this->userService = $userService;
        $this->lineService = $lineService;
    }

    public function lineLoginCallback(Request $request)
    {
        try {
            $session = $request->session();
            $state = $session->get('line.state');
            if ($state !== $request->state) {
                throw new Exception("State Timeout!");
            }

            $error = $request->input('error', false);
            if ($error) {
                throw new Exception("request params are not valid");
            }
            $lineToken = $this->lineService->getLineToken($request->code);
            $tokenVerify = $this->lineService->verifyLineToken($lineToken['id_token']);
            $lineUserInfo = $this->lineService->verifyAccessToken($lineToken['access_token']);
            if (isset($lineUserInfo->error)) {
                throw new Exception($lineUserInfo->error_description);
            }
            $userInfo = $this->userService->getUserByLineId($tokenVerify['sub']);

            // echo "<pre>"; print_r($cbState); echo "</pre>";
            // echo "<pre>"; print_r($lineToken); echo "</pre>";
            // echo "<pre>"; echo print_r($tokenVerify); echo "</pre>";
            // echo "<pre>"; echo print_r($lineUserInfo); echo "</pre>";
            // echo "<pre>"; echo print_r($userInfo); echo "</pre>";

            switch (explode("-", $state)[0]) {
                case 'register':
                    if ($userInfo) {
                        return response('This user has been Signed up.');
                    }
                    // 註冊使用者資料
                    $userInfo = $this->userService->create([
                        'name' => $tokenVerify['name'],
                        'line_id' => $tokenVerify['sub'],
                        'password' => Hash::make($tokenVerify['sub']),
                        'token_type' => $lineToken['token_type'],
                        'access_token' => $lineToken['access_token'],
                        'refresh_token' => $lineToken['refresh_token']
                    ]);
                case 'login':
                    if (
                        $userInfo
                        && strcmp($lineUserInfo['client_id'], config('line.channel_id')) == 0
                        && Auth::attempt(['id' => $userInfo['id'], 'password' => $tokenVerify['sub']])
                    ) {
                        $this->userService->update($userInfo['id'], [
                            'access_token' => $lineToken['access_token'],
                            'refresh_token' => $lineToken['refresh_token']
                        ]);
                        return redirect()->route('default');
                    }
                    return response('Member not found', 404);
                default:
                    throw new Exception("request params are not valid");
            };
        } catch (Exception $err) {
            $session->flush();
            return response($err, 401);
        }
    }
}
