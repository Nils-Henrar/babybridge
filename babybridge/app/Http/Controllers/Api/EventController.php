<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    //

    public function index()
    {
        return EventResource::collection(Event::all());
    }
}
