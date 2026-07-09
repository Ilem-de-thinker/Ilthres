<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'slug' => Str::slug(fake()->unique()->word()),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 0, 50000),
            'currency' => 'NGN',
            'interval' => 'monthly',
            'features' => ['Feature 1', 'Feature 2'],
            'sort_order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }

    public function free(): static
    {
        return $this->state(['price' => 0, 'name' => 'Free']);
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
