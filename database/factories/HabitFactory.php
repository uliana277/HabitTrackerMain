<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Habit>
 */
class HabitFactory extends Factory
{
    public function definition(): array
    {
         return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'start' => fake()->date(),
            'end' => fake()->date(),
            
        ];
    }
    protected $model = \App\Models\Habit::class;

}
  
