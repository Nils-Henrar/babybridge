<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'title',
        'schedule',
        'description',
        'price',

    ];

    public $timestamps = false;

    public function sections()
    {
        return $this->belongsToMany(Section::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
