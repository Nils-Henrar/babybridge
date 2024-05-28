<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
        }

        $token = $request->session()->get('authToken');

        // Optionnel : vérifier le token à partir d'un cookie
        // $token = $request->cookie('authToken');

        // Ici, vous pouvez ajouter une logique pour valider le token si nécessaire

        return $next($request);
    }
}
