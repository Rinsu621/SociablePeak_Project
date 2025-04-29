<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;
    protected $fillable = ['business_id', 'title', 'description', 'image_path'];
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
