<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaperChange extends Model
{
    use HasFactory;

    protected $table = 'diaper_changes';

    protected $fillable = [
        'child_id',
        'poop_consistency',
        'happened_at',
        'notes',
    ];

    public $timestamps = false;

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
