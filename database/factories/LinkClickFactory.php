<?php

namespace Database\Factories;

use App\Models\LinkClick;
use App\Models\MarketingLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkClickFactory extends Factory
{
    protected $model = LinkClick::class;

    public function definition(): array
    {
        return [
            'marketing_link_id' => MarketingLink::factory(),
            'ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
