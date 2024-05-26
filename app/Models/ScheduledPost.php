<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduledPost extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'description', 'status','likes', 'comments','set_time'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
