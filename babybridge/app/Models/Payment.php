<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'tutor_child_id',
        'event_id',
        'stripe_id',
        'status',
        'currency',
        'amount',
        'paid_at',
    ];

    public function tutorChild()
    {
        return $this->belongsTo(TutorChild::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
