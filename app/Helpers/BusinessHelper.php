<?php

use App\Models\Business;
use App\Models\Website;

if (! function_exists('current_business')) {
    /**
     * Get the business resolved by the ResolveBusinessDomain middleware.
     */
    function current_business(): ?Business
    {
        return request()->attributes->get('current_business');
    }
}

if (! function_exists('current_website')) {
    /**
     * Get the website resolved by the ResolveBusinessDomain middleware.
     */
    function current_website(): ?Website
    {
        return request()->attributes->get('current_website');
    }
}
