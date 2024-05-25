<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $table = 'childcare_workers';

    protected $fillable = [
        'user_id',
    ];

    public $timestamps = false;

    public function sectionWorkers()
    {
        return $this->hasMany(SectionWorker::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currentSection() // Récupère la section actuelle du travailleur
    {
        return $this->hasOne(SectionWorker::class)->where('to', null);
    }

    public function getCurrentChildren() // Récupère les enfants de la section actuelle du travailleur
    {
        $section = $this->currentSection->section ?? null;

        if (!$section) {
            return collect(); // Retourne une collection vide si aucune section n'est associée
        }

        return $section->currentChildren()->get()->map(function ($childSection) {
            return $childSection->child;
        });
    }
}
