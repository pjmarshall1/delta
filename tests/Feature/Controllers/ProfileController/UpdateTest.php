<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('requires authentication', function () {
    get(route('profile.update'))->assertRedirectToRoute('login');
});

it('updates profile information', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('profile.update'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $user->refresh();

    expect($user->name)->toBe('Test User')
        ->and($user->email)->toBe('test@example.com')
        ->and($user->email_verified_at)->not()->toBeNull();
});
