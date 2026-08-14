<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Perfect for getting started',
                'price' => 0,
                'currency' => 'NGN',
                'interval' => 'monthly',
                'features' => json_encode([
                    'One business profile',
                    'Basic theme',
                    'Limited analytics',
                    'Basic SEO',
                    '5 media uploads',
                    'Community support',
                ]),
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'For growing businesses',
                'price' => 15000,
                'currency' => 'NGN',
                'interval' => 'monthly',
                'features' => json_encode([
                    'One business profile',
                    'Premium themes',
                    'Advanced analytics',
                    'Priority search placement',
                    '50 media uploads',
                    'SEO tools',
                    'Marketing links',
                    'Custom branding',
                    'Priority support',
                ]),
                'sort_order' => 2,
                'is_active' => true,
            ],
            // [
            //     'name' => 'Business',
            //     'slug' => 'business',
            //     'description' => 'For established businesses',
            //     'price' => 35000,
            //     'currency' => 'NGN',
            //     'interval' => 'monthly',
            //     'features' => json_encode([
            //         'Everything in Professional',
            //         'Multiple staff accounts',
            //         'Advanced reports',
            //         'Custom domain',
            //         'Priority support',
            //         'Future integrations',
            //     ]),
            //     'sort_order' => 3,
            //     'is_active' => true,
            // ],
            [
                'name' => 'Linked Business',
                'slug' => 'linked_business',
                'description' => 'For businesses with their own website',
                'price' => 7500,
                'currency' => 'NGN',
                'interval' => 'monthly',
                'features' => json_encode([
                    'Business profile on Ilthres',
                    'Link your external website',
                    'Basic analytics',
                    '5 media uploads',
                    'Locations management',
                    'KYC verification',
                    'Community support',
                ]),
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::firstOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
