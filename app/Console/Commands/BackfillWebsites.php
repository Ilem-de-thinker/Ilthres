<?php

namespace App\Console\Commands;

use App\Domains\Website\Actions\CreateWebsite;
use App\Domains\Website\DTOs\WebsiteData;
use App\Models\Business;
use Illuminate\Console\Command;

class BackfillWebsites extends Command
{
    protected $signature = 'website:backfill';

    protected $description = 'Create websites for businesses that do not have one';

    public function handle(): int
    {
        $businessIds = Business::where('deleted_at', null)->pluck('id');
        $websiteBusinessIds = \DB::table('websites')->pluck('business_id');
        $businesses = Business::whereIn('id', $businessIds->diff($websiteBusinessIds))->get();

        if ($businesses->isEmpty()) {
            $this->info('All businesses already have websites.');

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($businesses->count());
        $bar->start();

        foreach ($businesses as $business) {
            $website = app(CreateWebsite::class)(new WebsiteData(
                businessId: $business->id,
                subdomain: $business->slug,
                isActive: false,
            ));

            // Seed home page
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

            // Seed about page
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

            // Seed contact page
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

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Created websites for {$businesses->count()} businesses.");

        return self::SUCCESS;
    }
}
