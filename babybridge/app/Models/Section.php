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

    public $timestamps = true;

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
}
