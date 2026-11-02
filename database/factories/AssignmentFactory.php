<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'classroom_id' => Classroom::factory(),
            'title' => 'Tugas: ' . fake()->sentence(3),
            'instructions' => '<p>' . fake()->paragraph() . '</p>',
            'due_date' => now()->addDays(3),
            'max_points' => 100,
            'is_published' => true,
        ];
    }
}
