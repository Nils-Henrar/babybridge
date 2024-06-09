<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\ChildTutor;
use App\Models\Section;
use App\Http\Requests\Child\StoreChildRequest;
use App\Http\Requests\Child\UpdateChildRequest;
use App\Models\User;
use App\Models\ChildSection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChildController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function adminIndex()
    {
        $children = Child::all();

        return view('admin.child.index', [
            'children' => $children,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Display the form to create a new child

        $tutors = ChildTutor::all();



        $sections = Section::all();

        return view('admin.child.create', [
            'tutors' => $tutors,
            'sections' => $sections,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChildRequest $request)
    {

        $data = $request->validated();

        // dd($data);
        // Traitement pour la création de l'enfant
        $child = new Child;
        $child->lastname = $data['lastname'];
        $child->firstname = $data['firstname'];
        $child->birthdate = $data['birthdate'];
        $child->special_infos = $data['special_infos'];

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file = $request->file('photo');
            
            // slugify the name
            $originalName = Str::slug($child->fullname);
            // Changer le nom du fichier
            $newOriginalName = $originalName . '.' . $file->getClientOriginalExtension();
    
            $targetPath = "profile/{$child->fullname}";
            
            // Assurez-vous que le répertoire cible existe
            Storage::disk('public')->makeDirectory($targetPath);
    
            // Stocker le fichier dans le dossier spécifié
            $photoPath = $file->storeAs($targetPath, $newOriginalName, 'public'); 
    
            $child->photo_path = $photoPath;
        }
        
        $child->save();

        if ($data['section']) {
            $childSection = new ChildSection;
            $childSection->child_id = $child->id;
            $childSection->section_id = $data['section'];
            $childSection->save();
        }

        // Traitement pour la création des tuteurs

        foreach ($data['tutor_lastname'] as $key => $value) {
            $tutor = new User;
            $tutor->lastname = $data['tutor_lastname'][$key];
            $tutor->firstname = $data['tutor_firstname'][$key];
            $tutor->email = $data['tutor_email'][$key];
            $tutor->phone = $data['tutor_phone'][$key];
            $tutor->langue = $data['tutor_language'][$key];
            $tutor->address = $data['tutor_address'][$key];
            $tutor->postal_code = $data['tutor_postal_code'][$key];
            $tutor->city = $data['tutor_city'][$key];

            $identifiers = $tutor->sendIdentifiersByEmail($tutor->firstname, $tutor->lastname);

            $tutor->login = $identifiers['login'];

            $tutor->password = bcrypt($identifiers['password']);

            try {
                $tutor->save();
            } catch (\Exception $e) {
                Log::error("Erreur lors de l'enregistrement du tuteur: " . $e->getMessage());
                return back()->withErrors('Erreur lors de l\'enregistrement du tuteur.');
            }

            //ajouter un enregistrement dans la table role_user

            $tutor->assignRole('tutor');

            $childTutor = new ChildTutor;
            $childTutor->child_id = $child->id;
            $childTutor->user_id = $tutor->id;
            $childTutor->save();
        }
        return redirect()->route('admin.child.index')->with('success', 'L\'enfant a été créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Display the child details

        $child = Child::findOrFail($id);

        return view('admin.child.show', [
            'child' => $child,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Display the form to edit a child

        $child = Child::findOrFail($id);

        $sections = Section::all();



        return view('admin.child.edit', [
            'child' => $child,
            'sections' => $sections,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChildRequest $request, string $id)
    {

        // validate the request
        $data = $request->validated();

        $child = Child::find($id);

        $child->lastname = $data['lastname'];
        $child->firstname = $data['firstname'];
        $child->birthdate = $data['birthdate'];
        $child->special_infos = $data['special_infos'];


        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $file = $request->file('photo');

            // détruire l'ancienne photo
            Storage::disk('public')->delete($child->photo_path);
            
            // slugify the name
            $originalName = Str::slug($child->fullname);
            // Changer le nom du fichier
            $newOriginalName = $originalName . '.' . $file->getClientOriginalExtension();
    
            $targetPath = "profile/{$child->fullname}";
            
            // Assurez-vous que le répertoire cible existe
            Storage::disk('public')->makeDirectory($targetPath);
    
            // Stocker le fichier dans le dossier spécifié
            $photoPath = $file->storeAs($targetPath, $newOriginalName, 'public'); 
    
            $child->photo_path = $photoPath;
        }

        $child->save();


        if ($data['section']) {
            $childSection = $child->currentSection;

            if ($data['section'] != $childSection->section->id) {
                $childSection->to = now();
                $childSection->save();

                $newChildSection = new ChildSection;

                $newChildSection->child_id = $child->id;
                $newChildSection->section_id = $data['section'];

                $newChildSection->save();
            }
        }

        // Traitement pour la modification des champs des tuteurs

        foreach ($data['tutor_id'] as $key => $value) {
            $tutor = User::find($data['tutor_id'][$key]);
            $tutor->lastname = $data['tutor_lastname'][$key];
            $tutor->firstname = $data['tutor_firstname'][$key];
            $tutor->email = $data['tutor_email'][$key];
            $tutor->phone = $data['tutor_phone'][$key];
            $tutor->langue = $data['tutor_language'][$key];
            $tutor->address = $data['tutor_address'][$key];
            $tutor->postal_code = $data['tutor_postal_code'][$key];
            $tutor->city = $data['tutor_city'][$key];

            $tutor->save();
        }


        return redirect()->route('admin.child.index')->with('success', 'L\'enfant a été modifié avec succès');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // enlever les contraintes de clé étrangère

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $child = Child::find($id);


        $child->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return redirect()->route('admin.child.index')->with('success', 'L\'enfant a été supprimé avec succès');
    }

    function dailyJournal()
    {
        //récupérer les enfants du tuteur connecté
        
        $children = auth()->user()->children;
        return view('tutor.child.daily_journal', compact('children'));
    }


    public function profileForTutor($childId)
    {
        $child = Child::findOrFail($childId);
        return view('tutor.child.profile', compact('child'));
    }


    public function profileForWorker($childId)
    {
        $child = Child::with('currentSection.section')->findOrFail($childId);
        return view('worker.section.child.profile', compact('child'));
    }
}
