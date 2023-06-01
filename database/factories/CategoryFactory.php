<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
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
            'name' => $this->faker->name,
            'description' => $this->faker->sentence(6),
            'image' => "https://icon-library.com/images/no-image-icon/no-image-icon-0.jpg",
            'thumbnail' => "https://icon-library.com/images/no-image-icon/no-image-icon-0.jpg"
        ];
    }
}
