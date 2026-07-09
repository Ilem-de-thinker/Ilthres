<?php

/**
 * Plan limits configuration.
 *
 * Defines per-plan limits for features. Keyed by plan slug.
 * Used by SubscriptionMiddleware and domain logic for feature gating.
 */
return [

    'free' => [
        'max_media' => 5,
        'max_products' => 10,
        'max_services' => 10,
        'premium_themes' => false,
        'custom_domain' => false,
        'analytics' => 'basic',
        'seo_tools' => false,
        'marketing_links' => false,
        'team_members' => 1,
    ],

    'professional' => [
        'max_media' => 50,
        'max_products' => 100,
        'max_services' => 100,
        'premium_themes' => true,
        'custom_domain' => false,
        'analytics' => 'advanced',
        'seo_tools' => true,
        'marketing_links' => true,
        'team_members' => 5,
    ],

    'business' => [
        'max_media' => 9999,
        'max_products' => 9999,
        'max_services' => 9999,
        'premium_themes' => true,
        'custom_domain' => true,
        'analytics' => 'advanced',
        'seo_tools' => true,
        'marketing_links' => true,
        'team_members' => 20,
    ],

];
