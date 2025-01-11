<?php

namespace Database\Factories;

use App\Models\Scan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Scan>
 */
class ScanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date('Y-m-d'),
            'symbol' => $this->faker->lexify('????'),
            'price' => $this->faker->numberBetween(100000, 999999),
            'gap_percent' => $this->faker->numberBetween(0, 10000),
            'float' => $this->faker->randomNumber(),
            'short_interest' => $this->faker->randomNumber(),
            'p_count' => $this->faker->numberBetween(1, 999),
            'm_count' => $this->faker->numberBetween(1, 999),
            'a_count' => $this->faker->numberBetween(1, 999),
            'reviewed' => $this->faker->boolean(),
            'name' => $this->faker->name(),
            'exchange' => $this->faker->word(),
            'market_cap' => $this->faker->randomNumber(),
            'list_date' => $this->faker->date('Y-m-d'),
        ];
    }
}
