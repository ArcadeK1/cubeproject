<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Scores;
use App\Models\User;
use App\Models\Competition;


class ScoreController extends Controller
{
    public function update(Request $request, Competition $competition, User $team)
    {
        // Валидация данных
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:0',
        ]);

        // Обновление баллов
        foreach ($request->input('scores') as $challengeId => $score) {
            Scores::updateOrCreate(
                [
                    'user_id' => $team->id,
                    'competition_id' => $competition->id,
                    'challenge_id' => $challengeId,
                ],
                [
                    'score' => $score,
                ]
            );
        }

        return redirect()->back()->with('success', 'Баллы успешно обновлены.');
    }
}

