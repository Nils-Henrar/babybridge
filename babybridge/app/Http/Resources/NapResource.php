<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NapResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'duration' => $this->duration,  // Supposant que vous avez un accesseur pour cela
            'quality' => $this->quality
        ];
    }
}
