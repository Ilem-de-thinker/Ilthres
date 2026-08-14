<?php

namespace Database\Seeders;

use App\Domains\Website\Actions\CreateWebsite;
use App\Domains\Website\DTOs\WebsiteData;
use App\Models\AnalyticsEvent;
use App\Models\Business;
use App\Models\Category;
use App\Models\MarketingLink;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    private array $users = [];

    private array $categories = [];

    public function run(): void
    {
        $this->categories = Category::pluck('id')->toArray();
        $freePlan = Plan::where('slug', 'free')->first();
        $proPlan = Plan::where('slug', 'professional')->first();

        $this->command->warn('Seeding demo data...');

        // ─── 10 Users with Nigerian names ─────────────────
        $names = [
            'Chioma Okafor', 'Emeka Nwosu', 'Aisha Bello', 'Tunde Balogun', 'Ngozi Eze',
            'Segun Adeyemi', 'Fatima Usman', 'Chidi Okonkwo', 'Yetunde Ogunlade', 'Kelechi Obi',
        ];

        foreach ($names as $i => $name) {
            $email = strtolower(str_replace(' ', '.', $name)).'@example.com';
            $this->users[] = User::firstOrCreate(['email' => $email], [
                'name' => $name,
                'password' => bcrypt('password'),
                'email_verified_at' => now()->subDays(rand(30, 90)),
                'onboarding_completed' => true,
                'onboarding_step' => 5,
                'created_at' => now()->subDays(rand(30, 90)),
            ]);
        }

        $this->command->info('✓ 10 users created');

        // ─── 15 Businesses ─────────────────────────────────
        $businessData = [
            ['name' => 'Chioma Fashion House', 'type' => Business::TYPE_PRODUCT_STORE, 'user' => 0, 'category' => 0],
            ['name' => 'Emeka Electronics', 'type' => Business::TYPE_PRODUCT_STORE, 'user' => 1, 'category' => 2],
            ['name' => 'Aisha Beauty Studio', 'type' => Business::TYPE_PRODUCT_STORE, 'user' => 2, 'category' => 3],
            ['name' => 'Tunde Fitness Hub', 'type' => Business::TYPE_SERVICE_BUSINESS, 'user' => 3, 'category' => 9],
            ['name' => 'Ngozi Home Decor', 'type' => Business::TYPE_PRODUCT_STORE, 'user' => 4, 'category' => 4],
            ['name' => 'Segun Auto Repairs', 'type' => Business::TYPE_SERVICE_BUSINESS, 'user' => 5, 'category' => 7],
            ['name' => 'Fatima Catering Services', 'type' => Business::TYPE_SERVICE_BUSINESS, 'user' => 6, 'category' => 1],
            ['name' => 'Chidi Photography', 'type' => Business::TYPE_SERVICE_BUSINESS, 'user' => 7, 'category' => 10],
            ['name' => 'Yetunde Natural Skincare', 'type' => Business::TYPE_PRODUCT_STORE, 'user' => 8, 'category' => 3],
            ['name' => 'Kelechi Bookstore', 'type' => Business::TYPE_PRODUCT_STORE, 'user' => 9, 'category' => 11],
            ['name' => 'Adeyemi Legal Consult', 'type' => Business::TYPE_SERVICE_BUSINESS, 'user' => 1, 'category' => 8],
            ['name' => 'Nwosu Grocery Mart', 'type' => Business::TYPE_PRODUCT_STORE, 'user' => 0, 'category' => 1],
            ['name' => 'Bello Medical Clinic', 'type' => Business::TYPE_SERVICE_BUSINESS, 'user' => 2, 'category' => 6],
            ['name' => 'Okafor Tailoring Studio', 'type' => Business::TYPE_SERVICE_BUSINESS, 'user' => 4, 'category' => 0],
            ['name' => 'Obi Tech Solutions', 'type' => Business::TYPE_PRODUCT_STORE, 'user' => 5, 'category' => 2],
        ];

        $businesses = [];
        foreach ($businessData as $data) {
            $user = $this->users[$data['user']];
            $catId = $this->categories[$data['category']] ?? $this->categories[0];

            $business = Business::firstOrCreate(
                ['slug' => Str::slug($data['name']).'-'.$user->id],
                [
                    'user_id' => $user->id,
                    'category_id' => $catId,
                    'name' => $data['name'],
                    'type' => $data['type'],
                    'description' => fake()->paragraph(3),
                    'about' => fake()->paragraph(5),
                    'email' => $user->email,
                    'phone' => '0'.rand(70, 81).rand(1000000, 9999999),
                    'whatsapp' => '234'.rand(70, 81).rand(1000000, 9999999),
                    'city' => collect(['Lagos', 'Abuja', 'Port Harcourt', 'Ibadan', 'Kano', 'Enugu', 'Aba', 'Benin City'])->random(),
                    'state' => collect(['Lagos', 'FCT', 'Rivers', 'Oyo', 'Kano', 'Enugu', 'Abia', 'Edo'])->random(),
                    'country' => 'Nigeria',
                    'is_active' => true,
                    'is_verified' => rand(0, 3) > 0,
                    'is_discoverable' => true,
                    'created_at' => now()->subDays(rand(20, 85)),
                ]
            );
            $businesses[] = $business;

            // Create website for the business
            $website = app(CreateWebsite::class)(new WebsiteData(
                businessId: $business->id,
                subdomain: $business->slug,
                isActive: false,
            ));

            // Seed default pages
            $homePage = $website->pages()->create([
                'title' => 'Home',
                'slug' => 'home',
                'sort_order' => 1,
                'is_active' => true,
            ]);
            $homePage->sections()->create([
                'type' => 'hero',
                'title' => 'Hero',
                'sort_order' => 1,
                'is_active' => true,
                'data' => [
                    'heading' => $business->name,
                    'subheading' => $business->description ?? '',
                    'cta_text' => 'Contact Us',
                    'cta_link' => '#contact',
                ],
            ]);
            $homePage->sections()->create([
                'type' => 'about',
                'title' => 'About',
                'sort_order' => 2,
                'is_active' => true,
                'data' => [
                    'heading' => 'About '.$business->name,
                    'body' => $business->about ?? $business->description ?? '',
                ],
            ]);
            $homePage->sections()->create([
                'type' => $business->isProductStore() ? 'products' : 'services',
                'title' => $business->isProductStore() ? 'Products' : 'Services',
                'sort_order' => 3,
                'is_active' => true,
                'data' => [
                    'heading' => $business->isProductStore() ? 'Our Products' : 'Our Services',
                    'show_pricing' => true,
                ],
            ]);
            $homePage->sections()->create([
                'type' => 'contact',
                'title' => 'Contact',
                'sort_order' => 4,
                'is_active' => true,
                'data' => [
                    'heading' => 'Get In Touch',
                    'phone' => $business->phone,
                    'email' => $business->email,
                    'whatsapp' => $business->whatsapp,
                    'show_map' => true,
                ],
            ]);

            $aboutPage = $website->pages()->create([
                'title' => 'About',
                'slug' => 'about',
                'sort_order' => 2,
                'is_active' => true,
            ]);
            $aboutPage->sections()->create([
                'type' => 'about',
                'title' => 'About Us',
                'sort_order' => 1,
                'is_active' => true,
                'data' => [
                    'heading' => 'About '.$business->name,
                    'body' => $business->about ?? $business->description ?? '',
                ],
            ]);

            $contactPage = $website->pages()->create([
                'title' => 'Contact',
                'slug' => 'contact',
                'sort_order' => 3,
                'is_active' => true,
            ]);
            $contactPage->sections()->create([
                'type' => 'contact',
                'title' => 'Contact Us',
                'sort_order' => 1,
                'is_active' => true,
                'data' => [
                    'heading' => 'Get In Touch',
                    'phone' => $business->phone,
                    'email' => $business->email,
                    'whatsapp' => $business->whatsapp,
                    'show_map' => true,
                    'address' => $business->address ?? '',
                ],
            ]);

            // Create subscription for each business
            $plan = rand(0, 2) === 0 ? $freePlan : $proPlan;
            Subscription::firstOrCreate(
                ['business_id' => $business->id],
                [
                    'plan_id' => $plan->id,
                    'status' => 'active',
                    'starts_at' => $business->created_at,
                    'ends_at' => $business->created_at->copy()->addMonth(),
                    'gateway' => 'paystack',
                    'gateway_reference' => 'PS_'.strtoupper(Str::random(20)),
                    'created_at' => $business->created_at,
                ]
            );
        }

        $this->command->info('✓ 15 businesses created with subscriptions');

        // ─── Products (~60) ────────────────────────────────
        $productNames = [
            // Fashion
            'Premium Dashiki Shirt', 'Ankara Print Dress', 'Classic Agbada', 'Handwoven Kente Scarf',
            'Leather Sandals', 'Beaded Necklace Set', 'Gele Headwrap', 'Aso Oke Blazer',
            'Traditional Corset Top', 'Cowrie Shell Earrings', 'Bubu Gown', 'Kaftan Maxi Dress',
            'Senegalise Kaftan', 'Adire Kimono Jacket', 'Fila Cap Collection',
            // Electronics
            'Bluetooth Speaker Pro', 'Wireless Earbuds', 'Solar Power Bank 20000mAh', 'LED Desk Lamp',
            'Smart Watch Series 3', 'USB-C Hub 7-in-1', 'Noise Cancelling Headphones', 'Phone Stand Holder',
            'Portable Projector Mini', 'Laptop Sleeve Bag', 'Smart Plug WiFi', 'Car Phone Mount',
            // Home
            'Scented Soy Candle Set', 'Handwoven Basket', 'Throw Pillow Cover Set', 'Ceramic Vase',
            'Bamboo Organizer Tray', 'Cotton Bed Sheet Set', 'Wall Art Canvas Print', 'Macrame Wall Hanging',
            'Rattan Shelf Decor', 'Marble Coaster Set', 'Wooden Serving Bowl', 'Linen Table Runner',
            // Skincare
            'Shea Butter Cream', 'Black Soap Original', 'Coconut Hair Oil', 'Neem Face Toner',
            'Aloe Vera Gel Moisturizer', 'Charcoal Face Mask', 'Moringa Body Lotion', 'Baobab Serum',
            // Books
            'The Art of African Storytelling', 'Nigerian History Vol II', 'Modern Afrobeats Production',
            'Financial Freedom for Africans', 'Traditional Medicine Guide', 'Yoruba Language Workbook',
        ];

        $productBusinesses = array_filter($businesses, fn ($b) => $b->isProductStore());
        $products = [];

        foreach ($productNames as $name) {
            $business = $productBusinesses[array_rand($productBusinesses)];
            $price = rand(1500, 85000);
            $products[] = Product::firstOrCreate(
                ['slug' => Str::slug($name).'-'.$business->id],
                [
                    'business_id' => $business->id,
                    'name' => $name,
                    'description' => fake()->paragraphs(rand(1, 3), true),
                    'price' => $price,
                    'compare_price' => rand(0, 1) ? $price + rand(1000, 15000) : null,
                    'currency' => 'NGN',
                    'is_active' => true,
                    'is_featured' => rand(0, 4) === 0,
                    'is_public' => true,
                    'sort_order' => 0,
                    'created_at' => now()->subDays(rand(5, 80)),
                ]
            );
        }

        $this->command->info('✓ '.count($products).' products created');

        // ─── Services (~30) ────────────────────────────────
        $serviceNames = [
            'Full Body Massage', 'Bridal Makeup Package', 'Grooming Session', 'Hair Styling & Braiding',
            'Manicure & Pedicure Combo', 'Facial Treatment', 'Deep Tissue Massage',
            'Personal Training Session', 'Yoga Class Monthly Pass', 'Diet & Nutrition Plan',
            'Car Oil Change Service', 'Engine Diagnostics', 'AC Repair Service', 'Car Wash & Detailing',
            'Brake Pad Replacement', 'Tire Rotation Service', 'Battery Check & Replacement',
            'Wedding Photography Package', 'Event Videography', 'Product Photography Session',
            'Portrait Photo Shoot', 'Drone Aerial Footage', 'Album Design Service',
            'Legal Consultation Hourly', 'Contract Review Service', 'Business Registration',
            'Tax Filing Assistance', 'Property Lease Agreement', 'Mediation Session',
            'Catering for 50 Guests', 'Event Planning Service', 'Private Chef Experience',
            'Meal Prep Weekly Plan', 'Consultation Call',
        ];

        $serviceBusinesses = array_filter($businesses, fn ($b) => $b->isServiceBusiness());

        foreach ($serviceNames as $name) {
            $business = $serviceBusinesses[array_rand($serviceBusinesses)];
            Service::firstOrCreate(
                ['slug' => Str::slug($name).'-'.$business->id],
                [
                    'business_id' => $business->id,
                    'name' => $name,
                    'description' => fake()->paragraphs(rand(1, 2), true),
                    'price' => rand(3000, 120000),
                    'currency' => 'NGN',
                    'duration_minutes' => collect([30, 45, 60, 90, 120, 180])->random(),
                    'is_active' => true,
                    'is_public' => true,
                    'sort_order' => 0,
                    'created_at' => now()->subDays(rand(5, 75)),
                ]
            );
        }

        $this->command->info('✓ '.count($serviceNames).' services created');

        // ─── Marketing Links ───────────────────────────────
        foreach ($businesses as $business) {
            if (rand(0, 1)) {
                continue;
            }
            MarketingLink::create([
                'business_id' => $business->id,
                'name' => collect(['Portfolio', 'Catalog', 'Book Now', 'Menu', 'Gallery'])->random(),
                'slug' => Str::slug($business->name).'-link',
                'url' => route('business.profile', $business),
                'description' => fake()->sentence(),
                'is_active' => true,
                'created_at' => now()->subDays(rand(10, 60)),
            ]);
        }

        $this->command->info('✓ Marketing links created');

        // ─── Promotions ────────────────────────────────────
        $promoData = [
            ['name' => 'Summer Mega Sale', 'type' => 'percentage', 'value' => 20, 'scope' => 'all', 'status' => 'active'],
            ['name' => 'Flash Friday', 'type' => 'percentage', 'value' => 30, 'scope' => 'all', 'status' => 'active'],
            ['name' => 'New Customer Offer', 'type' => 'fixed_amount', 'value' => 2000, 'scope' => 'all', 'status' => 'active'],
            ['name' => 'Clearance Blowout', 'type' => 'percentage', 'value' => 50, 'scope' => 'all', 'status' => 'scheduled'],
            ['name' => 'Free Shipping Week', 'type' => 'free_shipping', 'value' => null, 'scope' => 'all', 'status' => 'active'],
        ];

        foreach ($promoData as $i => $data) {
            $business = $businesses[$i % count($businesses)];
            Promotion::create([
                'business_id' => $business->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']).'-'.$business->id,
                'description' => fake()->sentence(),
                'promotion_type' => $data['type'],
                'scope' => $data['scope'],
                'discount_type' => in_array($data['type'], ['percentage', 'fixed_amount']) ? $data['type'] : null,
                'discount_value' => $data['value'],
                'priority' => $i,
                'status' => $data['status'],
                'show_countdown' => true,
                'show_badge' => true,
                'starts_at' => $data['status'] === 'scheduled' ? now()->addDays(3) : now()->subDays(5),
                'ends_at' => now()->addDays(10 + $i),
                'created_by' => $business->user_id,
                'created_at' => now()->subDays(10),
            ]);
        }

        $this->command->info('✓ Promotions created');

        // ─── Analytics Events (5000+) ──────────────────────
        $eventTypes = [
            AnalyticsEvent::TYPE_PAGE_VIEW,
            AnalyticsEvent::TYPE_PRODUCT_VIEW,
            AnalyticsEvent::TYPE_SERVICE_VIEW,
            AnalyticsEvent::TYPE_WHATSAPP_CLICK,
            AnalyticsEvent::TYPE_PHONE_CLICK,
        ];

        $sources = ['profile', 'search', 'social', 'direct', 'whatsapp', 'referral'];
        $eventCount = 0;
        $chunk = [];

        foreach ($businesses as $business) {
            $daysBack = min(90, max(30, now()->diffInDays($business->created_at)));
            $viewsPerDay = rand(5, 40);

            for ($day = $daysBack; $day >= 0; $day--) {
                $date = now()->subDays($day);
                $numEvents = rand(1, $viewsPerDay);

                for ($e = 0; $e < $numEvents; $e++) {
                    $type = $eventTypes[array_rand($eventTypes)];
                    $hoursAgo = rand(0, 23);
                    $minutesAgo = rand(0, 59);
                    $timestamp = $date->copy()->addHours($hoursAgo)->addMinutes($minutesAgo);

                    $chunk[] = [
                        'business_id' => $business->id,
                        'user_id' => null,
                        'type' => $type,
                        'source' => $sources[array_rand($sources)],
                        'url' => route('business.profile', $business),
                        'ip' => long2ip(rand(0, 4294967295)),
                        'user_agent' => collect([
                            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0',
                            'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148',
                            'Mozilla/5.0 (Linux; Android 14) AppleWebKit/537.36 Chrome/120.0.0.0 Mobile',
                            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 Safari/17.0',
                        ])->random(),
                        'metadata' => $type === AnalyticsEvent::TYPE_PRODUCT_VIEW ? json_encode(['product_id' => rand(1, 50), 'product_name' => 'Sample Product']) : null,
                        'created_at' => $timestamp,
                    ];

                    $eventCount++;

                    if (count($chunk) >= 200) {
                        AnalyticsEvent::insert($chunk);
                        $chunk = [];
                    }
                }
            }
        }

        if (! empty($chunk)) {
            AnalyticsEvent::insert($chunk);
        }

        $this->command->info("✓ {$eventCount} analytics events created");

        // ─── Transactions ──────────────────────────────────
        $txnCount = 0;
        foreach ($businesses as $business) {
            $sub = Subscription::where('business_id', $business->id)->first();
            if (! $sub) {
                continue;
            }

            $numTxns = rand(1, 6);
            for ($i = 0; $i < $numTxns; $i++) {
                $daysAgo = rand(1, 60);
                $amount = rand(15000, 35000);

                Transaction::create([
                    'business_id' => $business->id,
                    'subscription_id' => $sub->id,
                    'reference' => 'TXN_'.strtoupper(Str::random(16)),
                    'amount' => $amount,
                    'currency' => 'NGN',
                    'status' => 'success',
                    'description' => 'Subscription payment - '.($sub->plan->name ?? 'Plan'),
                    'gateway' => 'paystack',
                    'gateway_reference' => 'PS_'.strtoupper(Str::random(20)),
                    'paid_at' => now()->subDays($daysAgo),
                    'created_at' => now()->subDays($daysAgo),
                ]);
                $txnCount++;
            }
        }

        $this->command->info("✓ {$txnCount} transactions created");
        $this->command->info('');
        $this->command->info('── Demo Data Summary ──');
        $this->command->info('Users:        '.count($this->users));
        $this->command->info('Businesses:   '.count($businesses));
        $this->command->info('Products:     '.count($products));
        $this->command->info('Services:     '.count($serviceNames));
        $this->command->info('Events:       '.$eventCount);
        $this->command->info('Transactions: '.$txnCount);
        $this->command->info('─────────────────────');
        $this->command->info('Login with any user email (e.g. chioma.okafor@example.com)');
        $this->command->info('Password: password');
    }
}
