<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;
class WhatupFactory extends Factory
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
            "wp_title" => $this->faker->sentence(),
            "wp_body" => $this->faker->paragraph(),
            "is_public" => 1,
        ];
    }
}
