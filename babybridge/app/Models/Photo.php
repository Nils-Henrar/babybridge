<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;  

class Photo extends Model
{
    use HasFactory;

    protected $table = 'photos';

    protected $fillable = [
        'child_id',
        'description',
        'taken_at',
        'path',
    ];

    public $timestamps = false;

    public function child()
    {
        return $this->belongsTo(Child::class);
    }


    public function formatForJournal()
    {
        return [
            'type' => 'photo',
            'time' => Carbon::parse($this->taken_at)->format('H:i'),
            'description' => "Photo de {$this->child->getFullNameAttribute()} prise.",
            'child_name' => $this->child->getFullNameAttribute(),
            'image_url' => $this->path // Assurez-vous que le chemin est accessible pour le frontend
        ];
    }
}
