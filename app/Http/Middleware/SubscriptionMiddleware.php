<?php

namespace App\Http\Middleware;

use App\Models\Business;
use App\Models\PlatformSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Middleware that checks if the current business's plan allows a given feature.
 *
 * Usage in routes:
 *   Route::middleware('subscription:max_media')->group(...)
 *
 * Also accepts an optional operator and value:
 *   Route::middleware('subscription:max_media,>=,5')->group(...)
 *
 * Resolves the current business from the session. If no active subscription
 * is found, or if the plan's limit is exceeded, a 403 is returned.
 *
 * When platform pricing is disabled (pricing_enabled = false), all checks
 * are bypassed and all features are allowed.
 */
class SubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  string  $feature  The feature key from config/plans.php.
     * @param  string|null  $operator  Comparison operator (default: >=).
     * @param  string|null  $value  Value to compare against (default: reads from config).
     */
    public function handle(Request $request, Closure $next, string $feature, ?string $operator = null, ?string $value = null): mixed
    {
        $pricingEnabled = (bool) PlatformSetting::get('pricing_enabled', true);

        $businessId = session('current_business_id');

        // Fall back to the user's first business if no session value set.
        if (! $businessId) {
            $firstBusiness = auth()->user()->businesses()->first();
            $businessId = $firstBusiness?->id;
        }

        if (! $businessId) {
            throw new HttpException(403, 'No business found. Create a business first.');
        }

        $business = Business::with('subscription.plan')->find($businessId);

        if (! $business) {
            throw new HttpException(403, 'Business not found.');
        }

        $subscription = $business->subscription;

        // When pricing is disabled, use professional plan for free.
        // Active subscribers keep their current plan; expired/no-subscription get professional.
        if (! $pricingEnabled) {
            if ($subscription && $subscription->isActive()) {
                $planSlug = $subscription->plan?->slug ?? 'professional';
            } else {
                $planSlug = 'professional';
            }
        } else {
            // When pricing is enabled, enforce active subscription.
            if ($subscription && ! $subscription->isActive()) {
                throw new HttpException(403, 'Your subscription is no longer active.');
            }

            $planSlug = $subscription?->plan?->slug ?? 'free';
        }

        $limits = config("plans.{$planSlug}");

        if ($limits === null) {
            throw new HttpException(403, 'Plan configuration not found.');
        }

        $limit = $limits[$feature] ?? null;

        if ($limit === null) {
            // Feature not defined for this plan — deny by default.
            throw new HttpException(403, "Your plan does not support '{$feature}'.");
        }

        // If an explicit value is provided, compare; otherwise check boolean truthiness.
        if ($value !== null) {
            $operator = $operator ?? '>=';
            $allowed = match ($operator) {
                '>=' => $limit >= (int) $value,
                '>' => $limit > (int) $value,
                '==' => $limit == $value,
                '<=' => $limit <= (int) $value,
                '<' => $limit < (int) $value,
                default => (bool) $limit,
            };

            if (! $allowed) {
                throw new HttpException(403, "Your plan does not allow this action (requires {$operator} {$value}, limit is {$limit}).");
            }
        } elseif (! $limit) {
            // Boolean feature — false means not allowed.
            throw new HttpException(403, "Your plan does not support '{$feature}'.");
        }

        return $next($request);
    }
}
