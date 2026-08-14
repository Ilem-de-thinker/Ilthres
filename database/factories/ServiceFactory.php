<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'business_id' => Business::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->randomNumber(5),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 500, 100000),
            'currency' => 'NGN',
            'duration_minutes' => fake()->numberBetween(15, 240),
            'is_active' => true,
            'is_public' => false,
            'sort_order' => 0,
        ];
    }

    public function public(): static
    {
        return $this->state(['is_public' => true]);
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
