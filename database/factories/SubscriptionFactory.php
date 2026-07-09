<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'plan_id' => Plan::factory(),
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
            'gateway' => 'paystack',
            'gateway_reference' => fake()->unique()->uuid(),
        ];
    }

    public function expired(): static
    {
        return $this->state([
            'status' => 'expired',
            'ends_at' => now()->subDay(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }

    public function trial(): static
    {
        return $this->state(['status' => 'trial']);
    }
}
