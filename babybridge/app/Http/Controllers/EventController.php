<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Section;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use Illuminate\Support\Str;
use App\Models\Child;
use App\Models\Payment;
use App\Models\ChildTutor;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Display the list of events

        $events = Event::all();

        return view('admin.event.index', [
            'events' => $events,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Display the form to create a new event

        $sections = Section::getSortedSections();


        return view('admin.event.create', [
            'sections' => $sections,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();

        $event = new Event();
        $event->title = $data['title'];
        $event->schedule = $data['schedule'];
        $event->description = $data['description'];
        $event->slug = Str::slug($data['title']);
        $event->price = $data['price'];
        $event->save();

        $event->sections()->attach($data['sections']);


        // si le prix est null, on ne crée pas de paiement
        if ($event->price === null) {
            return redirect()->route('admin.event.index')->with('success', 'L\'événement a été créé avec succès');
        }

        // Ajoute des paiements en attente pour chaque tuteur-enfant des sections associées
        $sectionIds = $data['sections'];
        $children = Child::whereHas('childSections', function ($query) use ($sectionIds) {
            $query->whereIn('section_id', $sectionIds);
        })->with('childTutors')->get();

        foreach ($children as $child) {
            var_dump('child', $child);
            foreach ($child->childTutors as $tutor) {
                var_dump('tutor', $tutor);
                if ($tutor) {
                    Payment::create([
                        'child_tutor_id' => $tutor->id,
                        'event_id' => $event->id,
                        'amount' => $event->price,
                        'status' => 'pending',
                        'paid_at' => null,
                    ]);
                }
            }
        }




        return redirect()->route('admin.event.index')->with('success', 'L\'événement a été créé avec succès');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Display the details of an event

        $event = Event::with(['sections.childSections' => function ($query) {
            $query->whereNull('to')->with('child');
        }])->findOrFail($id);


        return view('admin.event.show', [
            'event' => $event
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Display the form to edit an event

        $event = Event::findOrFail($id);

        $sections = Section::getSortedSections();

        return view('admin.event.edit', [
            'event' => $event,
            'sections' => $sections,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, string $id)
    {
        // validate the request, ne pas oublier que si on change les sections, il faudra supprimer les anciennes et ajouter les nouvelles, ce qui implique de faire un sync et d'enlever les contraintes de clé étrangère

        $data = $request->validated();

        $event = Event::find($id);

        $event->title = $data['title'];
        $event->schedule = $data['schedule'];
        $event->description = $data['description'];
        $event->slug = Str::slug($data['title']);

        $event->save();

        $event->sections()->sync($data['sections']);

        return redirect()->route('admin.event.index')->with('success', 'L\'événement a été modifié avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete an event

        $event = Event::findOrFail($id);

        $event->sections()->detach();

        $event->payments()->delete();

        $event->delete();

        return redirect()->route('admin.event.index')->with('success', 'L\'événement a été supprimé avec succès');
    }

    /**
     * 
     * Vue pour afficher et sélectionner les événements par le tuteur
     */

    public function selectEvents()

    {
        $events = Event::all();

        return view('tutor.event.index', [
            'events' => $events,
        ]);
    }
}
