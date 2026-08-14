<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Category;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoreAndUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = bcrypt('password');

        // ─── Create or retrieve a user ─────────────────────
        $user = User::firstOrCreate(
            ['email' => 'store@example.com'],
            [
                'name' => 'Store Owner',
                'password' => $password,
                'email_verified_at' => now(),
                'onboarding_completed' => true,
                'onboarding_step' => 5,
            ]
        );

        $this->command->info("✓ User: {$user->name} ({$user->email}) / password: password");

        // ─── Pick a random category ─────────────────────────
        $category = Category::inRandomOrder()->first();
        if (! $category) {
            $this->command->warn('No categories found. Run CategorySeeder first.');

            return;
        }

        // ─── Pick a plan ────────────────────────────────────
        $plan = Plan::where('slug', 'free')->first() ?? Plan::first();
        if (! $plan) {
            $this->command->warn('No plans found. Run PlanSeeder first.');

            return;
        }

        // ─── Create a product store business ─────────────────
        $storeName = 'My Demo Store';
        $business = Business::firstOrCreate(
            ['slug' => Str::slug($storeName).'-'.$user->id],
            [
                'user_id' => $user->id,
                'category_id' => $category->id,
                'name' => $storeName,
                'type' => Business::TYPE_PRODUCT_STORE,
                'description' => 'A demo product store showcasing available features.',
                'about' => 'This is a sample store created by the StoreAndUserSeeder. It demonstrates how products and services can be seeded for testing and development.',
                'email' => $user->email,
                'phone' => '08012345678',
                'whatsapp' => '2348012345678',
                'city' => 'Lagos',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'is_active' => true,
                'is_verified' => true,
                'is_discoverable' => true,
            ]
        );

        $this->command->info("✓ Store: {$business->name} ({$business->type})");

        // ─── Create a subscription for the business ──────────
        Subscription::firstOrCreate(
            ['business_id' => $business->id],
            [
                'plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'gateway' => 'paystack',
                'gateway_reference' => 'PS_'.strtoupper(Str::random(20)),
            ]
        );

        $this->command->info('✓ Subscription created (free plan)');

        // ─── Seed products ──────────────────────────────────
        $productNames = [
            'Premium Dashiki Shirt',
            'Ankara Print Dress',
            'Leather Sandals',
            'Beaded Necklace Set',
            'Handwoven Basket',
            'Shea Butter Cream',
            'Coconut Hair Oil',
            'LED Desk Lamp',
            'Wireless Earbuds',
            'Smart Phone Stand',
        ];

        foreach ($productNames as $name) {
            Product::firstOrCreate(
                ['slug' => Str::slug($name).'-'.$business->id],
                [
                    'business_id' => $business->id,
                    'name' => $name,
                    'description' => fake()->paragraphs(rand(1, 2), true),
                    'price' => rand(2000, 50000),
                    'compare_price' => rand(0, 1) ? null : rand(3000, 60000),
                    'currency' => 'NGN',
                    'is_active' => true,
                    'is_public' => true,
                    'is_featured' => rand(0, 3) === 0,
                    'sort_order' => 0,
                ]
            );
        }

        $this->command->info('✓ '.count($productNames).' products created');

        // ─── Seed services ──────────────────────────────────
        $serviceNames = [
            'Full Body Massage',
            'Bridal Makeup Package',
            'Hair Styling & Braiding',
            'Manicure & Pedicure Combo',
            'Facial Treatment',
            'Personal Training Session',
            'Product Photography Session',
            'Event Planning Service',
        ];

        foreach ($serviceNames as $name) {
            Service::firstOrCreate(
                ['slug' => Str::slug($name).'-'.$business->id],
                [
                    'business_id' => $business->id,
                    'name' => $name,
                    'description' => fake()->paragraphs(rand(1, 2), true),
                    'price' => rand(5000, 100000),
                    'currency' => 'NGN',
                    'duration_minutes' => collect([30, 45, 60, 90, 120])->random(),
                    'is_active' => true,
                    'is_public' => true,
                    'sort_order' => 0,
                ]
            );
        }

        $this->command->info('✓ '.count($serviceNames).' services created');

        // ─── Summary ────────────────────────────────────────
        $this->command->info('');
        $this->command->info('── Store & User Seeder Complete ──');
        $this->command->info("Login: {$user->email}");
        $this->command->info('Password: password');
        $this->command->info('─────────────────────────────────');
    }
}
