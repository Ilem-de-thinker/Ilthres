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
 *   0 → not started       → redirect to /onboarding
 *   1 → WhatsApp step
 *   2 → Business type
 *   3 → Pricing
 *   4 → Setup
 *   5 → completed         → allow through
 *
 * Skips redirection if the user is already on the onboarding page
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

        // Already on the onboarding page — avoid redirect loop.
        if ($request->routeIs('onboarding')) {
            return $next($request);
        }

        // Redirect to the onboarding page.
        return redirect()->route('onboarding');
    }
}
