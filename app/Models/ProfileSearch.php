<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileSearch extends Model
{
    use HasFactory;
    protected $fillable = ['searcher_id', 'searched_id'];

    // Relationship with the user who performed the search
    public function searcher()
    {
        return $this->belongsTo(User::class, 'searcher_id');
    }

    // Relationship with the user who was searched
    public function searched()
    {
        return $this->belongsTo(User::class, 'searched_id');
    }
}
