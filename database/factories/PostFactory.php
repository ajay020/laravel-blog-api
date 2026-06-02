<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Str; 
use App\Models\User;
use App\Models\Category;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->numberBetween(1, 10000),
            'body' => fake()->paragraphs(5, true),
            'published' => true,

            'user_id' => User::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
