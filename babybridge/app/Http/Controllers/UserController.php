<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Section;
use App\Models\Child;
use App\Models\Worker;
use App\Models\SectionWorker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $sections = Section::all();
        $children = Child::all();

        return view('admin.user.create', [
            'roles' => $roles,
            'sections' => $sections,
            'children' => $children,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function invite(StoreUserRequest $request)
    {
        $data = $request->validated();

        // Vérifiez les données envoyées
        // dd($data);
        $token = Str::random(60);

        DB::table('invitation_tokens')->insert([
            'email' => $data['email'],
            'token' => $token,
            'roles' => json_encode($data['roles']),
            'child_ids' => isset($data['children']) ? json_encode($data['children']) : null,
            'section_id' => isset($data['section']) ? $data['section'] : null,
            'expires_at' => Carbon::now()->addHours(24), // Token expire in 24 hours
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $link = route('register.form', ['token' => $token]);
        Mail::raw("Veuillez compléter votre inscription en suivant ce lien : $link", function ($message) use ($data) {
            $message->to($data['email'])->subject('Complétez votre inscription');
        });

        return redirect()->route('admin.user.index')->with('success', 'L\'invitation a été envoyée avec succès');
    }


    public function showRegistrationForm($token)
    {
        $invitation = DB::table('invitation_tokens')->where('token', $token)->first();
    
        if (!$invitation || Carbon::parse($invitation->expires_at)->isPast()) {
            return redirect()->route('login')->with('error', 'Lien d\'invitation invalide ou expiré.');
        }
    
        return view('auth.register', [
            'token' => $token,
            'email' => $invitation->email,
            'section_id' => $invitation->section_id,
            'child_ids' => json_decode($invitation->child_ids),
        ]);
    }
    

    public function completeRegistration(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'login' => 'required|unique:users,login',
            'password' => 'required|confirmed|min:8',
            'firstname' => 'required|string|max:60',
            'lastname' => 'required|string|max:60',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'city' => 'required|string|max:60',
        ]);
    
        $invitation = DB::table('invitation_tokens')->where('token', $request->token)->first();
        if (!$invitation || Carbon::parse($invitation->expires_at)->isPast()) {
            DB::table('invitation_tokens')->where('token', $request->token)->delete();
            return redirect()->route('login')->with('error', 'Lien d\'invitation invalide ou expiré.');
        }
    
        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $invitation->email;
        $user->login = $request->login;
        $user->password = bcrypt($request->password);
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->postal_code = $request->postal_code;
        $user->city = $request->city;
        $user->langue = $request->language;
        $user->save();
    
        // Assign roles to the user
        $roles = json_decode($invitation->roles, true);
        $user->roles()->attach($roles);

        $tutorRoleId = Role::where('role', 'tutor')->first()->id;
        $workerRoleId = Role::where('role', 'worker')->first()->id;
    
        // Assign additional information based on role
        if (in_array($tutorRoleId, $roles)) {
            $childIds = json_decode($invitation->child_ids, true);
            foreach ($childIds as $childId) {
                $child = Child::find($childId);
                $child->users()->attach($user->id);
            }
        }
    
        if (in_array($workerRoleId, $roles)) {
            $worker = new Worker();

            $worker->user_id = $user->id;

            $worker->save();

            $sectionWorker = new SectionWorker();
            $sectionWorker->section_id = $invitation->section_id;
            $sectionWorker->worker_id = $worker->id;
            $sectionWorker->save();
        }
    
        DB::table('invitation_tokens')->where('token', $request->token)->delete();
    
        return redirect()->route('login')->with('success', 'Inscription complétée avec succès.');
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

        if ($user->roles->contains('role', 'worker')) {
            $user->worker->sectionWorkers() ? $user->worker->sectionWorkers()->delete() : null;
            $user->worker()->delete();
        }

        if ($user->roles->contains('role', 'tutor')) {
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

    public function editProfile()
    {
        $user = auth()->user();
        return view('tutor.user.edit', compact('user'));
    }
    

    public function updateProfile(Request $request)
    {
        $userId = auth()->id(); 
        $user = User::find($userId);

        $data = $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'language' => 'nullable|string|max:2',
            'city' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
        ]);

        // Log the user data before update
        Log::info('User before update: ', $user->toArray());

        // Attempt to update the user
        try {
            $user->update($data);
            // Log the user data after update
            Log::info('User after update: ', $user->toArray());
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'An error occurred while updating the profile.']);
        }

        return redirect()->route('tutor.profile')->with('success', 'Profil mis à jour avec succès.');
    }

    public function updateChildPhoto(Request $request, $childId)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $child = Child::findOrFail($childId);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'profile_photos/' . $filename;
            Storage::disk('public')->put($filePath, file_get_contents($file));

            // Supprimer l'ancienne photo si elle existe
            if ($child->photo_path) {
                Storage::disk('public')->delete($child->photo_path);
            }

            // Mettre à jour le chemin de la photo dans la base de données
            $child->photo_path = $filePath;
            $child->save();
        }

        return redirect()->route('tutor.child.profile', $child->id)->with('success', 'Photo de profil mise à jour avec succès.');
    }

}
