<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {

        if (auth()->user()->roles->contains('role', 'worker')) {
            return route('worker.section.children');
        } elseif (auth()->user()->roles->contains('role', 'admin')) {
            return route('admin.user.index');
        } elseif (auth()->user()->roles->contains('role', 'tutor')) {
            return route('tutor.children.daily-journal');
        }

        // avec message flash
        return redirect()->route('home')->with('error', 'Vous n\'avez pas la permission d\'accéder à cette page');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/');
    }

    protected function authenticated(Request $request, $user)
    {

        // Debug message to ensure authenticated method is being called
        Log::info('User authenticated', ['user_id' => $user->id]);
        // Generate token for the authenticated user
        $token = $user->createToken('API Token')->plainTextToken;

        // Store the token in the session for use in the application
        // session(['authToken' => $token]);

        // Store the token in an HttpOnly, Secure cookie
        cookie()->queue(cookie('authToken', $token, 60, null, null, true, true, false, 'Strict'));

        if ($request->is('api/*')) {
            return response()->json([
                'message' => 'Login successful',
                'token' => $token
            ]);
        }
        return redirect()->intended($this->redirectPath());
    }
}
