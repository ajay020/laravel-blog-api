<?php

use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;

test('Guest cannot create a post', function () {
    /** @var Tests\TestCase $this */

    $this->postJson('/api/posts')
        ->assertUnauthorized();
});


test("Authenticated user can create a post", function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();
    $category = Category::factory()->create();

    Sanctum::actingAs($user);

   $response = $this->postJson('/api/posts', [
        'title' => 'let it go',
        'slug' => 'let-it-go',
        'body' => 'Content',
        'category_id' => $category->id,
        'published' => true,
    ]);

    $response->assertCreated();

    $this->assertDatabaseHas('posts', [
        'title' => 'let it go',
        'user_id' => $user->id,
    ]);
});
