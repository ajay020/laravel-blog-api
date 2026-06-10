<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Foundation\Testing\RefreshDatabase;

// uses(RefreshDatabase::class);

test('user can login', function () {

    // this line stops showing red error line 
    /** @var Tests\TestCase $this */

    $user = User::factory()->create([
        'name' => 'Ajay',
        'email' => 'ajay@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'ajay@example.com',
        'password' => 'password',
    ]);

    $response
        ->assertOk()
        ->assertJsonStructure([
            'token',
            'user',
        ]);

});

test('user cannot login with invalid password', function () {
    /** @var Tests\TestCase $this */

    User::factory()->create([
        'email' => 'ajay@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'ajay@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertUnauthorized();
});
