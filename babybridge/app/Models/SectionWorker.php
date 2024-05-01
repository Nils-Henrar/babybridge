<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionWorker extends Model
{
    use HasFactory;

    protected $table = 'section_worker';

    protected $fillable = [
        'worker_id',
        'section_id',
        'from',
        'to',
    ];

    public $timestamps = false;

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
