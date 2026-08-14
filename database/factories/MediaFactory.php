<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'filename' => fake()->uuid().'.jpg',
            'original_name' => fake()->word().'.jpg',
            'mime_type' => 'image/jpeg',
            'size' => fake()->numberBetween(10000, 5000000),
            'disk' => 'local',
            'path' => 'media/'.fake()->uuid().'.jpg',
            'collection' => 'gallery',
        ];
    }

    public function logo(): static
    {
        return $this->state(['collection' => 'logo']);
    }

    public function cover(): static
    {
        return $this->state(['collection' => 'cover']);
    }
}
