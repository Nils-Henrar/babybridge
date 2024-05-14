<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'child_id',
        'attendance_date',
        'arrival_time',
        'departure_time',
        // 'wake-up_time',
        // 'breakfast_time',
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
            'type' => 'arrival',
            'arrival_time' => Carbon::parse($this->arrival_time)->format('H:i'),
            'departure_time' => Carbon::parse($this->departure_time)->format('H:i'),
            'description' => "{$this->child->getFullNameAttribute()} est arrivé.",
            'child_name' => $this->child->getFullNameAttribute(),
        ];
    }
}
