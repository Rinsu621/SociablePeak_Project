<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserEngagement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'elapsed_time',
        'tab_switch',
    ];

    protected $dates = ['date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
