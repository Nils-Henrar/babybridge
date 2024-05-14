<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ChildMeal extends Model
{
    use HasFactory;

    protected $table = 'child_meal';

    protected $fillable = [
        'child_id',
        'meal_id',
        'meal_time',
        'quantity',
        'notes',
    ];

    public $timestamps = false;

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function formatForJournal()
    {
        return [
            'type' => 'meal',
            'time' => Carbon::parse($this->meal_time)->format('H:i'),
            'description' => $this->meal->type . ' - ' . $this->quantity,
            'child_name' => $this->child->getFullNameAttribute(),
        ];
    }
}
