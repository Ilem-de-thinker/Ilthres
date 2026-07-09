<?php

namespace Database\Factories;

use App\Models\Theme;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ThemeFactory extends Factory
{
    protected $model = Theme::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'config' => [
                'colors' => [
                    'primary' => fake()->hexColor(),
                    'secondary' => fake()->hexColor(),
                    'background' => fake()->hexColor(),
                    'text' => fake()->hexColor(),
                    'accent' => fake()->hexColor(),
                ],
            ],
            'business_type' => null,
            'is_premium' => false,
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    public function premium(): static
    {
        return $this->state(['is_premium' => true]);
    }

    public function forBusinessType(string $type): static
    {
        return $this->state(['business_type' => $type]);
    }
}
