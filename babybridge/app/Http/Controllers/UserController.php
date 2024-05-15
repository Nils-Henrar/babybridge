<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //paginate the users

        $users = User::paginate(10);

        return view('admin.user.index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        $roles = Role::all();

        return view('admin.user.create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        // validate the request

        $data = $request->validated();

        $user = new User();

        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->email = $data['email'];
        $user->langue = $data['language'];
        $user->phone = $data['phone'];
        $user->address = $data['address'];
        $user->postal_code = $data['postal_code'];
        $user->city = $data['city'];

        $identifiers = $user->sendIdentifiersByEmail($user->firstname, $user->lastname);

        $user->login = $identifiers['login'];
        $user->password = bcrypt($identifiers['password']);

        $user->save();

        $user->roles()->attach($data['roles']);



        return redirect()->route('admin.user.index')->with('success', 'L\'utilisateur a été créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

        $user = User::find($id);

        return view('admin.user.show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //

        $user = User::find($id);

        $roles = Role::all();

        return view('admin.user.edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        // validate the request

        $data = $request->validated();

        $user = User::find($id);

        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->email = $data['email'];
        $user->langue = $data['language'];
        $user->phone = $data['phone'];
        $user->address = $data['address'];
        $user->postal_code = $data['postal_code'];
        $user->city = $data['city'];

        $user->save();

        $user->roles()->sync($data['roles']);

        return redirect()->route('admin.user.index')->with('success', 'L\'utilisateur a été modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $user = User::find($id);
        
        if($user->roles->contains('role', 'worker')) {
            $user->worker->sectionWorkers()->delete();
            $user->worker()->delete();
        }
    
        if($user->roles->contains('role', 'tutor')) {
            $user->tutor()->delete();
        }

        // Supprime tous les rôles associés à cet utilisateur
        $user->roles()->detach();
        // Supprime l'utilisateur lui-même
        $user->delete();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        return redirect()->route('admin.user.index')->with('success', 'L\'utilisateur a été supprimé avec succès');
    }


    public function workerProfile()
    {
        $user = auth()->user();
        return view('worker.user.profile', compact('user'));
    }


    public function tutorProfile()
    {
        $user = auth()->user();
        return view('tutor.user.profile', compact('user'));
    }
}
