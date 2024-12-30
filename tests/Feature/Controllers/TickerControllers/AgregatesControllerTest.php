<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('requires authentication', function () {
    get(route('aggregates'))->assertRedirectToRoute('login');
});

it('returns aggregate data from the provider', function () {
    $this->withoutExceptionHandling();

    $response = actingAs(User::factory()->create())
        ->get(route('ticker.aggregates', [
            'symbol' => 'AAPL',
            'multiplier' => 5,
            'timespan' => 'minute',
            'startDate' => '2024-04-01',
            'endDate' => '2024-04-01',
        ]))->assertStatus(200);

    $response->assertJsonStructure([[
        'time',
        'open',
        'high',
        'low',
        'close',
        'volume',
    ]]);
});
