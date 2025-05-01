<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdImage extends Model
{
    use HasFactory;
    protected $table = 'ad_images';
    protected $fillable = ['ad_id', 'file_path', 'caption'];

    public function ad()
    {
        return $this->belongsTo(Ad::class, 'ad_id');
    }
}
