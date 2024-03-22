<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enfant extends Model
{
    use HasFactory;

    protected $table = 'enfants';

    protected $fillable = [
        'section_id',
        'lastname',
        'firstname',
        'gender',
        'birthdate',
        'special_infos',
    ];

    public $timestamps = false;

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
