<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class CheckAuthToken
{
    public function handle(Request $request, Closure $next)
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['message' => 'Usuário não encontrado'], 400);
        }

        return $next($request);
    }

    protected function isAuthenticated(): bool
    {
        try {
            return (bool) JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException) {
            throw new HttpResponseException(response()->json(['message' => 'Token expirado'], 400));
        } catch (TokenInvalidException) {
            throw new HttpResponseException(response()->json(['message' => 'Token inválido'], 400));
        } catch (JWTException) {
            throw new HttpResponseException(response()->json(['message' => 'Token não fornecido'], 400));
        }
    }

}
