<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChildDailyJournalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'age' => $this->getAgeAttribute(),
            'attendances' => $this->attendances,
            'naps' => NapResource::collection($this->naps),
            'meals' => MealResource::collection($this->childMeals),
            'photos' => PhotoResource::collection($this->photos),
            'diaper_changes' => DiaperChangeResource::collection($this->diaperChanges),
            'activities' => ActivityResource::collection($this->activityChildren),
        ];
    }
}

