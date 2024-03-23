<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $table = 'medical_records';

    protected $fillable = [
        'child_id',
        'record_type',
        'description',
        'created_at',
    ];

    public $timestamps = true;

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
