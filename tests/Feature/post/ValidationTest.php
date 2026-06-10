<?php

use App\Models\Category;
use App\Models\User;
use App\Models\Post;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;


test('title is required when creating a post', function () {

    /** @var Tests\TestCase $this */

    $user = User::factory()->create();

    $category = Category::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/posts', [
        // title intentionally missing

        'slug' => 'my-post',
        'body' => 'Post content',
        'category_id' => $category->id,
        'published' => true,
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'title',
        ]);
});

test('slug is required when creating a post', function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();
    $category = Category::factory()->create();

    Sanctum::actingAs($user);

    $response =  $this->postJson('/api/posts', [
        'title' => 'My post',
        // 'slug' => 'my-post',
        'body' => 'Post content',
        'category_id' => $category->id,
        'published' => true,
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'slug'
        ]);

});

test('body is required when creating a post', function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();
    $category = Category::factory()->create();

    Sanctum::actingAs($user);

    $response =  $this->postJson('/api/posts', [
        'title' => 'My post',
        'slug' => 'my-post',
        // 'body' => 'Post content',
        'category_id' => $category->id,
        'published' => true,
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'body'
        ]);

});

test('category must exist when creating a post', function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response =  $this->postJson('/api/posts', [
        'title' => 'My post',
        'slug' => 'my-post',
        'body' => 'Post content',
        'category_id' => 999999,
        'published' => true,
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
             'category_id',
        ]);
});

test('slug must be unique', function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();
    $category = Category::factory()->create();

    Sanctum::actingAs($user);

    Post::factory()->create([
        'slug' => 'my-post',
        'user_id' => $user->id,
        'category_id' => $category->id,
    ]);

    $response = $this->postJson('/api/posts', [
        'title' => 'Another Post',
        'slug' => 'my-post',
        'body' => 'Content',
        'category_id' => $category->id,
        'published' => true,
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'slug',
        ]);
});


test('image must be an image file', function () {

    /** @var Tests\TestCase $this */

    $user = User::factory()->create();
    $category = Category::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/posts', [
        'title' => 'Post',
        'slug' => 'post',
        'body' => 'Content',
        'category_id' => $category->id,
        'image' => UploadedFile::fake()->create(
            'document.pdf',
            100
        ),
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'image',
        ]);
});