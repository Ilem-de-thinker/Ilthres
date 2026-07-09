<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\MarketingLink;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MarketingLinkFactory extends Factory
{
    protected $model = MarketingLink::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'business_id' => Business::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'url' => fake()->url(),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
