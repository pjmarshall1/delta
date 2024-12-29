<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('requires authentication', function () {
    get(route('aggregates'))->assertRedirectToRoute('login');
});

it('returns aggregate data from the provider', function () {
    $this->withoutExceptionHandling();

    actingAs(User::factory()->create())
        ->get(route('aggregates', [
            'symbol' => 'AAPL',
            'multiplier' => 5,
            'timespan' => 'minute',
            'startDate' => '2024-04-01',
            'endDate' => '2024-04-01',
        ]))->assertStatus(200);
});
