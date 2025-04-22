<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessProfilePicture extends Model
{
    use HasFactory;
    protected $fillable = [
        'business_id',
        'file_path',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
