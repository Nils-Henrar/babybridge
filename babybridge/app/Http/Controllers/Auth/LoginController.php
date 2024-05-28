<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/home';

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
            // Generate token for the authenticated user
            $token = $user->createToken('API Token')->plainTextToken;

            // Store the token in the session for use in the application
            session(['authToken' => $token]); 

            // Store the token in an HttpOnly, Secure cookie
            cookie()->queue(cookie('authToken', $token, 60, null, null, true, true, false, 'Strict'));

            // Redirect based on user role
            if ($user->roles->contains('role', 'worker')) {
                return redirect()->route('worker.section.children');
            } elseif ($user->roles->contains('role', 'admin')) {
                return redirect()->route('admin.user.index');
            } elseif ($user->roles->contains('role', 'tutor')) {
                return redirect()->route('tutor.children.daily-journal');
            }
    
            return redirect()->intended($this->redirectPath());
    }
}
