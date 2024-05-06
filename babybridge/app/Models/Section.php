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


    public function countChildren()
    {
        return $this->childSections->where('to', null)->count();
    }

    public function countWorkers()
    {
        return $this->sectionWorkers->where('to', null)->count();
    }

    public function currentChildren()
    {
        return $this->childSections->where('to', null);
    }

    public function currentWorkers()
    {
        $workers = [];

        $workers = $this->sectionWorkers->map(function ($sectionWorker) {
            //where to is null
            if ($sectionWorker->to == null) {
                return $sectionWorker->worker;
            }
        });

        return $workers;
    }

    // public function currentType()
    // {
    //     return $this->sectionTypes->where('to', null)->first();
    // }

    // Dans Section.php
    public function currentType()
    {
        return $this->hasOne(SectionType::class)->whereNull('to')->with('type');
    }

    public static function getSortedSections()
    {
        return Section::with('currentType.type')->get()->sortBy(function ($section) {
            return optional($section->currentType)->type->name ?? 'N/A';
        });
    }
}
