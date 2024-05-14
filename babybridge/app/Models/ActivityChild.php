<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ActivityChild extends Model
{
    use HasFactory;

    protected $table = 'activity_child';

    protected $fillable = [
        'child_id',
        'activity_id',
        'performed_at',
    ];

    public $timestamps = false;

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function formatForJournal()
    {
        return [
            'type' => 'activity',
            'time' => Carbon::parse($this->performed_at)->format('H:i'),
            'description' => "{$this->child->getFullNameAttribute()} a participé à l'activité {$this->activity->description}.",
            'child_name' => $this->child->getFullNameAttribute(),
        ];
    }
}
