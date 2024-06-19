<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; 
use Illuminate\Support\Facades\Storage;

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
            [
            'type' => 'photo',
            'time' => Carbon::parse($this->taken_at)->format('H:i'),
            'description' => "<i class='fas fa-fw fa-camera' style='color : magenta'></i>Photo de {$this->child->firstname} : {$this->description}",
            'child_name' => $this->child->getFullNameAttribute(),
            'image_url' => Storage::url($this->path),
            ],
        ];
    }
}
