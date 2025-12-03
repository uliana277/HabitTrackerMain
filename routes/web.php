<?php

use App\Http\Controllers\HabitController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        "text" => [1, 2, 3]
    ]);
});

Route::post('/habit/create', [HabitController::class, 'store'])->name("habit.create");