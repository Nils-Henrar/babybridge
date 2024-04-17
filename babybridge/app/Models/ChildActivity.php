<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildActivity extends Model
{
    use HasFactory;

    protected $table = 'child_activities';

    protected $fillable = [
        'child_id',
        'activity_id',
        'performed_at',
    ];

    public $timestamps = false;

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
