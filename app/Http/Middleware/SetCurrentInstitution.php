<?php

namespace App\Http\Middleware;

use App\Models\Institution;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentInstitution
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if domain mapping determines institution
        $host = $request->getHost();
        $institution = Institution::findByDomain($host);

        if ($institution) {
            // Set in session or container
            session(['current_institution' => $institution]);
            // Also share with Inertia if needed, usually done in HandleInertiaRequests
            return $next($request);
        }

        // 2. Fallback: Check session for manually selected institution
        // This is set when user logs in and selects an institution
        // OR when user switches institution via UI
        // if (session()->has('current_institution_id')) { ... }

        return $next($request);
    }
}
