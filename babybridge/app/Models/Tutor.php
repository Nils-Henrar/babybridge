<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;

    protected $table = 'tutors';

    protected $fillable = [
        'lastname',
        'firstname',
        'email',
        'phone',
        'locality_id',
    ];

    public $timestamps = false;

    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }

    //relation many to many avec la table children

    public function children()
    {
        return $this->belongsToMany(Child::class);
    }

    //relation many to many avec la table users

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
