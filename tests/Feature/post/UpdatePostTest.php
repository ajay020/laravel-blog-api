<?php

use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;

test("user cannot update another user's post", function () {
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

    $response = $this->putJson(
        "/api/posts/{$post->id}",
        [
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'body' => 'Updated Content',
            'category_id' => $category->id,
            'published' => true,
        ]
    );

    $response->assertForbidden();

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'User 2 Post',
    ]);
});

test("Owner CAN update their own post", function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();
    $category = Category::factory()->create();

    $post = $user->posts()->create([
        'title' => 'Original Title',
        'slug' => 'original-title',
        'body' => 'Content',
        'category_id' => $category->id,
        'published' => true,
    ]);

    Sanctum::actingAs($user);

    $response = $this->putJson(
        "/api/posts/{$post->id}",
        [
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'body' => 'Updated Content',
            'category_id' => $category->id,
            'published' => true,
        ]
    );

    $response->assertOk()
        ->assertJsonPath('title', 'Updated Title');

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'Updated Title',
    ]);
});