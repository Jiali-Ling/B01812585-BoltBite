<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
    protected $model = Restaurant::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->unique()->company,
            'description' => $this->faker->sentence(),
            'image' => null,
            'rating' => $this->faker->randomFloat(1, 3.5, 5),
        ];
    }
}

