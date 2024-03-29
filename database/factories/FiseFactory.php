<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fise>
 */
class FiseFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'client_id'       => $this->faker->numberBetween(1, 10),
            'code'            => $this->faker->unique()->numberBetween(100000000000, 999999999999),
            'amount'          => 25,
            'expiration_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'used_at'         => $this->faker->boolean(40) ? $this->faker->dateTimeBetween('-2 week', 'now') : null,
            'notes'           => $this->faker->boolean(25) ? $this->faker->sentence() : null,
        ];
    }
}
