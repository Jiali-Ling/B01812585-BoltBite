<?php

namespace Database\Factories;

use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition(): array
    {
        return [
            'restaurant_id' => Restaurant::factory(),
            'user_id' => User::factory(),
            'name' => $this->faker->unique()->words(2, true),
            'category' => $this->faker->randomElement(['PIZZA', 'PASTA', 'DESSERT', 'SALAD']),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 5, 60),
            'stock' => $this->faker->numberBetween(10, 200),
            'status' => 'on_shelf',
        ];
    }
}

