<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section;
use Faker\Provider\Medical;

class Child extends Model
{
    use HasFactory;

    protected $table = 'children';

    protected $fillable = [
        'lastname',
        'firstname',
        'gender',
        'birthdate',
        'special_infos',
    ];

    public $timestamps = false;

    /**
     * Fonctions de relation 
     */

    public function childSections()
    {
        return $this->hasMany(ChildSection::class);
    }

    public function tutors()
    {
        return $this->belongsToMany(Tutor::class);
    }


    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    public function childPhotos()
    {
        return $this->hasMany(Photo::class);
    }

    public function diaperChanges()
    {
        return $this->hasMany(DiaperChange::class);
    }

    public function childActivities()
    {
        return $this->hasMany(ChildActivity::class);
    }

    public function childMeals()
    {
        return $this->hasMany(Meal::class);
    }

    public function childNaps()
    {
        return $this->hasMany(Nap::class);
    }

    public function childMedRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
