<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckInstitutionAccess
{
    /**
     * Handle an incoming request.
     *
     * Validasi bahwa user memiliki akses (role) di institution yang sedang diakses.
     * Middleware ini harus berjalan SETELAH SetCurrentInstitution.
     *
     * MODULES.md Spec:
     * - Middleware wajib berjalan setiap kali request switch lembaga atau mengakses URL lembaga
     * - Logic: "User ini mengakses URL /ppdt/. Apakah User punya role di Institution ID milik ppdt? Jika TIDAK -> Abort 403."
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Jika belum login, biarkan auth middleware yang handle
        if (! $user) {
            return $next($request);
        }

        // Ambil current institution dari Service Container (diset oleh SetCurrentInstitution)
        $currentInstitution = app()->bound('current_institution')
            ? app('current_institution')
            : null;

        // Jika tidak ada institution context, lanjutkan (global routes)
        if (! $currentInstitution) {
            return $next($request);
        }

        // === ACCESS VALIDATION LOGIC ===

        // 1. Global Admin handling
        if ($user->isGlobalAdmin()) {
            // Two Hats Rule: jika Global Admin juga punya scoped role di lembaga ini,
            // inject sebagai scoped_role agar bertindak sesuai role lokal.
            // Contoh: Operator Yayasan yang juga Guru MI → saat akses MI, bertindak sebagai Guru MI.
            $accessType = $user->hasRoleInInstitution($currentInstitution->id)
                ? 'scoped_role'
                : 'global_admin';
            $this->injectSessionContext($currentInstitution, $user, $accessType);

            return $next($request);
        }

        // 2. Cek apakah user memiliki role di institution ini
        if (! $user->hasRoleInInstitution($currentInstitution->id)) {
            abort(403, 'Anda tidak memiliki akses ke lembaga ini.');
        }

        // 3. Inject session context untuk user yang valid
        $this->injectSessionContext($currentInstitution, $user, 'scoped_role');

        return $next($request);
    }

    /**
     * Inject context information into session.
     * Untuk tracking dan audit purposes.
     */
    protected function injectSessionContext($institution, $user, string $accessType): void
    {
        // Store access context for current request
        session([
            'institution_access_type' => $accessType,
            'institution_access_at' => now()->toIso8601String(),
        ]);

        // Get user's roles in this institution for reference
        if ($accessType === 'scoped_role') {
            $roles = $user->getRolesInInstitution($institution->id);
            session(['current_roles' => $roles->pluck('name')->toArray()]);
        } else {
            // Global admin accessing institution
            session(['current_roles' => ['Global Admin']]);
        }
    }
}
