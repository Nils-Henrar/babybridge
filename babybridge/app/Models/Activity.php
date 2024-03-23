<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';

    protected $fillable = [
        'child_id',
        'description',
        'performed_at',
    ];

    public $timestamps = false;

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
