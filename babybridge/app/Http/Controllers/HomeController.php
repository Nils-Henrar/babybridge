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
        $this->middleware('auth'); //
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {


        if ($request->is('api/*')) {
            return response()->json([
                'message' => 'You are authenticated'
            ]);
        }
        $user = auth()->user();


        if ($user->roles->contains('role', 'worker')) {
            return redirect()->route('worker.section.children');
        } elseif ($user->roles->contains('role', 'admin')) {
            return redirect()->route('admin.user.index');
        } elseif ($user->roles->contains('role', 'tutor')) {
            return redirect()->route('tutor.children.daily-journal');
        }
    }
}
