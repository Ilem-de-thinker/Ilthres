<?php

namespace Database\Factories;

use App\Models\AnalyticsEvent;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnalyticsEventFactory extends Factory
{
    protected $model = AnalyticsEvent::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'type' => AnalyticsEvent::TYPE_PAGE_VIEW,
            'source' => 'profile',
            'ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    public function pageView(): static
    {
        return $this->state(['type' => AnalyticsEvent::TYPE_PAGE_VIEW]);
    }

    public function whatsappClick(): static
    {
        return $this->state(['type' => AnalyticsEvent::TYPE_WHATSAPP_CLICK]);
    }

    public function phoneClick(): static
    {
        return $this->state(['type' => AnalyticsEvent::TYPE_PHONE_CLICK]);
    }

    public function productView(): static
    {
        return $this->state([
            'type' => AnalyticsEvent::TYPE_PRODUCT_VIEW,
            'metadata' => ['item_id' => fake()->randomNumber(), 'item_name' => fake()->word()],
        ]);
    }

    public function serviceView(): static
    {
        return $this->state([
            'type' => AnalyticsEvent::TYPE_SERVICE_VIEW,
            'metadata' => ['item_id' => fake()->randomNumber(), 'item_name' => fake()->word()],
        ]);
    }
}
