<?php

use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;

test("user cannot delete another user's post", function () {
    /** @var Tests\TestCase $this */
    
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $category = Category::factory()->create();

    $post = $user2->posts()->create([
        'title' => 'User 2 Post',
        'slug' => 'user-2-post',
        'body' => 'Content',
        'category_id' => $category->id,
        'published' => true,
    ]);

    Sanctum::actingAs($user1);

    $response = $this->deleteJson("/api/posts/{$post->id}");

    $response->assertForbidden();
    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'User 2 Post',
    ]);
});


test("owener can delete their own post", function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();
    $category = Category::factory()->create();
    $post = $user->posts()->create([
        'title' => 'User Post',
        'slug' => 'user-post',
        'body' => 'Content',
        'category_id' => $category->id,
        'published' => true,
    ]);

    Sanctum::actingAs($user);

    $reposne = $this->deleteJson("/api/posts/{$post->id}");

    $reposne->assertNoContent();
    $this->assertDatabaseMissing('posts', [
        'id' => $post->id,
    ]);
});