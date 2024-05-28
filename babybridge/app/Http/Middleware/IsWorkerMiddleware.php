<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IsWorkerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

            if (!Auth::check()) {

                Log::info('IsWorkerMiddleware: user is not logged in');

                return redirect()->route('login')->with('error', 'You have to be logged in to access the page');
            }

            if (Auth::user()->roles->contains('role', 'worker') === false) {

                Log::info('IsWorkerMiddleware: user is not worker');

                return redirect()->route('home')->with('error', 'You do not have permission to access the page');
            }

        Log::info('IsWorkerMiddleware: user is worker');



        return $next($request);
    }
}
