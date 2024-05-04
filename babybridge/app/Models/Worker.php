<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $table = 'childcare_workers';

    protected $fillable = [
        'user_id',
    ];

    public $timestamps = false;

    public function sectionWorkers()
    {
        return $this->hasMany(SectionWorker::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currentSections()
    {
        return $this->sectionWorkers->where('to', null);
    }
}
