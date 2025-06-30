<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Challenge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class CompetitionController extends Controller
{
    // Отображение списка мероприятий
    public function index()
    {
        $competitions = Competition::with(['challenges', 'participants'])->get();
        return view('competitions.index', compact('competitions'));
    }


    // Показ формы для создания нового мероприятия
    public function create()
    {
        $challenges = Challenge::all();
        $teams = User::where('role', 'team')->get();
        $judges = User::where('role', 'judge')->get();
        return view('competitions.create', compact('challenges', 'teams', 'judges'));
    }


//Хранение мероприятий
public function store(Request $request)
{
    // Валидация данных
    $request->validate([
        'name' => 'required|string|max:255',
        'challenges' => 'required|array',
        'challenges.*' => 'exists:challenges,id', // Проверяем, что выбранные challenges существуют
        'teams' => 'required|array',
        'teams.*' => 'exists:users,id', // Проверяем, что выбранные пользователи существуют
    ]);

    // Создаем мероприятие
    $competition = Competition::create([
        'name' => $request->input('name'),
        'date' => $request->input('date'), // Добавляем значение для поля date
    ]);

    // Привязываем выбранные испытания к мероприятию через таблицу competition_challenge
    foreach ($request->input('challenges') as $challengeId) {
        DB::table('competition_challenge')->insert([
            'competition_id' => $competition->id,
            'challenge_id' => $challengeId,
        ]);
    }

    // Привязываем выбранных пользователей к мероприятию и испытаниям через таблицу scores
    foreach ($request->input('teams') as $teamId) {
        foreach ($request->input('challenges') as $challengeId) {
            DB::table('scores')->insert([
                'user_id' => $teamId,
                'competition_id' => $competition->id,
                'challenge_id' => $challengeId,
                'score' => 0, // Начальный балл можно установить в 0
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

     // Привязываем выбранных судей к мероприятию
    foreach ($request->input('judges') as $judgeId) {
        DB::table('competition_judges')->insert([
                'competition_id' => $competition->id,
                'judge_id' => $judgeId,
            ]);
        }

    return redirect()->route('admin.dashboard')->with('success', 'Мероприятие успешно создано!');
}
    

    public function show(Competition $competition, User $team)
    {
        // Проверяем, что команда участвует в мероприятии
        if (!$team->scores()->where('competition_id', $competition->id)->exists()) {
            return redirect()->back()->with('error', 'Команда не участвует в этом мероприятии.');
        }

        // Получаем испытания мероприятия
        $challenges = $competition->challenges;

        // Получаем баллы команды по каждому испытанию
        $scores = $team->scores()
            ->whereIn('challenge_id', $challenges->pluck('id'))
            ->get()
            ->keyBy('challenge_id'); // Группируем по challenge_id для удобства


        
            $competitions = $team->competitions()->distinct()->with('challenges')->get();

        return view('competitions.show', compact('competition', 'team', 'challenges', 'scores', 'competitions'));

    }

    // Показ формы для редактирования соревнования
    public function edit(Competition $competition)
    {
        $challenges = Challenge::all();
        $teams = User::where('role', 'team')->get();
        $judges = User::where('role', 'judge')->get();
        return view('competitions.edit', compact('competition', 'challenges', 'teams', 'judges'));
    }


    //Подробная информация о мероприятии
    public function info($competitionID)
    {
        // Получаем информацию о мероприятии
        $competition = Competition::findOrFail($competitionID);

        // Получаем все закреплённые за мероприятием Challenge через таблицу competition_challenge
        $challenges = DB::table('competition_challenge')
            ->where('competition_id', $competitionID)
            ->join('challenges', 'competition_challenge.challenge_id', '=', 'challenges.id')
            ->select('challenges.*') // Выбираем все поля из таблицы challenges
            ->get();

        // Получаем все команды (участники с ролью "team")
        $teams = DB::table('scores')
        ->join('users', 'scores.user_id', '=', 'users.id')
        ->where('scores.competition_id', $competitionID)
        ->where('users.role', 'team')
        ->select('users.id', 'users.name')
        ->distinct() // Убедимся, что каждая команда будет только один раз
        ->get();


        $judges = DB::table('competition_judges')
        ->where('competition_id', $competitionID)
        ->join('users', 'competition_judges.judge_id', '=', 'users.id')
        ->select('users.id', 'users.name')
        ->get();

        // Получаем баллы для каждой команды по каждой компетенции
        $teamScores = DB::table('scores')
            ->join('users', 'scores.user_id', '=', 'users.id')
            ->join('competitions', 'scores.competition_id', '=', 'competitions.id')
            ->join('challenges', 'scores.challenge_id', '=', 'challenges.id')
            ->where('competitions.id', $competitionID)
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

        // Структурируем данные для удобного вывода в таблице
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

        return view('competitions.info', compact('competition', 'challenges', 'teams', 'scoresByChallenge', 'judges'));
    }

    // Обновление информации о мероприятии
    public function update(Request $request, Competition $competition)
    {
        $request->validate([
            'name' => 'required|unique:competitions,name,' . $competition->id . '|max:255',
        ]);
    
        // Удаляем существующие связи мероприятия с испытаниями, командами и судьями
        DB::table('competition_challenge')->where('competition_id', $competition->id)->delete();
        DB::table('scores')->where('competition_id', $competition->id)->delete();
        DB::table('competition_judges')->where('competition_id', $competition->id)->delete();
    
        // Привязываем новые испытания, если они выбраны
        $challenges = $request->input('challenges', []);
        foreach ($challenges as $challengeId) {
            DB::table('competition_challenge')->insert([
                'competition_id' => $competition->id,
                'challenge_id' => $challengeId,
            ]);
        }
    
        // Получаем все новые команды
        $newTeams = $request->input('teams', []);
    
        foreach ($newTeams as $teamId) {
            foreach ($challenges as $challengeId) {
                DB::table('scores')->insert([
                    'user_id' => $teamId,
                    'competition_id' => $competition->id,
                    'challenge_id' => $challengeId,
                    'score' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    
        // Обновляем пароли у всех команд, привязанных к мероприятию
        foreach ($newTeams as $teamId) {
            $newLogin = Str::random(5);
            $newPassword = Str::random(5); // Генерируем новый пароль
            DB::table('users')
                ->where('id', $teamId)
                ->update([
                    'login' => $newLogin,
                    'password' => Hash::make($newPassword),
                    'plain_password' => $newPassword,
                ]);
    
            // Можно записывать пароли в лог или отправлять их командам
            \Log::info("Команде ID {$teamId} установлен новый пароль: {$newPassword}");
        }
    
        // Привязываем новых судей, если они выбраны
        $judges = $request->input('judges', []);
        foreach ($judges as $judgeId) {
            DB::table('competition_judges')->insert([
                'competition_id' => $competition->id,
                'judge_id' => $judgeId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    
        // Обновляем данные мероприятия
        $competition->update($request->only(['name', 'date']));
    
        return redirect()->route('admin.dashboard')->with('success', 'Соревнование успешно обновлено.');
    }




// Генерируем отчёт о мероприятии с логинами и пароями команд/судей
public function generateReport($competitionID)
{
    // Получаем информацию о мероприятии
    $competition = Competition::findOrFail($competitionID);

    // Получаем всех участников (команды)
    $teams = DB::table('scores')
        ->join('users', 'scores.user_id', '=', 'users.id')
        ->where('scores.competition_id', $competitionID)
        ->where('users.role', 'team')
        ->select('users.id', 'users.name', 'users.login', 'users.plain_password') // Добавляем логины и пароли
        ->distinct()
        ->get();
    
    $judges = DB::table('competition_judges')
        ->join('users', 'competition_judges.judge_id', '=', 'users.id')
        ->where('competition_judges.competition_id', $competitionID)
        ->where('users.role', 'judge')
        ->select('users.id', 'users.name', 'users.login', 'users.plain_password') // Добавляем логины и пароли
        ->distinct()
        ->get();
    


    // Генерируем PDF
    $pdf = Pdf::loadView('pdf.report', compact('competition', 'teams', 'judges'));

    return $pdf->download('report_' . $competition->id . '.pdf');
}



    // Удаление мероприятия
   public function destroy(Competition $competition)
    {
        DB::transaction(function () use ($competition) {
            // Удаляем связанные испытания
            $competition->challenges()->detach();

            $competition->judges()->detach();

            // Удаляем связанные результаты
            $competition->scores()->delete();

            // Удаляем само соревнование
            $competition->delete();
        });

        return redirect()->route('admin.dashboard')->with('success', 'Мероприятие успешно удалено.');
    }
}
