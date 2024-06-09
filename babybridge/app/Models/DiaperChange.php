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

    public function translatePoopConsistency($consistency)
    {
        switch ($consistency) {
            case 'watery':
                return 'liquides';
            case 'soft':
                return 'molles';
            case 'normal':
                return 'normales';
            default:
                return $consistency; // Return the original value if no translation is found
        }
    }

    public function formatForJournal()
    {
        $translatedConsistency = $this->poop_consistency ? $this->translatePoopConsistency($this->poop_consistency) : '';

        return [
            [
            'type' => 'diaper_change',
            'time' => Carbon::parse($this->happened_at)->format('H:i'),
            'description' => "Changement de couche pour {$this->child->firstname} (les selles sont <strong>{$translatedConsistency}</strong>).",
            'child_name' => $this->child->getFullNameAttribute(),
            ],
        ];
    }
}
