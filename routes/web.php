<?php


use App\Http\Controllers\HabitController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Habit;


Route::post('/habit/create', [HabitController::class, 'store'])->name("habit.create");
Route::post('/habit/{habit}/mark', [HabitController::class, 'mark']) ->name('habit.mark');


Route::get('/', [HabitController::class, 'index']); 
