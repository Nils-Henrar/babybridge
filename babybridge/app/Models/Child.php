<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    protected $table = 'children';

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
