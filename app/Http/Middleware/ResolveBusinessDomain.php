<?php

namespace App\Http\Middleware;

use App\Models\Website;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolves the business from the incoming request hostname.
 *
 * Checks the Host header against registered websites (subdomain + custom domain)
 * and attaches the resolved business and website to the request. If no match
 * is found, the request continues to regular route handling.
 */
class ResolveBusinessDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $baseDomain = config('app.base_domain');

        if (! $baseDomain) {
            return $next($request);
        }

        $website = Website::where('is_active', true)
            ->with('business')
            ->get()
            ->first(fn (Website $w) => $w->matchesHost($host));

        if ($website) {
            $request->attributes->set('current_website', $website);
            $request->attributes->set('current_business', $website->business);
        }

        return $next($request);
    }
}
