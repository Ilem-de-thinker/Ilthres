<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BusinessFactory extends Factory
{
    protected $model = Business::class;

    public function definition(): array
    {
        $name = fake()->company();

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->randomNumber(5),
            'type' => Business::TYPE_PRODUCT_STORE,
            'description' => fake()->paragraph(),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'whatsapp' => fake()->phoneNumber(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => 'Nigeria',
            'is_verified' => false,
            'is_active' => true,
            'is_discoverable' => false,
        ];
    }

    public function productStore(): static
    {
        return $this->state(['type' => Business::TYPE_PRODUCT_STORE]);
    }

    public function serviceBusiness(): static
    {
        return $this->state(['type' => Business::TYPE_SERVICE_BUSINESS]);
    }

    public function discoverable(): static
    {
        return $this->state(['is_discoverable' => true]);
    }

    public function verified(): static
    {
        return $this->state(['is_verified' => true]);
    }
}
