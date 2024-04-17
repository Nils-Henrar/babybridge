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

    public function workerSections()
    {
        return $this->hasMany(WorkerSection::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }




    // public function sections()
    // {
    //     return $this->belongsToMany(Section::class, 'worker_sections', 'worker_id', 'section_id')
    //         ->withPivot('from', 'to')
    //         ->withTimestamps();
    // }
}
