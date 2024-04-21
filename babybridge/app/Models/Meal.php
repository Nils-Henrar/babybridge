<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $table = 'meals';

    protected $fillable = [
        'type',
    ];

    public $timestamps = false;

    public function childMeals()
    {
        return $this->hasMany(ChildMeal::class);
    }
}
