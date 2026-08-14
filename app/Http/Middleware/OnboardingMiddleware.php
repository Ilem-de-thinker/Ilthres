<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirects authenticated users to their current onboarding step
 * if they haven't completed the onboarding wizard.
 *
 * Steps:
 *   0 → not started       → redirect to /get-started (choice page)
 *   1 → WhatsApp step     → redirect to /onboarding
 *   2 → Business type
 *   3 → Pricing
 *   4 → Setup
 *   5 → completed         → allow through
 *
 * Skips redirection if the user is already on the onboarding/get-started/link-business page
 * to avoid infinite redirect loops.
 */
class OnboardingMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Only applies to authenticated users.
        if (! $user) {
            return $next($request);
        }

        // User has finished onboarding — let them through.
        if ($user->onboarding_completed) {
            return $next($request);
        }

        // Already on pages that must be accessible before onboarding — avoid redirect loop.
        if ($request->routeIs('onboarding', 'get-started', 'link-business')) {
            return $next($request);
        }

        // User hasn't started onboarding yet — show the choice page.
        if ($user->onboarding_step === 0) {
            return redirect()->route('get-started');
        }

        // User is in the middle of onboarding — resume the wizard.
        return redirect()->route('onboarding');
    }
}
