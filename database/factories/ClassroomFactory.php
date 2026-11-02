<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Classroom>
 */
class ClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Matematika', 'IPA Terpadu', 'Bahasa Indonesia', 'Bahasa Inggris', 'Informatika']).' Kelas '.fake()->randomElement(['7A', '7B', '8A', '8B', '9A']),
            'description' => fake()->sentence(),
            'join_code' => Classroom::generateUniqueJoinCode(),
            'teacher_id' => User::factory()->guru(),
            'academic_year' => '2026/2027',
            'is_active' => true,
        ];
    }
}
