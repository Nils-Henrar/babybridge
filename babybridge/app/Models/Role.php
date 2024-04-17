<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'role',
    ];

    public $timestamps = false;

    // relation many to many avec la table users

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
