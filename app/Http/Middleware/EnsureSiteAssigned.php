<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiteAssigned
{
    /**
     * Non-admin users must have a site_id assigned to access the app.
     * Admin is exempt — they can see all sites.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->needsSiteAssignment()) {
            // If this is an API/AJAX request, return JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Akun Anda belum ditetapkan ke site manapun. Hubungi Administrator.',
                ], 403);
            }

            // For web requests, show a blocked page
            return response()->view('errors.no-site', [], 403);
        }

        return $next($request);
    }
}
