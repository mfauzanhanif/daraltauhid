<?php

namespace App\Http\Middleware;

use App\Models\Institution;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class SetCurrentInstitution
{
    /**
     * Handle an incoming request.
     *
     * Path-based multi-tenancy: Mengambil institution dari route parameter {institution}
     * yang berisi code lembaga (contoh: MI, SMP, PONDOK, dll).
     *
     * Session Injection Logic:
     * 1. Ambil institution code dari URL
     * 2. Validasi institution exists
     * 3. Bind ke Service Container (app-level)
     * 4. Simpan di session (persist antar request)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil institution code dari route parameter
        $institutionCode = $request->route('institution');

        if ($institutionCode) {
            // Cek Cache dulu (Simpan selama 24 jam)
            $institution = Cache::remember(
                "institution_code_{$institutionCode}",
                60 * 60 * 24,
                fn() => Institution::findByCode($institutionCode)
            );

            if ($institution) {
                // === SESSION INJECTION LOGIC ===

                // 1. Bind ke Service Container agar bisa diakses global via app('current_institution')
                app()->instance('current_institution', $institution);

                // 2. Simpan di session untuk persistence antar request
                session([
                    'current_institution_id' => $institution->id,
                    'current_institution_code' => $institution->code,
                    'current_institution_name' => $institution->name,
                ]);

                // 3. Share data ke View untuk frontend (Inertia/Blade)
                view()->share('currentInstitution', $institution);
            } else {
                // Institution code tidak valid, abort 404
                abort(404, 'Lembaga tidak ditemukan.');
            }
        } else {
            // Tidak ada institution context (Global routes seperti /admin)
            app()->instance('current_institution', null);

            // Clear session jika sebelumnya ada
            // NOTE: Jangan clear di sini, biarkan session tetap untuk reference
        }

        return $next($request);
    }
}
