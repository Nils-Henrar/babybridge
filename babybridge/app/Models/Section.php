<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'created_at',
    ];

    public $timestamps = true;

    public function children()
    {
        return $this->belongsToMany(Child::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function workers()
    {
        return $this->belongsToMany(Worker::class);
    }
}
