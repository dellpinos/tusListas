<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Provider>
 */
class ProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->company(),
            'email' => $this->faker->email(),
            'telefono' => $this->faker->phoneNumber(),
            'vendedor' => $this->faker->name(),
            'web' => $this->faker->url(),
            'ganancia' => $this->faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 1),
            'providersCategorias_id' => $this->faker->numberBetween($min = 1, $max = 30),
        ];
    }
}
