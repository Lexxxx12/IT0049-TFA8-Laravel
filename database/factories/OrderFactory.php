<?php

namespace Database\Factories;

use App\Models\CustomerAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => fn () => CustomerAccount::factory()->create()->customer_id,
            'submission_token' => fake()->uuid(),
            'status' => 'pending',
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'total_price' => '0.00',
        ];
    }
}
