<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start' => $this->schedule,  // Assurez-vous que 'schedule' contient la date/heure de début
            'description' => $this->description,
            // Vous pouvez ajouter d'autres propriétés selon les besoins
        ];
    }
}
