<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Challenge;
use App\Models\Competition;
use App\Models\Scores;
use App\Models\User;



class ViewerController extends Controller
{
    public function index()
{
    $now = date('Y-m-d');

    $competition = DB::table('competitions')
        ->whereDate('date', $now)
        ->first(); // Получаем одно мероприятие

    if (!$competition) {
        return view('viewer.index', [
            'competition' => null,
            'challenges' => [],
            'teams' => [],
            'scoresByChallenge' => []
        ]);
    }

    $challenges = DB::table('competition_challenge')
        ->where('competition_id', $competition->id)
        ->join('challenges', 'competition_challenge.challenge_id', '=', 'challenges.id')
        ->select('challenges.*')
        ->get();

    $teams = DB::table('scores')
        ->join('users', 'scores.user_id', '=', 'users.id')
        ->where('scores.competition_id', $competition->id)
        ->where('users.role', 'team')
        ->select('users.id', 'users.name')
        ->distinct()
        ->get();

    $teamScores = DB::table('scores')
        ->join('users', 'scores.user_id', '=', 'users.id')
        ->join('competitions', 'scores.competition_id', '=', 'competitions.id')
        ->join('challenges', 'scores.challenge_id', '=', 'challenges.id')
        ->where('competitions.id', $competition->id)
        ->where('users.role', 'team')
        ->select(
            'users.id as team_id',
            'users.name as team_name',
            'challenges.id as challenge_id',
            'challenges.title as challenge_title',
            DB::raw('SUM(scores.score) as total_score')
        )
        ->groupBy('users.id', 'challenges.id')
        ->get();

    $scoresByChallenge = [];
    foreach ($challenges as $challenge) {
        $scoresByChallenge[$challenge->id] = [
            'title' => $challenge->title,
            'location' => $challenge->location,
            'teams' => []
        ];
    }

    foreach ($teamScores as $score) {
        $scoresByChallenge[$score->challenge_id]['teams'][$score->team_id] = [
            'team_name' => $score->team_name,
            'total_score' => $score->total_score
        ];
    }

    return view('viewer.index', compact('competition', 'challenges', 'teams', 'scoresByChallenge'));
}



public function getCompetitionsByDate(Request $request)
{
    $date = $request->query('date');
    $competitions = DB::table('competitions')
        ->whereDate('date', $date)
        ->get();

    return response()->json(['competitions' => $competitions]);
}


public function getCompetitionData(Request $request)
{
    $competitionId = $request->query('competition_id');

    $competition = DB::table('competitions')->where('id', $competitionId)->first();

    if (!$competition) {
        return response()->json([
            'competition' => null,
            'challenges' => [],
            'teams' => [],
            'scoresByChallenge' => []
        ]);
    }

    $challenges = DB::table('competition_challenge')
        ->where('competition_id', $competitionId)
        ->join('challenges', 'competition_challenge.challenge_id', '=', 'challenges.id')
        ->select('challenges.*')
        ->get();

    $teams = DB::table('scores')
        ->join('users', 'scores.user_id', '=', 'users.id')
        ->where('scores.competition_id', $competitionId)
        ->where('users.role', 'team')
        ->select('users.id', 'users.name')
        ->distinct()
        ->get();

    $teamScores = DB::table('scores')
        ->join('users', 'scores.user_id', '=', 'users.id')
        ->join('challenges', 'scores.challenge_id', '=', 'challenges.id')
        ->where('scores.competition_id', $competitionId)
        ->where('users.role', 'team')
        ->select(
            'users.id as team_id',
            'users.name as team_name',
            'challenges.id as challenge_id',
            'challenges.title as challenge_title',
            DB::raw('SUM(scores.score) as total_score')
        )
        ->groupBy('users.id', 'challenges.id')
        ->get();

    $scoresByChallenge = [];
    foreach ($challenges as $challenge) {
        $scoresByChallenge[$challenge->id] = [
            'title' => $challenge->title,
            'location' => $challenge->location,
            'teams' => []
        ];
    }

    foreach ($teamScores as $score) {
        $scoresByChallenge[$score->challenge_id]['teams'][$score->team_id] = [
            'team_name' => $score->team_name,
            'total_score' => $score->total_score
        ];
    }

    return response()->json([
        'competition' => $competition,
        'challenges' => $challenges,
        'teams' => $teams,
        'scoresByChallenge' => $scoresByChallenge
    ]);
}




}
