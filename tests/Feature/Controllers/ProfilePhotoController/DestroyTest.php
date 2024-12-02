<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;

it('requires authentication', function () {
    delete(route('profile.photo.destroy'))->assertRedirectToRoute('login');
});
it('deletes profile photo', function () {
    $user = User::factory()->withProfilePhoto()->create();

    actingAs($user)
        ->delete(route('profile.photo.destroy'));

    $user->refresh();

    expect($user->profile_photo_path)->toBeNull();
});
