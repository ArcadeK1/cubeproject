<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Challenge; 
use App\Models\Competition; 
use App\Models\Scores; 
use Illuminate\Support\Facades\DB;


class TeamController extends Controller
{

    //Кабинет команды (team.dashboard)
    public function dashboard()
    {
        // Получаем текущего пользователя (команду)
        $team = auth()->user();

        // Проверяем, что пользователь имеет роль "Team"
        if ($team->role !== 'team') {
            return redirect()->route('login')->with('error', 'Доступ запрещён.');
        }

        // Получаем мероприятие, в котором команда участвует
        $competition = Competition::whereHas('scores', function ($query) use ($team) {
            $query->where('user_id', $team->id);
        })->first();

        if (!$competition) {
            return view('team.dashboard', [
                'team' => $team,
                'competition' => null,
                'challenges' => [],
                'scores' => [],
            ])->with('info', 'Команда не участвует ни в одном мероприятии.');
        }

        // Получаем испытания мероприятия
        $challenges = DB::table('competition_challenge')
            ->where('competition_id', $competition->id)
            ->join('challenges', 'competition_challenge.challenge_id', '=', 'challenges.id')
            ->select('challenges.*')
            ->get();

        // Получаем баллы команды по каждому испытанию
        $scores = Scores::where('user_id', $team->id)
            ->where('competition_id', $competition->id)
            ->get()
            ->keyBy('challenge_id'); // Группируем по challenge_id для удобства

        return view('team.dashboard', compact('team', 'competition', 'challenges', 'scores'));
    }

    //Форма создания команд (teams.create)
    public function create()
    {
        return view('teams.create');
    }


    //Обновление очков в кабинете команды (team.dashboard)
    public function refreshScores()
    {
        $team = auth()->user();
        $competition = Competition::whereHas('scores', function ($query) use ($team) {
            $query->where('user_id', $team->id);
        })->first();

        if (!$competition) {
            return response()->json([]);
        }

        $scores = Scores::where('user_id', $team->id)
            ->where('competition_id', $competition->id)
            ->get()
            ->map(function ($score) {
                return [
                    'challenge_id' => $score->challenge_id,
                    'score' => $score->score
                ];
            });

        return response()->json($scores);
    }


    // Сохранение новой команды
    public function store(Request $request)
    {
        // Валидация данных
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
        ], [
            'name.unique' => 'Выберите другое имя команды.',
        ]);

        // Генерация случайного логина и пароля
        $login = Str::random(5); // Случайный логин из 5 символов
        $password = Str::random(5); // Случайный пароль из 5 символов

        // Создание нового пользователя с ролью "Team"
        $user = User::create([
            'name' => $request->input('name'),
            'login' => $login, // Добавляем логин
            'password' => Hash::make($password), // Хэшируем пароль
            'plain_password' => $password,
            'role' => 'team',
        ]);

        // Перенаправление с данными для входа
        return redirect()->route('teams.create')
                         ->with('success', 'Команда успешно создана!')
                         ->with('name', $user->name)
                         ->with('login', $login)
                         ->with('password', $password);
    }


    //Удаление команды
    public function destroy(User $team)
    {
        // Проверяем, что удаляется именно команда (role = 'team')
        if ($team->role !== 'team') {
            return response()->json(['error' => 'Можно удалять только команды'], 403);
        }
    
        try {
            $team->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка при удалении команды'], 500);
        }
    }
}
