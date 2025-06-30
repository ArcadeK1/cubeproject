<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'location', 'points'];

    public function competition()
    {
        return $this->belongsToMany(Competition::class, 'competition_challenge');
    }


    public function scores()
    {
        return $this->hasMany(Scores::class);
    }
}
