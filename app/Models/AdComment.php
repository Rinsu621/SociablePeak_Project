<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdComment extends Model
{
    use HasFactory;
    protected $fillable=['user_id','ad_id','comment','business_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

}
