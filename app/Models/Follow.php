<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;
    protected $table = 'follows';

    // Fields that are mass assignable
    protected $fillable = ['follower_id', 'following_id'];

    // Define the relationship to the follower (user who follows)
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    // Define the relationship to the following (business being followed)
    public function following()
    {
        return $this->belongsTo(Business::class, 'following_id');
    }
    public function user()
{
    return $this->belongsTo(User::class, 'follower_id');
}

}
