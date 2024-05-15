<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildTutor extends Model
{
    use HasFactory;

    protected $table = 'child_tutor';


    protected $fillable = [
        'user_id',
        'child_id',
    ];

    public $timestamps = false;

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
        return $this->hasMany(Payment::class , 'child_tutor_id');
    }
}
