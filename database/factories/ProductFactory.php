<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_uuid' => Category::first()->uuid,
            'title' => fake()->word(),
            'price' => fake()->numberBetween(1200, 5000),
            'description' => fake()->realText(),
            'metadata' => json_encode(['image' => 'https://placehold.co/600x400']),
        ];
    }
}
