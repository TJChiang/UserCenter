<?php

namespace App\Exceptions\Auth;

use Exception;

class AccountNotExistException extends Exception
{
    public function render($request)
    {
        $request->session()->flush();

        return response()->json([
            'code' => 401,
            'message' => "This account does not exist"
        ], 401);
    }
}
