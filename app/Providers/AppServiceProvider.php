<?php

namespace App\Providers;

use App\Domains\Administration\Policies\AdminPolicy;
use App\Domains\Authentication\Policies\UserPolicy;
use App\Domains\Business\Policies\BusinessPolicy;
use App\Domains\Media\Policies\MediaPolicy;
use App\Domains\Product\Policies\ProductPolicy;
use App\Domains\Service\Policies\ServicePolicy;
use App\Domains\Subscription\Policies\SubscriptionPolicy;
use App\Domains\Marketing\Policies\MarketingLinkPolicy;
use App\Domains\Payment\Policies\TransactionPolicy;
use App\Domains\Theme\Policies\ThemePolicy;
use App\Models\Business;
use App\Models\Media;
use App\Models\User;
use App\Models\Product;
use App\Models\Service;
use App\Models\MarketingLink;
use App\Models\Subscription;
use App\Models\Theme;
use App\Models\Transaction;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::addNamespace('themes', resource_path('themes'));

        $this->configureDefaults();
        $this->registerPolicies();
    }

    /**
     * Register domain authorization policies.
     *
     * Policies are registered here instead of an AuthServiceProvider
     * because Laravel 11+ removed the default AuthServiceProvider template.
     */
    protected function registerPolicies(): void
    {
        Gate::define('admin.access', [AdminPolicy::class, 'access']);

        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Business::class, BusinessPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Media::class, MediaPolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);
        Gate::policy(Subscription::class, SubscriptionPolicy::class);
        Gate::policy(Transaction::class, TransactionPolicy::class);
        Gate::policy(MarketingLink::class, MarketingLinkPolicy::class);
        Gate::policy(Theme::class, ThemePolicy::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
