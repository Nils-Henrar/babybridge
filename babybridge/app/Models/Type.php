<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = 'types';

    protected $fillable = [
        'type',
    ];

    public $timestamps = false;

    public function sectionTypes()
    {
        return $this->hasMany(SectionType::class);
    }
}