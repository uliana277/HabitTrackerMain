<?php

use App\Models\Habit;
use App\Models\Home;


class HabitController extends Habit
{
    public function store(Request $request)
    {
       
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:completed,uncompleted',
            'progress' => 'required|integer|between:0,100',
        ]);

        $habit = Habit::create($data);

      
        return response()->json([
            'habit' => $habit
        ]);
    }
}
