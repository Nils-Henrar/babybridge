<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Section;
use App\Models\SectionWorker;
use App\Models\User;
use App\Http\Requests\Worker\StoreWorkerRequest;
use App\Http\Requests\Worker\UpdateWorkerRequest;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Display a list of workers

        $workers = Worker::all();

        return view('admin.worker.index', [
            'workers' => $workers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $sections = Section::all();

        // Display the form to create a new worker

        return view('admin.worker.create', [
            'sections' => $sections,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkerRequest $request)
    {

        $data = $request->validated();

        $workerRoleId = Role::where('role', 'worker')->first()->id;
        
        $token = Str::random(60);

        DB::table('invitation_tokens')->insert([
            'token' => $token,
            'email' => $data['email'],
            'roles' => json_encode([$workerRoleId]),
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'language' => $data['language'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'postal_code' => $data['postal_code'],
            'city' => $data['city'],
            'section_id' => $data['section_id'],
            'expires_at' => Carbon::now()->addHours(48),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $link = route('register.form', ['token' => $token]);
        Mail::raw("Veuillez compléter votre inscription en suivant ce lien : $link", function ($message) use ($data) {
            $message->to($data['email'])->subject('Complétez votre inscription');
        });

        return redirect()->route('admin.worker.index')->with('success', 'Le travailleur a été créé avec succès et l\'invitation a été envoyée');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // 

        $worker = Worker::find($id);

        return view('admin.worker.show', [
            'worker' => $worker,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Display the form to edit a worker



        $worker = Worker::find($id);

        $sections = Section::all();

        return view('admin.worker.edit', [
            'worker' => $worker,
            'sections' => $sections,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkerRequest $request, string $id)
    {
        // validate the request

        $data = $request->validated();

        $worker = Worker::find($id);

        $worker->user->firstname = $data['firstname'];
        $worker->user->lastname = $data['lastname'];
        $worker->user->email = $data['email'];
        $worker->user->langue = $data['language'];
        $worker->user->phone = $data['phone'];
        $worker->user->address = $data['address'];
        $worker->user->postal_code = $data['postal_code'];
        $worker->user->city = $data['city'];

        $worker->user->save();

        if ($data['section_id'] != $worker->currentSection->section_id) {
            $worker->currentSection->to = now();
            $worker->currentSection->save();

            $sectionWorker = new SectionWorker();
            $sectionWorker->section_id = $data['section_id'];
            $sectionWorker->worker_id = $worker->id;
            $sectionWorker->save();
        }

        return redirect()->route('admin.worker.index')->with('success', 'Le travailleur a été mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $user = Worker::find($id)->user;
        $user->worker->sectionWorkers() ? $user->worker->sectionWorkers()->delete() : null;
        $user->worker->delete();
        $user->delete();

        $user->roles()->detach();


        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return redirect()->route('admin.worker.index')->with('success', 'Le travailleur a été supprimé avec succès');
    }
}
