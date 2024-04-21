<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionType extends Model
{
    use HasFactory;

    protected $table = 'section_types';

    protected $fillable = [
        'section_id',
        'type_id',
        'from',
        'to',
    ];
}
