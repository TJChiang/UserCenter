<?php

namespace App\Http\Controllers;

class LineCallbackGet
{
    public function __invoke()
    {
        /** @var \SocialiteProviders\Line\Provider $lineProvider */
        $lineProvider = \Laravel\Socialite\Facades\Socialite::driver('line');

        $userInfo = $lineProvider->user();

        return response()->json($userInfo);

        // function base64UrlEncode()
        // {
        // };
        // function convertBase64ToBase64Url(string $base64)
        // {
        //     return str_replace(['+', '/', '='], ['-', '_', ''], $base64);
        // }



        // $idToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2FjY2Vzcy5saW5lLm1lIiwic3ViIjoiVTAxYzc4MzMwZDUxMDhhYTg4NTBjMjYxMzNmOWEwMGRjIiwiYXVkIjoiMTY1NjczMjUyOSIsImV4cCI6MTY3NTM1MDczMywiaWF0IjoxNjc1MzQ3MTMzLCJhbXIiOlsibGluZXFyIl0sIm5hbWUiOiJOYXR1cmUiLCJwaWN0dXJlIjoiaHR0cHM6Ly9wcm9maWxlLmxpbmUtc2Nkbi5uZXQvMGhXQ0EwaUliekNHeDhTU0JNb3U5M08wQU1CZ0VMWnc0a0JId1ZBZ3NaVjFoWGVCcHZSeTVHRGxwT0FnOVZlQnM4UUhvVkF3dEFVZzRGIiwiZW1haWwiOiJvbmVwaWVjZTg0MTExOUBnbWFpbC5jb20ifQ.u022VpD34iZi29IC76uSzh_9kg4FDsM1VklCg8XCiCw';

        // $header = explode('.', $idToken)[0];
        // $payload = explode('.', $idToken)[1];
        // $signature = explode('.', $idToken)[2];



        // $data = base64UrlEncode($header) . '.' . base64UrlEncode($payload);

        // return $signature === base64UrlEncode(
        //     hash_hmac('sha256', $data, config('services.line.client_secret'), true)
        // );
    }
}
