<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        Theme::insert([
            [
                'name' => 'Default',
                'slug' => 'default',
                'description' => 'Clean, minimal theme suitable for all business types.',
                'thumbnail' => null,
                'config' => json_encode([
                    'colors' => [
                        'primary' => '#4F46E5',
                        'secondary' => '#10B981',
                        'background' => '#FFFFFF',
                        'text' => '#111827',
                        'accent' => '#F59E0B',
                    ],
                    'typography' => [
                        'heading' => 'Inter',
                        'body' => 'Inter',
                    ],
                    'radius' => '0.75rem',
                    'animations' => true,
                ]),
                'is_premium' => false,
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'name' => 'Modern',
                'slug' => 'modern',
                'description' => 'Sleek, contemporary theme with gradient accents and bold visuals.',
                'thumbnail' => null,
                'config' => json_encode([
                    'colors' => [
                        'primary' => '#8B5CF6',
                        'secondary' => '#06B6D4',
                        'background' => '#F9FAFB',
                        'text' => '#1F2937',
                        'accent' => '#F472B6',
                    ],
                    'typography' => [
                        'heading' => 'Plus Jakarta Sans',
                        'body' => 'Inter',
                    ],
                    'radius' => '0.75rem',
                    'animations' => true,
                ]),
                'is_premium' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Bold',
                'slug' => 'bold',
                'description' => 'Confident, typography-driven theme with dark accents and strong visual hierarchy.',
                'thumbnail' => null,
                'config' => json_encode([
                    'colors' => [
                        'primary' => '#0F172A',
                        'secondary' => '#F59E0B',
                        'background' => '#FFFFFF',
                        'text' => '#0F172A',
                        'accent' => '#EF4444',
                    ],
                    'typography' => [
                        'heading' => 'Inter',
                        'body' => 'Inter',
                    ],
                    'radius' => '0.25rem',
                    'animations' => false,
                ]),
                'is_premium' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
        ]);
    }
}
