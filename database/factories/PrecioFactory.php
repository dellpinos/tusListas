<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Precio>
 */
class PrecioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'precio' => $this->faker->randomFloat($nbMaxDecimals = NULL, $min = 50, $max = 8000),
            'dolar' => $this->faker->numberBetween($min = 200, $max = 1000),
            'fabricante_id' => $this->faker->numberBetween($min = 1, $max = 40),
            'categoria_id' => $this->faker->numberBetween($min = 1, $max = 40),
            'producto_id' => $this->faker->numberBetween($min = 1, $max = 80)
        ];
    }
}
