<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('database connection works', function () {
    // Test basic database connection
    expect(DB::connection()->getPdo())->not->toBeNull();
});

test('can create and retrieve a user', function () {
    $user = \App\Models\User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    
    $retrievedUser = \App\Models\User::find($user->id);
    expect($retrievedUser)->not->toBeNull();
    expect($retrievedUser->name)->toBe('Test User');
});
