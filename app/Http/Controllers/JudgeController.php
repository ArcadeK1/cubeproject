<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Competition;
use App\Models\Scores;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class JudgeController extends Controller
{
    public function dashboard()
    {
        return view('judge.dashboard');
    }


    public function create()
    {
        return view('judges.create');
    }

    public function handleQRScan(Request $request)
    {
        // Валидация данных
        $request->validate([
            'team_name' => 'required|string|max:255',
        ]);

        // Получаем имя команды из QR-кода
        $teamName = $request->input('team_name');

        // Находим команду в базе данных
        $team = User::where('name', $teamName)->where('role', 'team')->first();

        if (!$team) {
            return response()->json([
                'success' => false,
                'message' => 'Команда не найдена.',
            ]);
        }


        $judgeId = auth()->id();

        // Находим мероприятие, в котором участвует команда
        $competition = Competition::whereHas('scores', function ($query) use ($team) {
            $query->where('user_id', $team->id);
        })
        ->whereHas('judges', function ($query) use ($judgeId) {
            $query->where('judge_id', $judgeId); // Проверяем, есть ли судья в мероприятии
        })
        ->first();

        if (!$competition) {
            return response()->json([
                'success' => false,
                'message' => 'Вы не можете редактировать баллы этой команды.',
            ]);
        }

        // Возвращаем URL для редактирования результатов
        return response()->json([
            'success' => true,
            'redirect_url' => route('competitions.show', ['competition' => $competition->id, 'team' => $team->id]),
        ]);
    }

    public function destroy(User $judge)
    {
        // Проверяем, что пользователь действительно имеет роль "judge"
        if ($judge->role !== 'judge') {
            return redirect()->back()->with('error', 'Этот пользователь не является судьёй.');
        }

        // Удаляем судью
        $judge->delete();

        return redirect()->back()->with('success', 'Судья успешно удалён.');
    }


    public function store(Request $request)
    {
        // Валидация данных
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Генерация случайного логина и пароля
        $login = Str::random(5); // Случайный логин из 5 символов
        $password = Str::random(5); // Случайный пароль из 5 символов

        // Создание нового пользователя с ролью "judge"
        $user = User::create([
            'name' => $request->input('name'),
            'login' => $login, // Добавляем логин
            'plain_password' =>  $password,
            'password' => Hash::make($password), // Хэшируем пароль
            'role' => 'judge',
        ]);

        // Перенаправление с данными для входа
        return redirect()->route('judges.create')
                         ->with('success', 'Судья успешно добавлен.')
                         ->with('name', $user->name)
                         ->with('login', $login)
                         ->with('password', $password);
    }
}