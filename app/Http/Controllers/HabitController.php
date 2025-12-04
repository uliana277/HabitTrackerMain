<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

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

        $Habit = Habit::create([
            'name' => $request->name,
            'description' => $request->description,
            'start' => new DateTime($request->start)->format('Y-m-d'),
            'end' => new DateTime($request->end)->format('Y-m-d')
        ]);

        return Inertia::render('Home')->with("id", $Habit->id);
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

    public function getHabits()
    {
        return Habit::with('logs')->get();
    }
    public function index()
    {
        return Inertia::render('Home', [
            "habits" => Habit::with('logs')->get()  
        ]);
    }

}
