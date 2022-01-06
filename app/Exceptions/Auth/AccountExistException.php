<?php

namespace App\Exceptions\Auth;

use Exception;

class AccountExistException extends Exception
{
    public function render($request)
    {
        $request->session()->flush();

        return response()->json([
            "code" => 409,
            "message" => "This account already exists"
        ], 409);
    }
}
