<?php

use App\Models\ScanAlert;
use App\Models\User;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\get;

it('requires authentication', function () {
    get(route('scans.import'))->assertRedirectToRoute('login');
});

it('imports scan from a sample file', function () {
    $this->withoutExceptionHandling();

    $this->actingAs(User::factory()->create())
        ->post(route('scans.import'), [
            'file' => new UploadedFile(base_path('/tests/Fixtures/scans/20240503_Momo.csv'), '20240503_Momo.csv', 'text/csv', null, true),
        ]);

    $this->assertDatabaseHas(ScanAlert::class, [
        'symbol' => 'HSDT',
        'timestamp' => '2024-05-03 10:51:44', // adjust for timezone
        'price' => 40500,
        'float' => 822522,
        'short_interest' => 72107,
        'gap_percent' => 5226,
        'change_percent' => 5226,
        'volume' => 248289,
        'relative_volume_daily' => 1755100,
        'relative_volume_five' => 133479000,
        'strategy_name' => 'Squeeze Alert - Up 10% in 10min',
    ]);
});

it('returns an error if the file size is too large', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('scans.import'), [
            'file' => UploadedFile::fake()->create('20241225_Momo.csv', 2048),
        ])->assertSessionHasErrors();
});
