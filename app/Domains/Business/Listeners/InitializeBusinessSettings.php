<?php

namespace App\Domains\Business\Listeners;

use App\Domains\Business\Events\BusinessCreated;
use App\Domains\Website\Actions\CreateWebsite;
use App\Domains\Website\DTOs\WebsiteData;
use App\Models\BusinessLocation;
use App\Models\Website;

/**
 * Sets up default configurations for a newly created business.
 *
 * Creates the business website with a subdomain derived from the business slug,
 * assigns the default theme, and seeds the standard pages and sections.
 */
class InitializeBusinessSettings
{
    public function handle(BusinessCreated $event): void
    {
        $business = $event->business;

        // Skip website creation for businesses with an external URL.
        if ($business->website_url) {
            $this->createPrimaryLocation($business);

            return;
        }

        $website = app(CreateWebsite::class)(new WebsiteData(
            businessId: $business->id,
            subdomain: $business->slug,
            isActive: false,
        ));

        $this->createPrimaryLocation($business);

        $homePage = $this->seedHomePage($website, $business);
        $this->seedAboutPage($website, $business);
        $this->seedContactPage($website, $business);
    }

    private function createPrimaryLocation($business): void
    {
        if (! $business->address && ! $business->city) {
            return;
        }

        BusinessLocation::create([
            'business_id' => $business->id,
            'country' => $business->country ?? 'Nigeria',
            'state' => $business->state ?? '',
            'city' => $business->city ?? '',
            'address' => $business->address ?? '',
            'latitude' => $business->latitude ?? 0,
            'longitude' => $business->longitude ?? 0,
            'phone' => $business->phone,
            'email' => $business->email,
            'is_primary' => true,
            'is_headquarters' => true,
        ]);
    }

    private function seedHomePage(Website $website, $business): void
    {
        $page = $website->pages()->create([
            'title' => 'Home',
            'slug' => 'home',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $page->sections()->create([
            'type' => 'hero',
            'title' => 'Hero',
            'sort_order' => 1,
            'is_active' => true,
            'data' => [
                'heading' => $business->name,
                'subheading' => $business->description ?? '',
                'background_image' => $business->cover,
                'cta_text' => 'Contact Us',
                'cta_link' => '#contact',
            ],
        ]);

        $page->sections()->create([
            'type' => 'about',
            'title' => 'About',
            'sort_order' => 2,
            'is_active' => true,
            'data' => [
                'heading' => 'About '.$business->name,
                'body' => $business->about ?? $business->description ?? '',
                'image' => $business->logo,
            ],
        ]);

        $page->sections()->create([
            'type' => $business->isProductStore() ? 'products' : 'services',
            'title' => $business->isProductStore() ? 'Products' : 'Services',
            'sort_order' => 3,
            'is_active' => true,
            'data' => [
                'heading' => $business->isProductStore() ? 'Our Products' : 'Our Services',
                'show_pricing' => true,
            ],
        ]);

        $page->sections()->create([
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
    }

    private function seedAboutPage(Website $website, $business): void
    {
        $page = $website->pages()->create([
            'title' => 'About',
            'slug' => 'about',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $page->sections()->create([
            'type' => 'about',
            'title' => 'About Us',
            'sort_order' => 1,
            'is_active' => true,
            'data' => [
                'heading' => 'About '.$business->name,
                'body' => $business->about ?? $business->description ?? '',
                'image' => $business->logo,
            ],
        ]);
    }

    private function seedContactPage(Website $website, $business): void
    {
        $page = $website->pages()->create([
            'title' => 'Contact',
            'slug' => 'contact',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $page->sections()->create([
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
    }
}
