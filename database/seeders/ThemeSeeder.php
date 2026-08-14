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
                'description' => 'Serene Ivory — airy minimalism with warm ivory surfaces, Playfair Display serif headlines, and delicate champagne gold accents.',
                'thumbnail' => null,
                'config' => json_encode([
                    'colors' => [
                        'primary' => '#1c1c18',
                        'secondary' => '#5e5f5c',
                        'background' => '#fcf9f2',
                        'text' => '#1c1c18',
                        'accent' => '#6c5e06',
                    ],
                    'typography' => [
                        'heading' => 'Playfair Display',
                        'body' => 'Plus Jakarta Sans',
                    ],
                    'radius' => '0.25rem',
                    'animations' => true,
                ]),
                'is_premium' => false,
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'name' => 'Modern',
                'slug' => 'modern',
                'description' => 'Lumina SaaS — technological elegance with glassmorphism, indigo gradients, and Plus Jakarta Sans.',
                'thumbnail' => null,
                'config' => json_encode([
                    'colors' => [
                        'primary' => '#4648d4',
                        'secondary' => '#006591',
                        'background' => '#f0f2ff',
                        'text' => '#1a1a2e',
                        'accent' => '#b90538',
                    ],
                    'typography' => [
                        'heading' => 'Plus Jakarta Sans',
                        'body' => 'Plus Jakarta Sans',
                    ],
                    'radius' => '1rem',
                    'animations' => true,
                ]),
                'is_premium' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Bold',
                'slug' => 'bold',
                'description' => 'High-Impact Editorial — raw, authoritative aesthetics with Anton typography, heavy borders, and neon green accents.',
                'thumbnail' => null,
                'config' => json_encode([
                    'colors' => [
                        'primary' => '#ffffff',
                        'secondary' => '#72ff70',
                        'background' => '#131313',
                        'text' => '#e2e2e2',
                        'accent' => '#72ff70',
                    ],
                    'typography' => [
                        'heading' => 'Anton',
                        'body' => 'Archivo Narrow',
                        'label' => 'Space Mono',
                    ],
                    'radius' => '0rem',
                    'animations' => false,
                ]),
                'is_premium' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Heritage',
                'slug' => 'heritage',
                'description' => 'Heritage Luxury — authoritative sophistication with Playfair Display serif, champagne gold accents, and fine hairline borders.',
                'thumbnail' => null,
                'config' => json_encode([
                    'colors' => [
                        'primary' => '#000000',
                        'secondary' => '#735c00',
                        'background' => '#fbf9f9',
                        'text' => '#000000',
                        'accent' => '#fed65b',
                    ],
                    'typography' => [
                        'heading' => 'Playfair Display',
                        'body' => 'Hanken Grotesk',
                    ],
                    'radius' => '0.125rem',
                    'animations' => true,
                ]),
                'is_premium' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ]);
    }
}
