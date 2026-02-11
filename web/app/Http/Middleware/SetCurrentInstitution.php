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
        // Ambil institution code dari route parameter
        $institutionParam = $request->route('institution');

        $institution = null;

        if ($institutionParam instanceof Institution) {
            $institution = $institutionParam;
        } elseif (is_string($institutionParam)) {
            // Cek Cache dulu (Simpan selama 24 jam)
            $institution = Cache::remember(
                "institution_code_{$institutionParam}",
                60 * 60 * 24,
                fn() => Institution::findByCode($institutionParam)
            );
        }

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
            // Hanya abort jika ada parameter institution tapi tidak ketemu
            if ($institutionParam) {
                abort(404, 'Lembaga tidak ditemukan.');
            }
        }
        
        if (!$institutionParam) {
            // Tidak ada institution context (Global routes seperti /admin)
            app()->instance('current_institution', null);
        }

        return $next($request);
    }
}
