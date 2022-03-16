<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "user_id" => User::factory(),
            "bl_title" => $this->faker->sentence(),
            "bl_slug" => $this->faker->slug(),
            "bl_excerpt" => $this->faker->paragraph(),
            "bl_body" => $this->faker->paragraph(),
            "bl_is_public" => 1
        ];
    }
}
