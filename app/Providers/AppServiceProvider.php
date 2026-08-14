<?php

namespace App\Providers;

use App\Domains\Administration\Policies\AdminPolicy;
use App\Domains\Authentication\Policies\UserPolicy;
use App\Domains\Business\Policies\BusinessPolicy;
use App\Domains\Business\Policies\LocationPolicy;
use App\Domains\Marketing\Policies\MarketingLinkPolicy;
use App\Domains\Media\Policies\MediaPolicy;
use App\Domains\Payment\Policies\TransactionPolicy;
use App\Domains\Product\Policies\ProductPolicy;
use App\Domains\Service\Policies\ServicePolicy;
use App\Domains\Subscription\Policies\SubscriptionPolicy;
use App\Domains\Theme\Policies\ThemePolicy;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\MarketingLink;
use App\Models\Media;
use App\Models\PlatformSetting;
use App\Models\Product;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\Theme;
use App\Models\Transaction;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\Livewire;

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
        $this->registerDomainLivewireComponents();
        $this->applyAiConfig();
    }

    /**
     * Inject AI settings from the database into Prism's config at runtime.
     */
    protected function applyAiConfig(): void
    {
        try {
            if (! app()->runningInConsole() && Schema::hasTable('platform_settings')) {
                $provider = PlatformSetting::get('ai_provider', 'openai');
                $apiKey = PlatformSetting::get('ai_api_key', '');

                if ($apiKey && config("prism.providers.{$provider}")) {
                    config(["prism.providers.{$provider}.api_key" => $apiKey]);
                }
            }
        } catch (\Throwable $e) {
            // Table may not exist during migrations — silently skip.
        }
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
        Gate::policy(BusinessLocation::class, LocationPolicy::class);
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

    /**
     * Scan all domain Livewire components and register them.
     *
     * Components live under app/Domains/{Domain}/Livewire/{Component}.php
     * but Livewire's auto-discovery only looks in App\Livewire.
     * This registers each one with the correct alias (e.g. review.review-display).
     */
    protected function registerDomainLivewireComponents(): void
    {
        $filesystem = app(Filesystem::class);
        $domainsPath = app_path('Domains');

        if (! $filesystem->isDirectory($domainsPath)) {
            return;
        }

        foreach ($filesystem->directories($domainsPath) as $domainPath) {
            $domain = basename($domainPath);
            $livewirePath = $domainPath.DIRECTORY_SEPARATOR.'Livewire';

            if (! $filesystem->isDirectory($livewirePath)) {
                continue;
            }

            foreach ($filesystem->files($livewirePath) as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $className = 'App\\Domains\\'.$domain.'\\Livewire\\'.$file->getBasename('.php');

                if (! class_exists($className)) {
                    continue;
                }

                $reflection = new \ReflectionClass($className);

                if ($reflection->isAbstract() || ! $reflection->isSubclassOf(Component::class)) {
                    continue;
                }

                $alias = Str::kebab($domain).'.'.Str::kebab($file->getBasename('.php'));

                Livewire::component($alias, $className);
            }
        }
    }
}
