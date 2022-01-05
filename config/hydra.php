<?php

return [
    "public" => [
        'get_jwt_key_url' => env('HYDRA_URL') . '/.well-known/jwks.json',
        'authorize_base_url' => env('HYDRA_URL') . '/oauth2/auth',
        'get_token_url' => env('HYDRA_URL') . '/oauth2/token',
        'revoke_token_url' => env('HYDRA_URL') . '/oauth2/revoke',
        'logout_oidc_url' => env('HYDRA_URL') . 'sessions/logout',
        'get_userinfo_url' => env('HYDRA_URL') . '/userinfo',
    ],
    "admin" => [
        'client_base_url' => env('HYDRA_ADMIN_URL') . '/clients',
        'check_status_url' => env('HYDRA_ADMIN_URL') . '/health/alive',
        'jwt_key_url' => env('HYDRA_ADMIN_URL') . '/keys',
        'consent_request_url' => env('HYDRA_ADMIN_URL') . '/oauth2/auth/requests/consent',
        'login_request_url' => env('HYDRA_ADMIN_URL') . '/oauth2/auth/requests/login',
        'logout_request_url' => env('HYDRA_ADMIN_URL') . '/oauth2/auth/requests/logout'
    ]
];
