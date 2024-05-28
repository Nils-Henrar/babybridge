<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('authToken')) {
            return response()->json(['message' => 'Token not found in session'], 401);
        }else{
            // log pour dire que le token est dans la session
            Log::info('Token is in session');
        }

        $token = $request->session()->get('authToken');

        // Optionnel : vérifier le token à partir d'un cookie
        $token = $request->cookie('authToken');

        // Ici, vous pouvez ajouter une logique pour valider le token si nécessaire

        if (empty($token)) {
            return response()->json(['message' => 'Invalid token'], 401);
        } else {
            // log pour dire que le token est valide
            Log::info('Token is valid');
        }

        return $next($request);
    }
}
