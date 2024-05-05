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

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function childTutors()
    {
        return $this->hasMany(ChildTutor::class);
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

    public function ActivityChildren()
    {
        return $this->hasMany(ActivityChild::class);
    }

    public function childMeals()
    {
        return $this->hasMany(ChildMeal::class);
    }

    public function childNaps()
    {
        return $this->hasMany(Nap::class);
    }

    public function childMedRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function currentSection()
    {
        return $this->childSections()->where('to', null)->first();
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getAgeAttribute()
    {
        $date = new \DateTime($this->birthdate);
        $now = new \DateTime();
        $interval = $now->diff($date);
        return $interval->y;
    }
}
