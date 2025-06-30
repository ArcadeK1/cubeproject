<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Competition;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    // Отображение списка испытаний
    public function index()
    {
        $challenges = Challenge::with('competition')->get();
        return view('challenges.index', compact('challenges'));
    }

    // Показ формы для создания нового испытания
    public function create()
    {
        $competitions = Competition::all();
        return view('challenges.create', compact('competitions'));
    }

    // Сохранение нового испытания
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255|unique:challenges,title',
            'location' => 'required|max:255',
            'points' => 'required|integer|min:0',
        ],[
            'title.unique' => 'Испытание с таким названием уже есть в системе.',
        ]);

        $challenge = Challenge::create([
            'title' => $request->input('title'),
            'location' => $request->input('location'),
            'points' => $request->input('points'),
        ]);


        return redirect()->route('competitions.create')
                         ->with('success', 'Испытание успешно создано.');
    }

    // Отображение конкретного испытания
    public function show(Challenge $challenge)
    {
        return view('challenges.show', compact('challenge'));
    }


    // Обновление информации об испытании
    public function update(Request $request, Challenge $challenge)
    {
        $request->validate([
            'title' => 'required|max:255',
            'location' => 'required|max:255',
            'points' => 'required|integer|min:0',
            'competition_id' => 'required|exists:competitions,id',
        ]);

        $challenge->update($request->all());
        return redirect()->route('challenges.index')
                         ->with('success', 'Испытание успешно обновлено.');
    }

    // Удаление испытания
    public function destroy(Challenge $challenge)
    {
        try {
            $challenge->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ошибка при удалении испытания'], 500);
        }
    }
}
