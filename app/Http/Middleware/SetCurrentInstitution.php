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
     * Middleware ini menentukan institusi aktif dari session atau domain.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $institution = null;

        // 1. Prioritas: Ambil dari session (user sudah pilih institusi)
        if (session()->has('current_institution_id')) {
            $institutionId = session('current_institution_id');
            $institution = cache()->remember("institution_{$institutionId}", 3600, function () use ($institutionId) {
                return Institution::find($institutionId);
            });
        }

        // 2. Fallback: Coba cari dari domain (untuk landing pages)
        if (! $institution) {
            $host = $request->getHost();
            $institution = cache()->remember("domain_mapping_{$host}", 3600, function () use ($host) {
                return Institution::findByDomain($host);
            });
        }

        // 3. Simpan ke Service Container jika ketemu
        if ($institution) {
            app()->instance('current_institution', $institution);
        }

        return $next($request);
    }
}
