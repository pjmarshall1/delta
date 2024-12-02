<?php

use function Pest\Laravel\get;

it('redirects to dashboard', function () {
    get(route('home'))
        ->assertRedirect(route('dashboard.index'));
});
