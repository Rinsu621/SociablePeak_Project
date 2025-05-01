<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;
    protected $fillable = ['business_id', 'title', 'description', 'category', 'status', 'privacy', 'likes', 'comments'];
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function adimages()
    {
        return $this->hasMany(AdImage::class, 'ad_id');
    }

  

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
