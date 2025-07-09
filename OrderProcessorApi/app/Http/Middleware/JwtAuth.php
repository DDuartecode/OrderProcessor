<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Auth;

class JwtAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token não fornecido'], 401);
        }

        try {
            $publicKey = file_get_contents(storage_path('oauth-public.key'));

            $tokeDecoded = JWT::decode($token, new Key($publicKey, 'RS256'));

            $user = User::find($tokeDecoded->sub);

            if (!$user) {
                return response()->json(['error' => 'Usuário não encontrado'], 401);
            }

            // Define o usuário autenticado
            Auth::setUser($user);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Token inválido', 'message' => $e->getMessage()], 401);
        }

        return $next($request);
    }
}
