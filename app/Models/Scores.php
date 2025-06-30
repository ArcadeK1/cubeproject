<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scores extends Model
{
    protected $fillable = ['user_id', 'competition_id', 'challenge_id', 'score'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
