<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Scan>
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
            'timestamp' => $this->faker->date(),
            'symbol' => $this->faker->word,
            'previous_close' => $this->faker->randomNumber(),
            'gap_percent' => $this->faker->numberBetween(0, 10000),
            'float' => $this->faker->randomNumber(),
            'short_interest' => $this->faker->randomNumber(),
        ];
    }
}
