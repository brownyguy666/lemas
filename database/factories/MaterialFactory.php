<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Material>
 */
class MaterialFactory extends Factory
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
            'title' => fake()->sentence(4),
            'type' => Material::TYPE_TEXT,
            'content' => '<p>'.fake()->paragraphs(3, true).'</p>',
            'order' => fake()->numberBetween(1, 10),
            'is_published' => true,
        ];
    }
}
