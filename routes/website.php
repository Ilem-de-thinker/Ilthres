<?php

use App\Domains\Business\Livewire\PublicProfile;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Website Routes
|--------------------------------------------------------------------------
|
| These routes are served through the ResolveBusinessDomain middleware.
| The business is resolved from the request hostname (subdomain or custom
| domain) and attached to the request. Route parameters use the business
| resolved from the domain rather than a URL segment.
|
*/

Route::middleware(['domain'])->group(function () {
    Route::get('/product/{product:slug}', PublicProfile::class)->name('website.product');
    Route::get('/service/{service:slug}', PublicProfile::class)->name('website.service');
});
