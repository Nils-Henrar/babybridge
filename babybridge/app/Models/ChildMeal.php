<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildMeal extends Model
{
    use HasFactory;

    protected $table = 'child_meals';

    protected $fillable = [
        'child_id',
        'meal_id',
        'meal_time',
        'quantity',
        'note',
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
}
