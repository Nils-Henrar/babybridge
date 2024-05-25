<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\ChildTutor;
use App\Models\ChildSection;

class EventController extends Controller
{
    //

    public function index()
    {
        return EventResource::collection(Event::all());
    }

    public function getAvailableEvents($userId)
    {
        $childrenIds = ChildTutor::where('user_id', $userId)->pluck('child_id');
        $sectionIds = ChildSection::whereIn('child_id', $childrenIds)->pluck('section_id');
        $events = Event::whereHas('sections', function ($query) use ($sectionIds) {
            $query->whereIn('section_id', $sectionIds);
        })->get(); // Récupère les événements disponibles pour les sections des enfants dont l'utilisateur est le tuteur

        return response()->json($events);
    }
}
