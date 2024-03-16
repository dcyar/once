<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name'    => $this->faker->name(),
            'dni'     => $this->faker->unique()->numberBetween(10000000, 99999999),
            'address' => $this->faker->address(),
            'phone'   => $this->faker->numberBetween(900000000, 999999999),
        ];
    }
}
