<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('CAT???')),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'photo' => fake()->imageUrl(),
            'parent_id' => null,
         ];
    }
}
