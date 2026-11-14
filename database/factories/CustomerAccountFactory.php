<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerAccountFactory extends Factory
{
    public function definition(): array
    {
        return ['email' => fake()->unique()->safeEmail(), 'customer_id' => fn (array $attributes) => Customer::create(['full_name' => fake()->name(), 'email' => $attributes['email']])->id, 'password' => 'password123'];
    }
}
