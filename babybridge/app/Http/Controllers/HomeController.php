<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();


        if ($user->roles->contains('role', 'worker')) {
            return redirect()->route('worker.section.children');
        } elseif ($user->roles->contains('role', 'admin')) {
            return redirect()->route('admin.user.index');
        } elseif ($user->roles->contains('role', 'parent')) {
            return redirect()->route('tutor.daily_journal');
        }
    }
}
