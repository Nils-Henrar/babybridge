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

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, ChildTutor::class, 'child_id', 'child_tutor_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'child_tutor', 'child_id', 'user_id');
    }


    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function diaperChanges()
    {
        return $this->hasMany(DiaperChange::class);
    }

    public function activityChildren()
    {
        return $this->hasMany(ActivityChild::class);
    }

    public function childMeals()
    {
        return $this->hasMany(ChildMeal::class);
    }

    public function naps()
    {
        return $this->hasMany(Nap::class);
    }

    public function childMedRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function currentSection()
    {
        return $this->hasOne(ChildSection::class)->where('to', null);
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getAgeAttribute()
    // onaffiche nombre d'anées et de mois
    {
        $date = new \DateTime($this->birthdate);
        $now = new \DateTime();
        $interval = $now->diff($date);

        if($interval->y == 0){
            return $interval->m . ' mois';
        }

        return $interval->y . ' ans et ' . $interval->m . ' mois';
    }

    public function getBirthdateFormAttribute()
    {
        return date('d/m/Y', strtotime($this->birthdate));
    }
    public function getSectionWorkersAttribute()
    {
        $section = $this->currentSection;
        if ($section) {
            return $section->section->currentWorkers;
        }
        return null;
    }
}
