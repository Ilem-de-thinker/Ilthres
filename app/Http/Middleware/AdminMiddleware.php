<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensures the authenticated user is an admin.
 *
 * Redirects non-admin users to their dashboard with an error message.
 * This is a simple flag check — a full RBAC system is deferred to a later phase.
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->is_admin) {
            return redirect('/dashboard')
                ->with('error', 'You do not have permission to access the admin panel.');
        }

        return $next($request);
    }
}
