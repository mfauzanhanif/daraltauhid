<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstitutionAccess
{
    /**
     * Handle an incoming request.
     * Middleware ini mengecek apakah User boleh masuk ke institusi
     * yang sudah ditemukan oleh middleware SetCurrentInstitution.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. Jika User Global Admin (Yayasan), izinkan masuk ke mana saja
        if ($user && $user->isGlobalAdmin()) {
            return $next($request);
        }

        // 2. Ambil institusi dari konteks (hasil kerja SetCurrentInstitution)
        if (app()->bound('current_institution')) {
            $institution = app('current_institution');

            // 3. Cek apakah user punya role di institusi ini
            // Menggunakan method yang sudah ada di User.php
            if (! $user || ! $user->hasRoleInInstitution($institution->id)) {
                // Jika request JSON/Inertia, return 403
                if ($request->wantsJson()) {
                    abort(403, 'Anda tidak terdaftar sebagai staf di lembaga ini.');
                }
                // Jika akses web biasa, abort dengan pesan
                abort(403, 'Akses Ditolak: Anda bukan staf ' . $institution->nickname);
            }
        }

        return $next($request);
    }
}
