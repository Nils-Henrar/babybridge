<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Nap extends Model
{
    use HasFactory;

    protected $table = 'naps';

    protected $fillable = [
        'child_id',
        'started_at',
        'ended_at',
        'quality',
        'notes',
    ];

    public $timestamps = false;

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function duration()
    {
        // en heures et minutes
        $start = Carbon::parse($this->started_at);
        $end = Carbon::parse($this->ended_at);
        $diff = $start->diff($end);
        //form( 1h30)
        return $diff->format('%hh%I');
    }


    public function formatForJournal()
    {
        $description = $this->ended_at 
        ? $this->child->firstname . ' a dormi pendant ' . $this->duration() 
        : $this->child->firstname . ' est en train de dormir';
        return [
            [
            'type' => 'nap',
            'time' => Carbon::parse($this->started_at)->format('H:i'),
            'description' => $description,
            'child_name' => $this->child->getFullNameAttribute(),
            ],
        ];
    }
}
