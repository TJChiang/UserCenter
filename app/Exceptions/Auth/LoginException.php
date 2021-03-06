<?php

namespace App\Exceptions\Auth;

use Exception;

class LoginException extends Exception
{
    public function render($request)
    {
        $request->session()->flush();

        return response()->json([
            "code" => 401,
            "message" => $this->getMessage()
        ], 401);
    }
}
