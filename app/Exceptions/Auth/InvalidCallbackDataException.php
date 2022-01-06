<?php

namespace App\Exceptions\Auth;

use RuntimeException;

class InvalidCallbackDataException extends RuntimeException
{
    public function render($request)
    {
        $request->session()->flush();

        return response()->json([
            "code" => 500,
            'message' => $this->getMessage()
        ], 500);
    }
}
