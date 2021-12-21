<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Auth\LineService;
use Exception;

class LineAuthController extends Controller
{
    protected $lineService;

    public function __construct(LineService $lineService)
    {
        $this->lineService = $lineService;
    }

    public function pageLine()
    {
        $url = $this->lineService->getLoginBaseUrl();
        return view('login', ['url' => $url]);
    }

    public function lineLoginCallBack(Request $request)
    {
        try {
            $error = $request->input('error', false);
            if ($error) {
                throw new Exception("request params are not valid");
            }
            $response = $this->lineService->getLineToken($request->code);
            $user_profile = $this->lineService->getUserProfile($response['access_token']);
        } catch (Exception $err) {
            throw $err;
        }
    }
}
