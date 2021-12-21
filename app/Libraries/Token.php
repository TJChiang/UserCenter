<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token
{
    public static function create($uid)
    {
        $key = env('APP_KEY');
        $time = time();
        $payload = array(
            "iss" => env("APP_NAME"),
            "aud" => env("APP_URL"),
            "iat" => $time,
            "exp" => $time + 1 * 60 * 60,
            "nbf" => $time,
            "data" => [
                "userId" => $uid
            ]
        );
        return JWT::encode($payload, $key, 'HS256');
    }

    public static function verificate($token)
    {
        try {
            $decoded = JWT::decode($token, env('APP_KEY'));
            return $decoded->data["userId"];
        } catch (Exception $err) {
            throw $err->getMessage();
        }
    }
}
