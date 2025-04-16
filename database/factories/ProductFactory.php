<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $cost = $this->faker->numberBetween(50, 200);
        return [
            'name' => $this->faker->words(3, true),
            'description' => '<p>' . $this->faker->paragraph . '</p>',
            'price' => $cost * 1.2, // Garante que o preço é sempre 20% maior que o custo
            'cost' => $cost,
            'stock' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->boolean,
            'slug' => $this->faker->slug,
        ];
    }
} 