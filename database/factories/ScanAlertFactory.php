<?php

namespace Database\Factories;

use App\Models\Scan;
use App\Models\ScanAlert;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ScanAlertFactory extends Factory
{
    protected $model = ScanAlert::class;

    public function definition(): array
    {
        return [
            'timestamp' => Carbon::now(),
            'scan_id' => Scan::factory()->create(),
            'symbol' => $this->faker->word(),
            'price' => $this->faker->randomNumber(),
            'volume' => $this->faker->randomNumber(),
            'float' => $this->faker->randomNumber(),
            'relative_volume_daily' => $this->faker->randomNumber(),
            'relative_volume_five' => $this->faker->randomNumber(),
            'gap_percent' => $this->faker->randomNumber(),
            'change_percent' => $this->faker->randomNumber(),
            'reviwed' => false,
            'short_interest' => $this->faker->randomNumber(),
            'strategy_name' => $this->faker->name(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
