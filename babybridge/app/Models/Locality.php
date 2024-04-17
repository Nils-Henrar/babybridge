<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    use HasFactory;

    protected $table = 'localities';

    protected $fillable = [
        'city',
        'locality',
        'postal_code',
        'street',
        'number',
    ];

    public $timestamps = false;

    public function tutors()
    {
        return $this->hasMany(Tutor::class);
    }

    public function workers()
    {
        return $this->hasMany(Worker::class);
    }
}
