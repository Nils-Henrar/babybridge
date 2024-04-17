<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerSection extends Model
{
    use HasFactory;

    protected $table = 'worker_sections';

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
