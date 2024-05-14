<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function formatForJournal()
    {
        return [
            'type' => 'diaper_change',
            'time' => Carbon::parse($this->happened_at)->format('H:i'),
            'description' => "Changement de couche pour {$this->child->getFullNameAttribute()} ({$this->poop_consistency}).",
            'child_name' => $this->child->getFullNameAttribute(),
        ];
    }
}
