<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'images';
    protected $fillable = ['post_id', 'file_path', 'caption'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
