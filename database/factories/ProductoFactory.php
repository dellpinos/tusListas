<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->colorName(),
            'codigo' => $this->faker->regexify('[A-Za-z0-9]{4}'),
            'fabricante_id' => $this->faker->numberBetween($min = 1, $max = 40),
            'categoria_id' => $this->faker->numberBetween($min = 1, $max = 40),
            'precio_id' => $this->faker->numberBetween($min = 1, $max = 300),
            'provider_id' => $this->faker->numberBetween($min = 1, $max = 30),
        ];
    }
}
