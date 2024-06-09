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
        $arrivalTime = Carbon::parse($this->arrival_time)->format('H:i');
        $departureTime = $this->departure_time ? Carbon::parse($this->departure_time)->format('H:i') : null;

        $entries = [];

        // Si seulement l'heure d'arrivée est présente
        $entries[] = [
            'type' => 'arrival',
            'time' => $arrivalTime,
            'description' => "{$this->child->firstname} est bien arrivé à {$arrivalTime}",
            'child_name' => $this->child->getFullNameAttribute(),
        ];

        // Si l'heure de départ est également présente
        if ($departureTime) {
            $entries[] = [
                'type' => 'departure',
                'time' => $departureTime,
                'description' => "{$this->child->firstname} est parti à {$departureTime}",
                'child_name' => $this->child->getFullNameAttribute(),
            ];
        }

        return $entries;
    }

}
