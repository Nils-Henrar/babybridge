<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildSection extends Model
{
    use HasFactory;

    protected $table = 'child_section';

    protected $fillable = [
        'child_id',
        'section_id',
        'from',
        'to',
    ];

    public $timestamps = false;

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
