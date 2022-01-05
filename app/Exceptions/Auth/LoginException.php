<?php

namespace App\Exceptions\Auth;

use Exception;

class LoginException extends Exception
{
    public function render()
    {
        return response()->json([
            "code" => 401,
            "message" => $this->getMessage()
        ], 401);
    }
}
