<?php

use App\Models\User;

use function Pest\Laravel\get;

it('requires authentication', function () {
    get(route('tickers.details'))->assertRedirectToRoute('login');
});

it('fetches quote data from the provider', function () {
    $response = $this->actingAs(User::factory()->create())
        ->get(route('ticker.details', [
            'symbol' => 'AAPL',
            'date' => '2024-11-07',
        ]))->assertStatus(200);

    $response->assertJsonStructure([
        'name',
        'exchange',
        'market_cap',
        'list_date',
    ]);
});
