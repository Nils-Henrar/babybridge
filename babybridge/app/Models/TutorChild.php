<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorChild extends Model
{
    use HasFactory;

    protected $table = 'tutor_child';


    protected $fillable = [
        'user_id',
        'child_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
