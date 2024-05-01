<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionType extends Model
{
    use HasFactory;

    protected $table = 'section_type';

    protected $fillable = [
        'section_id',
        'type_id',
        'from',
        'to',
    ];

    public $timestamps = false;

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
