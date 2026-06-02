<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $users = User::factory(10)->create();

        $categories = Category::factory(5)->create();

        Post::factory(100)
            ->recycle($users)
            ->recycle($categories)
            ->create();
    }
}
