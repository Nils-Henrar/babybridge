<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';

    protected $fillable = [
        'nom',
        'slug',
        'type',
        'created_at',
    ];

    public $timestamps = true;

    public function enfants()
    {
        return $this->hasMany(Enfant::class);
    }
}
