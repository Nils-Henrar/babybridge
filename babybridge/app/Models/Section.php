<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';

    protected $fillable = [
        'name',
        'slug',
        'created_at',
    ];

    public $timestamps = false;

    public function childSections()
    {
        return $this->hasMany(ChildSection::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
    public function sectionWorkers()
    {
        return $this->hasMany(SectionWorker::class);
    }

    public function sectionTypes()
    {
        return $this->hasMany(SectionType::class);
    }


    public function countChildren()// Récupère le nombre d'enfants dans la section actuelle
    {
        return $this->childSections->where('to', null)->count();
    }

    public function countWorkers() // Récupère le nombre de travailleurs dans la section actuelle
    {
        return $this->sectionWorkers->where('to', null)->count();
    }

    public function currentChildren() // Récupère les enfants dans la section actuelle
    {
        return $this->hasMany(ChildSection::class)->where('to', null);
    }

    public function currentWorkers() // Récupère les travailleurs dans la section actuelle
    {
        
        return $this->hasMany(SectionWorker::class)->where('to', null);
    }

    // public function currentType()
    // {
    //     return $this->sectionTypes->where('to', null)->first();
    // }

    // Dans Section.php
    public function currentType() // Récupère le type de section actuel
    {
        return $this->hasOne(SectionType::class)->whereNull('to')->with('type');
    }

    public static function getSortedSections() // Récupère les sections triées par type
    {
        return Section::with('currentType.type')->get()->sortBy(function ($section) {
            return optional($section->currentType)->type->name ?? 'N/A';
        });
    }
}
