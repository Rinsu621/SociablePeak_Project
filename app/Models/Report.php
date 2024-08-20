<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'reporter_id',
        'reported_user_id',
        'reason',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    // Relationship to get the user who was reported
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }
}
