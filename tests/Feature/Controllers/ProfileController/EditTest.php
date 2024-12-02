<?php

use App\Models\User;

use function Pest\Laravel\get;

it('requires authentication', function () {
    get(route('profile.edit'))->assertRedirectToRoute('login');
});

it('returns the correct component', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('profile.edit'))->assertComponent('Profile/Edit');
});
