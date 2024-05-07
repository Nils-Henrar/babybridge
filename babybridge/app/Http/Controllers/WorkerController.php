<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Section;
use App\Models\SectionWorker;
use App\Models\User;
use App\Http\Requests\Worker\StoreWorkerRequest;
use App\Http\Requests\Worker\UpdateWorkerRequest;

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
        // Store un nouveau worker implique de créer un nouvel utilisateur et de lui assigner un rôle de worker et de lui assigner une section(si il en a une)
        //la table worker est liée à la table user par un user_id

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

        $user->assignRole('worker');

        $worker = new Worker();

        $worker->user_id = $user->id;

        $worker->save();

        if ($data['section_id']) {
            $sectionWorker = new SectionWorker();
            $sectionWorker->section_id = $data['section_id'];
            $sectionWorker->worker_id = $worker->id;
            $sectionWorker->save();
        }

        return redirect()->route('admin.worker.index')->with('success', 'Le travailleur a été créé avec succès');
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

        if ($data['section_id'] != $worker->section->id) {
            $worker->currentSection()->to = now();
            $worker->save();

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
        //
    }
}
