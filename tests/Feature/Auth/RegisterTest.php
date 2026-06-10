<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can register', function () {
    
    /** @var Tests\TestCase $this */

    $response = $this->postJson('/api/register', [
        'name' => 'Ajay',
        'email' => 'ajay@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertCreated();

    $this->assertDatabaseHas('users', [
        'email' => 'ajay@example.com',
    ]);
});
