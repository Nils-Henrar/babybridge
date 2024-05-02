<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IsTutorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if ($request->is('tutor') || $request->is('tutor/*')) { //permet de vérifier si la requête est pour la page tutor ou une sous-page tutor

            Log::info('IsTutorMiddleware: checking if user is tutor');

            if (!Auth::check()) {

                Log::info('IsTutorMiddleware: user is not logged in');

                return redirect()->route('login')->with('error', 'You have to be logged in to access the page');
            }

            if (Auth::user()->roles->contains('role', 'tutor') === false) {

                Log::info('IsTutorMiddleware: user is not tutor');

                return redirect()->route('home')->with('error', 'You do not have permission to access the page');
            }
        }

        Log::info('IsTutorMiddleware: user is tutor');





        return $next($request);
    }
}
