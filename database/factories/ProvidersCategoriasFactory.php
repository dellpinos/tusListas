<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProvidersCategorias>
 */
class ProvidersCategoriasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider_id' => $this->faker->numberBetween($min = 1, $max = 40),
            'categoria_id' => $this->faker->numberBetween($min = 1, $max = 40),
        ];
    }
}
