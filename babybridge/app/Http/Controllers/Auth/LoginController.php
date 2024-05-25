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

        // // Redirect to the intended page
        // return redirect()->intended($this->redirectPath());

        // si on est worker on redirige vers la page de gestion des enfants, si on est admin on redirige vers la page de gestion des utilisateurs et si on est parent on redirige vers la page journal de bord

        if ($user->roles->contains('role', 'worker')) {
            return redirect()->route('worker.section.children');
        } elseif ($user->roles->contains('role', 'admin')) {
            return redirect()->route('admin.user.index');
        } elseif ($user->roles->contains('role', 'parent')) {
            return redirect()->route('tutor.daily_journal');
        }
    }
}
