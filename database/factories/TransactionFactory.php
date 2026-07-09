<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'subscription_id' => Subscription::factory(),
            'reference' => fake()->unique()->uuid(),
            'amount' => fake()->randomFloat(2, 1000, 50000),
            'currency' => 'NGN',
            'status' => 'success',
            'gateway' => 'paystack',
            'paid_at' => now(),
        ];
    }

    public function failed(): static
    {
        return $this->state(['status' => 'failed']);
    }

    public function pending(): static
    {
        return $this->state(['status' => 'pending', 'paid_at' => null]);
    }
}
