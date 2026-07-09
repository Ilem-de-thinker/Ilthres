<?php

use App\Domains\Administration\Livewire\AdminDashboard;
use App\Domains\Administration\Livewire\BusinessManager;
use App\Domains\Administration\Livewire\CategoryManager;
use App\Domains\Administration\Livewire\PaymentManager;
use App\Domains\Administration\Livewire\SubscriptionManager;
use App\Domains\Administration\Livewire\ThemeManager;
use App\Domains\Administration\Livewire\UserManager;
use App\Domains\Analytics\Actions\RecordEvent;
use App\Domains\Business\Actions\CreateBusiness;
use App\Domains\Business\DTOs\BusinessData;
use App\Domains\Business\Livewire\PublicProfile;
use App\Domains\Marketing\Actions\TrackClick;
use App\Domains\Payment\Actions\RecordPayment;
use App\Domains\Payment\DTOs\TransactionData;
use App\Http\Middleware\OnboardingMiddleware;
use App\Models\Business;
use App\Models\MarketingLink;
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

        // Subscription.
        Route::view('/dashboard/subscription', 'pages.subscription')->name('dashboard.subscription');

        // Pricing.
        Route::view('/dashboard/pricing', 'pages.pricing')->name('dashboard.pricing');

        // Billing.
        Route::view('/dashboard/billing', 'pages.billing')->name('dashboard.billing');

        // Marketing.
        Route::view('/dashboard/marketing', 'pages.marketing')->name('dashboard.marketing');
        Route::view('/dashboard/marketing/create', 'pages.marketing-create')->name('dashboard.marketing.create');

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
        Route::view('/dashboard/business/edit', 'pages.business-edit')->name('dashboard.business.edit');
        Route::view('/business/create', 'pages.create-business')->name('business.create');

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

// ─── Admin panel ──────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/users', UserManager::class)->name('users');
    Route::get('/businesses', BusinessManager::class)->name('businesses');
    Route::get('/payments', PaymentManager::class)->name('payments');
    Route::get('/subscriptions', SubscriptionManager::class)->name('subscriptions');
    Route::get('/themes', ThemeManager::class)->name('themes');
    Route::get('/categories', CategoryManager::class)->name('categories');
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
Route::get('/{business:slug}', PublicProfile::class)->name('business.profile');
