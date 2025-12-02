<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Habit>
 */
class HabitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'start_date' => fake()->dateTime(),
            'end_date' => fake()->dateTime(),
            'status' => fake()->randomElement(['completed', 'uncompleted']),
            'progress' => fake()->randomElement(0, 100),

        ];
    }
}
