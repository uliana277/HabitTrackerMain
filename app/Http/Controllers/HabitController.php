<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use App\Models\Habit;




class HabitController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start'
        ]);

        $habit = Habit::create([
            'name' => $request->name,
            'description' => $request->description,
            'start' => new DateTime($request->start)->format('Y-m-d'),
            'end' => new DateTime($request->end)->format('Y-m-d')
        ]);
    }
    public function mark(Request $request, Habit $habit)
    {
        HabitLog::updateOrCreate(
            [
                'habit_id' => $habit->id,
                'date' => $request->date,
            ],
            [
                'completed' => $request->completed,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function getHabits(): array
    {
        $habits = Habit::all();
    }
}
