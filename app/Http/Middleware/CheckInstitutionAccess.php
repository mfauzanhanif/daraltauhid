<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstitutionAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If user is global admin, bypass check
        if ($user && $user->isGlobalAdmin()) {
            return $next($request);
        }

        // Get institution ID from route parameter 'institution' (if resource route)
        // or query parameter 'institution_id'
        $institutionId = $request->route('institution')?->id
            ?? $request->route('institution')
            ?? $request->input('institution_id');

        // Logic:
        // 1. If no institution context is required (null), proceed.
        // 2. If institution is specified, check if user has role in it.

        if ($institutionId) {
            if (! $user->hasRoleInInstitution($institutionId)) {
                abort(403, 'Anda tidak memiliki akses ke lembaga ini.');
            }
        }

        return $next($request);
    }
}
