<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token
{
    public static function create($data)
    {
        $key = env('APP_KEY');
        $time = time();
        $payload = array(
            "iss" => env("APP_NAME"),
            "aud" => env("APP_URL"),
            "iat" => $time,
            "exp" => $time + 1 * 60 * 60,
            "data" => $data
        );
        return JWT::encode($payload, $key, 'HS256');
    }

    public static function verify($token)
    {
        try {
            $decoded = JWT::decode($token, env('APP_KEY'), ['HS256']);
            return $decoded->data;
        } catch (Exception $err) {
            return null;
        }
    }
}
