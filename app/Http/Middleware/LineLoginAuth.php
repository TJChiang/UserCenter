<?php

namespace App\Http\Middleware;

use Closure;
use Token;
use Illuminate\Http\Request;

class LineLoginAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-token');
        if (!$token) return 'Token not found';
        $tokenData = Token::verify($token);
        if (!$tokenData) {
            return 'Token verify error';
        }
        $request['userId'] = $tokenData['userId'];
        return $next($request);
    }
}
