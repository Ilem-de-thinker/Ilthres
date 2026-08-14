<?php

use App\Domains\Administration\Livewire\AdminDashboard;
use App\Domains\Administration\Livewire\AiSettingsManager;
use App\Domains\Administration\Livewire\BusinessManager;
use App\Domains\Administration\Livewire\CategoryManager;
use App\Domains\Administration\Livewire\OrderManager;
use App\Domains\Administration\Livewire\PaymentManager;
use App\Domains\Administration\Livewire\PlatformAnalytics;
use App\Domains\Administration\Livewire\PlatformSettingsManager;
use App\Domains\Administration\Livewire\SubscriptionManager;
use App\Domains\Administration\Livewire\ThemeManager;
use App\Domains\Administration\Livewire\UserManager;
use App\Domains\Administration\Livewire\VerificationManager;
use App\Domains\Analytics\Actions\RecordEvent;
use App\Domains\Booking\Livewire\BookingManager;
use App\Domains\Business\Actions\CreateBusiness;
use App\Domains\Business\DTOs\BusinessData;
use App\Domains\Business\Livewire\BusinessDetail;
use App\Domains\Business\Livewire\PublicProfile;
use App\Domains\Marketing\Actions\TrackClick;
use App\Domains\Order\Livewire\Cart;
use App\Domains\Order\Livewire\Checkout;
use App\Domains\Order\Livewire\Inquiry;
use App\Domains\Payment\Actions\RecordPayment;
use App\Domains\Payment\DTOs\TransactionData;
use App\Domains\Review\Livewire\ReviewManager;
use App\Http\Controllers\PublicPageController;
use App\Http\Middleware\OnboardingMiddleware;
use App\Models\Business;
use App\Models\MarketingLink;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\Theme;
use HopekellDev\Paystack\Paystack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

// ─── Paystack webhook (no auth required) ───────────────
Route::post('/webhooks/paystack', function (Request $request) {
    $payload = $request->all();
    $event = $payload['event'] ?? '';

    Log::channel('paystack')->info('Webhook received', ['event' => $event]);

    if ($event === 'charge.success') {
        $reference = $payload['data']['reference'] ?? null;
        if ($reference) {
            $subscription = Subscription::where('gateway_reference', $reference)->first();
            if ($subscription && $subscription->status !== 'active') {
                $subscription->update([
                    'status' => 'active',
                    'gateway_data' => $payload,
                ]);

                app(RecordPayment::class)(new TransactionData(
                    businessId: $subscription->business_id,
                    reference: $reference,
                    amount: (float) ($payload['data']['amount'] / 100),
                    status: 'success',
                    subscriptionId: $subscription->id,
                    currency: $payload['data']['currency'] ?? 'NGN',
                    description: 'Subscription payment',
                    gateway: 'paystack',
                    gatewayReference: $reference,
                    gatewayData: $payload,
                    paidAt: $payload['data']['paid_at'] ?? now()->toDateTimeString(),
                ));

                Log::channel('paystack')->info('Subscription activated via webhook', [
                    'subscription_id' => $subscription->id,
                    'reference' => $reference,
                ]);
            }
        }
    } else {
        Log::channel('paystack')->warning('Unhandled webhook event', [
            'event' => $event,
            'payload' => $payload,
        ]);
    }

    return response()->json(['status' => 'ok']);
})->name('webhooks.paystack');

// ─── Public pages ──────────────────────────────────────
Route::view('/', 'welcome')->name('home');
Route::view('/explore', 'explore')->name('explore');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/privacy', 'pages.privacy')->name('privacy');

// ─── Guest-only pages (login, register, password) ──────
Route::middleware('guest')->group(function () {
    Route::view('/login', 'pages.login')->name('login');
    Route::view('/register', 'pages.register')->name('register');
    Route::view('/forgot-password', 'pages.forgot-password')->name('password.request');
    Route::view('/reset-password/{token}', 'pages.reset-password')->name('password.reset');
});

// ─── Authenticated pages ───────────────────────────────
Route::middleware('auth')->group(function () {

    // Onboarding wizard — excluded from OnboardingMiddleware to avoid redirect loops.
    Route::view('/onboarding', 'pages.onboarding')->name('onboarding');

    // Post-registration choice page — excluded from OnboardingMiddleware.
    Route::view('/get-started', 'pages.get-started')->name('get-started');
    Route::view('/link-business', 'pages.link-business')->name('link-business');

    // Paystack redirect — reads authorization URL from session and redirects to Paystack.
    Route::get('/onboarding/paystack/redirect', function () {
        $url = session('paystack_authorization_url');
        if (! $url) {
            return redirect()->route('onboarding')->with('error', 'Payment session expired. Please try again.');
        }

        return redirect()->away($url);
    })->name('onboarding.paystack.redirect');

    // Paystack redirect for subscription upgrades.
    Route::get('/subscription/paystack/redirect', function () {
        $url = session('paystack_upgrade_authorization_url');
        if (! $url) {
            return redirect()->route('dashboard.subscription')->with('error', 'Payment session expired. Please try again.');
        }

        return redirect()->away($url);
    })->name('subscription.paystack.redirect');

    // Paystack callback — verifies payment, creates business and subscription.
    Route::get('/onboarding/paystack/callback', function (Request $request) {
        $reference = $request->query('reference');
        $sessionRef = session('paystack_reference');
        $onboardingData = session('paystack_onboarding_data');

        if (! $reference) {
            return redirect()->route('onboarding')->with('error', 'Payment was cancelled.');
        }

        if (! $sessionRef || $reference !== $sessionRef) {
            Log::warning('Paystack callback reference mismatch', [
                'expected' => $sessionRef,
                'received' => $reference,
            ]);

            return redirect()->route('onboarding')->with('error', 'Payment verification failed. Invalid reference.');
        }

        if (! $onboardingData) {
            Log::warning('Paystack callback missing onboarding data', ['reference' => $reference]);

            return redirect()->route('onboarding')->with('error', 'Session expired. Please start again.');
        }

        try {
            $response = Paystack::transactions()->verify($reference);

            if (! ($response['status'] ?? false)) {
                Log::error('Paystack verify API returned false', ['reference' => $reference, 'response' => $response]);

                return redirect()->route('onboarding')->with('error', 'Payment verification failed with gateway.');
            }

            $paystackStatus = $response['data']['status'] ?? '';
            if ($paystackStatus !== 'success') {
                Log::warning('Paystack transaction not successful', [
                    'reference' => $reference,
                    'status' => $paystackStatus,
                ]);

                return redirect()->route('onboarding')->with('error', 'Payment was not completed.');
            }

            $planId = $response['data']['metadata']['plan_id'] ?? $onboardingData['plan_id'] ?? null;
            if (! $planId) {
                Log::error('Paystack callback missing plan_id in metadata', ['reference' => $reference]);

                return redirect()->route('onboarding')->with('error', 'Invalid payment metadata.');
            }

            $plan = Plan::find($planId);
            if (! $plan) {
                Log::error('Paystack callback plan not found', ['plan_id' => $planId]);

                return redirect()->route('onboarding')->with('error', 'Plan not found.');
            }

            $user = auth()->user();

            // Clean up orphaned businesses from previous failed attempts so we
            // can re-use the original slug when creating the new business.
            $candidateSlug = Str::slug($onboardingData['business_name']);
            Business::where('user_id', $user->id)
                ->where('slug', $candidateSlug)
                ->whereDoesntHave('subscription', fn ($q) => $q->where('status', 'active'))
                ->get()
                ->each(fn ($orphan) => $orphan->delete());

            // Create business after payment verification.
            $logoPath = null;
            $business = app(CreateBusiness::class)(new BusinessData(
                userId: $user->id,
                type: $onboardingData['business_type'],
                name: $onboardingData['business_name'],
                slug: Str::slug($onboardingData['business_name']),
                description: $onboardingData['business_description'] ?: null,
                email: $user->email,
                phone: null,
                whatsapp: $onboardingData['whatsapp'] ?: null,
                address: null,
                city: null,
                state: null,
                country: 'Nigeria',
                categoryId: $onboardingData['category_id'] ?: null,
                logo: $logoPath,
                cover: null,
            ));

            // Attach selected theme.
            if ($themeId = $onboardingData['theme_id'] ?? null) {
                $theme = Theme::find($themeId);
                if ($theme) {
                    $business->themes()->attach($themeId, [
                        'customization' => json_encode([
                            'colors' => [
                                'primary' => $onboardingData['theme_primary'] ?? '#4F46E5',
                                'secondary' => $onboardingData['theme_secondary'] ?? '#10B981',
                                'background' => $onboardingData['theme_background'] ?? '#FFFFFF',
                                'text' => $onboardingData['theme_text'] ?? '#111827',
                                'accent' => $onboardingData['theme_accent'] ?? '#F59E0B',
                            ],
                        ]),
                        'is_active' => true,
                    ]);
                }
            }

            // Create active subscription.
            $subscription = Subscription::create([
                'business_id' => $business->id,
                'plan_id' => $planId,
                'price_paid' => (float) ($response['data']['amount'] / 100),
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'gateway' => 'paystack',
                'gateway_reference' => $reference,
                'gateway_data' => $response,
            ]);

            app(RecordPayment::class)(new TransactionData(
                businessId: $business->id,
                reference: $reference,
                amount: (float) ($response['data']['amount'] / 100),
                status: 'success',
                subscriptionId: $subscription->id,
                currency: $response['data']['currency'] ?? 'NGN',
                description: 'Initial payment for '.($plan->name ?? 'Plan'),
                gateway: 'paystack',
                gatewayReference: $reference,
                gatewayData: $response,
                paidAt: $response['data']['paid_at'] ?? now()->toDateTimeString(),
            ));

            // Mark onboarding complete.
            $user->forceFill([
                'onboarding_step' => 5,
                'onboarding_completed' => true,
            ])->save();

            session(['current_business_id' => $business->id]);
            session()->forget([
                'paystack_authorization_url',
                'paystack_reference',
                'paystack_onboarding_data',
            ]);

            Log::info('Business created after successful Paystack payment', [
                'business_id' => $business->id,
                'user_id' => $user->id,
                'plan_id' => $planId,
                'reference' => $reference,
            ]);

            return redirect()->route('dashboard');
        } catch (Throwable $e) {
            Log::error('Paystack callback failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('onboarding')->with('error', 'Payment verification failed. Please contact support with your reference: '.$reference);
        }
    })->name('onboarding.paystack.callback');

    // Paystack callback — handles plan upgrades/downgrades for existing subscriptions.
    Route::get('/onboarding/paystack/upgrade-callback', function (Request $request) {
        $reference = $request->query('reference');
        $sessionRef = session('paystack_upgrade_reference');
        $upgradeData = session('paystack_upgrade_data');

        if (! $reference || ! $sessionRef || $reference !== $sessionRef || ! $upgradeData) {
            return redirect()->route('dashboard.subscription')->with('error', 'Payment verification failed.');
        }

        try {
            $response = Paystack::transactions()->verify($reference);

            if (! ($response['status'] ?? false) || ($response['data']['status'] ?? '') !== 'success') {
                return redirect()->route('dashboard.subscription')->with('error', 'Payment was not completed.');
            }

            $planId = $response['data']['metadata']['plan_id'] ?? $upgradeData['plan_id'] ?? null;
            $subscriptionId = $response['data']['metadata']['subscription_id'] ?? $upgradeData['subscription_id'] ?? null;

            if (! $planId || ! $subscriptionId) {
                return redirect()->route('dashboard.subscription')->with('error', 'Invalid payment metadata.');
            }

            $subscription = Subscription::find($subscriptionId);
            if (! $subscription) {
                return redirect()->route('dashboard.subscription')->with('error', 'Subscription not found.');
            }

            $subscription->update([
                'plan_id' => $planId,
                'price_paid' => (float) ($response['data']['amount'] / 100),
                'ends_at' => now()->addMonth(),
                'gateway_reference' => $reference,
                'gateway_data' => $response,
            ]);

            app(RecordPayment::class)(new TransactionData(
                businessId: $subscription->business_id,
                reference: $reference,
                amount: (float) ($response['data']['amount'] / 100),
                status: 'success',
                subscriptionId: $subscription->id,
                currency: $response['data']['currency'] ?? 'NGN',
                description: 'Plan upgrade',
                gateway: 'paystack',
                gatewayReference: $reference,
                gatewayData: $response,
                paidAt: $response['data']['paid_at'] ?? now()->toDateTimeString(),
            ));

            session()->forget([
                'paystack_upgrade_authorization_url',
                'paystack_upgrade_reference',
                'paystack_upgrade_data',
            ]);

            Log::info('Subscription upgraded after Paystack payment', [
                'subscription_id' => $subscription->id,
                'plan_id' => $planId,
                'reference' => $reference,
            ]);

            return redirect()->route('dashboard.subscription')->with('success', 'Plan upgraded successfully.');
        } catch (Throwable $e) {
            Log::error('Paystack upgrade callback failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('dashboard.subscription')->with('error', 'Payment verification failed. Please contact support with your reference: '.$reference);
        }
    })->name('subscription.paystack.callback');

    // ─── Link Business Paystack routes ──────────────────────
    Route::get('/link-business/paystack/redirect', function () {
        $url = session('link_business_authorization_url');
        if (! $url) {
            return redirect()->route('link-business')->with('error', 'Payment session expired. Please try again.');
        }

        return redirect()->away($url);
    })->name('link-business.paystack.redirect');

    Route::get('/link-business/paystack/callback', function (Request $request) {
        $reference = $request->query('reference');
        $sessionRef = session('link_business_reference');
        $businessData = session('link_business_data');

        if (! $reference) {
            return redirect()->route('link-business')->with('error', 'Payment was cancelled.');
        }

        if (! $sessionRef || $reference !== $sessionRef) {
            Log::warning('Link business Paystack callback reference mismatch', [
                'expected' => $sessionRef,
                'received' => $reference,
            ]);

            return redirect()->route('link-business')->with('error', 'Payment verification failed. Invalid reference.');
        }

        if (! $businessData) {
            Log::warning('Link business Paystack callback missing business data', ['reference' => $reference]);

            return redirect()->route('link-business')->with('error', 'Session expired. Please start again.');
        }

        try {
            $response = Paystack::transactions()->verify($reference);

            if (! ($response['status'] ?? false)) {
                Log::error('Paystack verify API returned false for link business', ['reference' => $reference, 'response' => $response]);

                return redirect()->route('link-business')->with('error', 'Payment verification failed with gateway.');
            }

            $paystackStatus = $response['data']['status'] ?? '';
            if ($paystackStatus !== 'success') {
                Log::warning('Link business Paystack transaction not successful', [
                    'reference' => $reference,
                    'status' => $paystackStatus,
                ]);

                return redirect()->route('link-business')->with('error', 'Payment was not completed.');
            }

            $planId = $response['data']['metadata']['plan_id'] ?? null;
            if (! $planId) {
                Log::error('Link business Paystack callback missing plan_id', ['reference' => $reference]);

                return redirect()->route('link-business')->with('error', 'Invalid payment metadata.');
            }

            $plan = Plan::find($planId);
            if (! $plan) {
                Log::error('Link business Paystack callback plan not found', ['plan_id' => $planId]);

                return redirect()->route('link-business')->with('error', 'Plan not found.');
            }

            $user = auth()->user();

            // Create the business.
            $business = app(CreateBusiness::class)(new BusinessData(
                userId: $businessData['user_id'],
                type: $businessData['type'],
                name: $businessData['name'],
                slug: $businessData['slug'],
                description: null,
                email: $businessData['email'],
                phone: null,
                whatsapp: null,
                address: null,
                city: null,
                state: null,
                country: 'Nigeria',
                categoryId: $businessData['category_id'],
                logo: null,
                cover: null,
                websiteUrl: $businessData['website_url'],
            ));

            // Create subscription.
            $subscription = Subscription::create([
                'business_id' => $business->id,
                'plan_id' => $planId,
                'price_paid' => (float) ($response['data']['amount'] / 100),
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'gateway' => 'paystack',
                'gateway_reference' => $reference,
                'gateway_data' => $response,
            ]);

            app(RecordPayment::class)(new TransactionData(
                businessId: $business->id,
                reference: $reference,
                amount: (float) ($response['data']['amount'] / 100),
                status: 'success',
                subscriptionId: $subscription->id,
                currency: $response['data']['currency'] ?? 'NGN',
                description: 'Linked Business plan subscription',
                gateway: 'paystack',
                gatewayReference: $reference,
                gatewayData: $response,
                paidAt: $response['data']['paid_at'] ?? now()->toDateTimeString(),
            ));

            // Mark onboarding complete.
            $user->forceFill([
                'onboarding_step' => 5,
                'onboarding_completed' => true,
            ])->save();

            session(['current_business_id' => $business->id]);
            session()->forget([
                'link_business_authorization_url',
                'link_business_reference',
                'link_business_data',
            ]);

            Log::info('Linked business created after successful Paystack payment', [
                'business_id' => $business->id,
                'user_id' => $user->id,
                'plan_id' => $planId,
                'reference' => $reference,
            ]);

            return redirect()->route('dashboard');
        } catch (Throwable $e) {
            Log::error('Link business Paystack callback failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('link-business')->with('error', 'Payment verification failed. Please contact support with your reference: '.$reference);
        }
    })->name('link-business.paystack.callback');

    // Logout — excluded from OnboardingMiddleware (must always be accessible).
    Route::post('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    })->name('logout');

    // Everything below requires completed onboarding.
    Route::middleware(OnboardingMiddleware::class)->group(function () {

        // Business switcher — sets current_business_id in session.
        Route::get('/dashboard/switch/{business}', function (Business $business) {
            if ($business->user_id !== auth()->id()) {
                abort(403);
            }
            session(['current_business_id' => $business->id]);

            return redirect()->route('dashboard');
        })->name('dashboard.switch');

        // Dashboard — scoped to the current business type.
        Route::view('/dashboard', 'pages.dashboard')->name('dashboard');

        // Analytics.
        Route::view('/dashboard/analytics', 'pages.analytics')->name('dashboard.analytics');

        // Bookings.
        Route::get('/dashboard/bookings', BookingManager::class)->name('dashboard.bookings');
        Route::get('/dashboard/services/{service}/availability', function (Service $service) {
            if ($service->business->user_id !== auth()->id()) {
                abort(403);
            }

            return view('livewire.booking.availability-manager', ['serviceId' => $service->id]);
        })->name('dashboard.availability');

        // Reviews.
        Route::get('/dashboard/reviews', ReviewManager::class)->name('dashboard.reviews');

        // Subscription.
        Route::view('/dashboard/subscription', 'pages.subscription')->name('dashboard.subscription');

        // Pricing.
        Route::view('/dashboard/pricing', 'pages.pricing')->name('dashboard.pricing');

        // Billing.
        Route::view('/dashboard/billing', 'pages.billing')->name('dashboard.billing');

        // Marketing.
        Route::view('/dashboard/marketing', 'pages.marketing')->name('dashboard.marketing');
        Route::view('/dashboard/marketing/create', 'pages.marketing-create')->name('dashboard.marketing.create');

        // Hero Banners.
        Route::view('/dashboard/hero-banners', 'pages.hero-banners')->name('dashboard.hero-banners');

        // Promotions.
        Route::view('/dashboard/promotions', 'pages.promotions')->name('dashboard.promotions');
        Route::view('/dashboard/promotions/create', 'pages.promotion-create')->name('dashboard.promotions.create');
        Route::get('/dashboard/promotions/{promotion}/edit', function (Promotion $promotion) {
            if ($promotion->business->user_id !== auth()->id()) {
                abort(403);
            }

            return view('pages.promotion-edit', ['promotion' => $promotion]);
        })->name('dashboard.promotions.edit');

        // Business management.
        Route::view('/dashboard/business', 'pages.business-settings')->name('dashboard.business');
        Route::view('/business/create', 'pages.create-business')->name('business.create');

        // Locations.
        Route::view('/dashboard/locations', 'pages.locations')->name('dashboard.locations');

        // KYC Verification.
        Route::view('/dashboard/kyc', 'pages.kyc')->name('dashboard.kyc');

        // Website pages & sections.
        // Route::view('/dashboard/website', 'pages.website')->name('dashboard.website');
        // Route::get('/dashboard/website/pages/{pageId}/sections', function ($pageId) {
        //     return view('pages.website-sections', ['pageId' => $pageId]);
        // })->name('dashboard.website.sections');

        // Media library — with subscription plan enforcement.
        Route::middleware('subscription:max_media')->group(function () {
            Route::view('/dashboard/media', 'pages.media')->name('dashboard.media');
        });

        // Products — for product-store businesses.
        Route::middleware('subscription:max_products')->group(function () {
            Route::view('/dashboard/products', 'pages.products')->name('dashboard.products');
            Route::view('/dashboard/products/create', 'pages.product-create')->name('dashboard.products.create');
            Route::get('/dashboard/products/{product}', function (Product $product) {
                if ($product->business->user_id !== auth()->id()) {
                    abort(403);
                }
                session(['current_business_id' => $product->business_id]);

                return view('pages.product-detail', ['product' => $product]);
            })->name('dashboard.products.show');
            Route::get('/dashboard/products/{product}/edit', function (Product $product) {
                if ($product->business->user_id !== auth()->id()) {
                    abort(403);
                }
                session(['current_business_id' => $product->business_id]);

                return view('pages.product-edit', ['product' => $product]);
            })->name('dashboard.products.edit');
            Route::get('/dashboard/products/{product}/variants', function (Product $product) {
                if ($product->business->user_id !== auth()->id()) {
                    abort(403);
                }
                session(['current_business_id' => $product->business_id]);

                return view('pages.product-variants', ['product' => $product]);
            })->name('dashboard.products.variants');
        });

        // Services — for service businesses.
        Route::middleware('subscription:max_services')->group(function () {
            Route::view('/dashboard/services', 'pages.services')->name('dashboard.services');
            Route::view('/dashboard/services/create', 'pages.service-create')->name('dashboard.services.create');
            Route::get('/dashboard/services/{service}', function (Service $service) {
                if ($service->business->user_id !== auth()->id()) {
                    abort(403);
                }
                session(['current_business_id' => $service->business_id]);

                return view('pages.service-detail', ['service' => $service]);
            })->name('dashboard.services.show');
            Route::get('/dashboard/services/{service}/edit', function (Service $service) {
                if ($service->business->user_id !== auth()->id()) {
                    abort(403);
                }
                session(['current_business_id' => $service->business_id]);

                return view('pages.service-edit', ['service' => $service]);
            })->name('dashboard.services.edit');
        });

        // Notifications.
        Route::view('/dashboard/notifications', 'pages.notifications')->name('dashboard.notifications');
        Route::view('/dashboard/notifications/preferences', 'pages.notification-preferences')->name('dashboard.notifications.preferences');

        // Orders.
        Route::view('/dashboard/orders', 'pages.orders')->name('dashboard.orders');

        // Themes.
        Route::get('/dashboard/theme', function () {
            $user = auth()->user();
            $currentBusiness = $user->businesses()
                ->firstWhere('id', session('current_business_id'))
                ?: $user->businesses()->first();

            return view('pages.theme-settings', ['currentBusiness' => $currentBusiness]);
        })->name('dashboard.theme');
    });
});

// ─── Click tracking (public) ─────────────────────────────
Route::get('/track/{business}/{type}', function (Business $business, string $type) {
    if (! in_array($type, ['whatsapp_click', 'phone_click'])) {
        abort(404);
    }

    app(RecordEvent::class)(
        $business,
        $type,
        source: request()->query('source', 'profile'),
        url: request()->query('url'),
    );

    return response()->noContent();
})->name('track.click');

// ─── Marketing link redirect (public) ───────────────────
Route::get('/go/{link:slug}', function (MarketingLink $link) {
    if (! $link->is_active) {
        abort(404);
    }

    app(TrackClick::class)($link);

    return redirect()->away($link->url);
})->name('go');

// ─── Order system (public + auth) ───────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/{business:slug}/cart/add/{type}/{id}', function (Business $business, string $type, int $id) {
        if (! in_array($type, ['product', 'service'])) {
            abort(400);
        }

        $item = $type === 'product'
            ? $business->products()->where('id', $id)->first()
            : $business->services()->where('id', $id)->first();

        if (! $item || ! $item->is_active) {
            return back()->with('error', 'Item not available.');
        }

        $cartKey = 'cart.'.$business->id;
        $cart = session($cartKey, []);
        $cartItemId = $type.'_'.$id;

        if (isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] += 1;
        } else {
            $cart[$cartItemId] = [
                'type' => $type,
                'id' => $id,
                'name' => $item->name,
                'price' => (float) $item->price,
                'quantity' => 1,
                'image' => is_array($item->images) ? ($item->images[0] ?? null) : null,
            ];
        }

        session([$cartKey => $cart]);

        return back()->with('success', $item->name.' added to cart.');
    })->name('order.cart.add');

    Route::get('/{business:slug}/cart', Cart::class)->name('order.cart');
    Route::get('/{business:slug}/checkout', Checkout::class)->name('order.checkout');
    Route::get('/{business:slug}/inquiry', Inquiry::class)->name('order.inquiry');
    Route::get('/{business:slug}/inquiry/{product:slug}', function (Business $business, Product $product) {
        if ($product->business_id !== $business->id) {
            abort(404);
        }

        return view('livewire.order.inquiry', ['businessId' => $business->id, 'productId' => $product->id]);
    })->name('order.inquiry.product');
    Route::get('/{business:slug}/inquiry/service/{service:slug}', function (Business $business, Service $service) {
        if ($service->business_id !== $business->id) {
            abort(404);
        }

        return view('livewire.order.inquiry', ['businessId' => $business->id, 'serviceId' => $service->id]);
    })->name('order.inquiry.service');
});

// Paystack order callback
Route::get('/order/paystack/callback', function (Request $request) {
    $reference = $request->query('reference');

    if (! $reference) {
        return redirect()->route('dashboard')->with('error', 'Payment verification failed.');
    }

    try {
        $response = Paystack::transactions()->verify($reference);

        if (! ($response['status'] ?? false) || ($response['data']['status'] ?? '') !== 'success') {
            return redirect()->route('dashboard')->with('error', 'Payment was not completed.');
        }

        $orderId = $response['data']['metadata']['order_id'] ?? null;

        if (! $orderId) {
            return redirect()->route('dashboard')->with('error', 'Invalid payment metadata.');
        }

        $order = Order::find($orderId);
        if (! $order) {
            return redirect()->route('dashboard')->with('error', 'Order not found.');
        }

        $order->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'gateway_reference' => $reference,
            'gateway_data' => $response,
        ]);

        session()->forget('cart.'.$order->business_id);

        $business = $order->business;

        return redirect()->route('business.profile', ['business' => $business->slug])
            ->with('success', 'Payment successful! Your order '.$order->order_number.' has been confirmed.');

    } catch (Throwable $e) {
        Log::error('Order Paystack callback failed', [
            'reference' => $reference,
            'error' => $e->getMessage(),
        ]);

        return redirect()->route('dashboard')->with('error', 'Payment verification failed. Please contact support.');
    }
})->name('order.paystack.callback');

// ─── Booking system (public) ────────────────────────────
Route::get('/{business:slug}/book/{service}', function (Business $business, Service $service) {
    if ($service->business_id !== $business->id) {
        abort(404);
    }

    return view('livewire.booking.book-appointment', ['business' => $business, 'service' => $service]);
})->name('booking.show');

// ─── Review system (public + auth) ───────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/{business:slug}/review', function (Business $business) {
        return view('livewire.review.leave-review', ['businessId' => $business->id]);
    })->name('review.create');
});

// ─── Admin panel ──────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/users', UserManager::class)->name('users');
    Route::get('/businesses', BusinessManager::class)->name('businesses');
    Route::get('/payments', PaymentManager::class)->name('payments');
    Route::get('/subscriptions', SubscriptionManager::class)->name('subscriptions');
    Route::get('/themes', ThemeManager::class)->name('themes');
    Route::get('/categories', CategoryManager::class)->name('categories');
    Route::get('/settings', PlatformSettingsManager::class)->name('settings');
    Route::get('/settings/ai', AiSettingsManager::class)->name('settings.ai');
    Route::get('/orders', OrderManager::class)->name('orders');
    Route::get('/analytics', PlatformAnalytics::class)->name('analytics');
    Route::get('/verifications', VerificationManager::class)->name('verifications');
});

// ─── Public promotion landing page ───────────────────────
Route::get('/{business:slug}/promotions/{promotion:slug}', function (Business $business, Promotion $promotion) {
    if ($promotion->business_id !== $business->id) {
        abort(404);
    }

    $products = collect();
    $services = collect();

    if (in_array($promotion->scope, ['all', 'selected_products'])) {
        $products = $promotion->products()
            ->where('is_active', true)
            ->with(['promotions' => fn ($q) => $q->active()])
            ->get();
    }

    if (in_array($promotion->scope, ['all', 'selected_services'])) {
        $services = $promotion->services()
            ->where('is_active', true)
            ->with(['promotions' => fn ($q) => $q->active()])
            ->get();
    }

    return view('pages.promotions-show', [
        'business' => $business,
        'promotion' => $promotion,
        'products' => $products,
        'services' => $services,
    ]);
})->name('business.promotions.show');

// ─── Public business profile & detail pages (slug-based) ──
// Placed last so static routes (explore, pricing, dashboard, etc.) take precedence.
Route::get('/{business:slug}/product/{product:slug}', PublicProfile::class)->name('business.product');
Route::get('/{business:slug}/service/{service:slug}', PublicProfile::class)->name('business.service');
Route::get('/{business:slug}/products', PublicProfile::class)->name('business.products');
Route::get('/{business:slug}/services', PublicProfile::class)->name('business.services');
Route::get('/{business:slug}/portfolio', [PublicPageController::class, 'portfolio'])->name('business.portfolio');
Route::get('/{business:slug}/contact', [PublicPageController::class, 'contact'])->name('business.contact');
Route::get('/{business:slug}/about', [PublicPageController::class, 'about'])->name('business.about');
Route::get('/{business:slug}/site', PublicProfile::class)->name('business.site');
Route::get('/{business:slug}', BusinessDetail::class)->name('business.profile');
