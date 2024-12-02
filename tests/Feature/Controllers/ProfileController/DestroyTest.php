<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('requires authentication', function () {
    get(route('profile.destroy'))->assertRedirectToRoute('login');
});

it('deletes a users account', function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create();

    actingAs($user)->delete(route('profile.destroy'), [
        'password' => 'password',
    ])->assertOk();

    expect($user->fresh())->toBeNull();
});

it('requires the correct password to delete an account', function () {
    $user = User::factory()->create();

    actingAs($user)->delete(route('profile.destroy'), [
        'password' => 'wrong-password',
    ])->assertSessionHasErrors('password');

    expect($user->fresh())->not()->toBeNull();
});
