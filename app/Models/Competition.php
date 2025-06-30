<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'date'];

    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'competition_challenge');
    }

    public function scores()
    {
        return $this->hasMany(Scores::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'scores', 'competition_id', 'user_id')
            ->withPivot('challenge_id', 'score')
            ->distinct();
    }

    public function judges()
    {
        return $this->belongsToMany(User::class, 'competition_judges', 'competition_id', 'judge_id');
    }

}
