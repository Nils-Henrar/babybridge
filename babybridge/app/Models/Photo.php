<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $table = 'photos';

    protected $fillable = [
        'child_id',
        'description',
        'taken_at',
        'path',
    ];

    public $timestamps = true;

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
