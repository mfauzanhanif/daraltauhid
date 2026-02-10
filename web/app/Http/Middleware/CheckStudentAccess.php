<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckStudentAccess
{
    /**
     * Handle an incoming request.
     *
     * Validasi bahwa user (Wali Santri) memiliki akses ke siswa yang sedang diakses.
     * Route: /wali/{student}/...
     * {student} adalah public_id (NanoID 10 digit) bukan database ID.
     *
     * MODULES.md Spec:
     * - Gunakan NanoID (public_id) 10 digit pada tabel siswa
     * - Pastikan Middleware sangat ketat (memastikan public_id siswa tersebut benar-benar anak dari user)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Jika belum login, biarkan auth middleware yang handle
        if (!$user) {
            return $next($request);
        }

        // Ambil student public_id dari route parameter
        $studentPublicId = $request->route('student');

        if (!$studentPublicId) {
            return $next($request);
        }

        // Validate public_id format (NanoID 10 chars)
        if (!$this->isValidPublicId($studentPublicId)) {
            abort(400, 'Format ID siswa tidak valid.');
        }

        // === ACCESS VALIDATION LOGIC ===

        // 1. Global Admin bypass
        if ($user->isGlobalAdmin()) {
            $this->bindStudent($studentPublicId, $user, 'global_admin');
            return $next($request);
        }

        // 2. KETAT: Cek apakah user adalah wali dari siswa ini
        if (!$user->isWaliOf($studentPublicId)) {
            // Log suspicious access attempt
            Log::warning('Student access denied', [
                'user_id' => $user->id,
                'student_public_id' => $studentPublicId,
                'ip' => $request->ip(),
            ]);

            abort(403, 'Anda tidak memiliki akses ke data siswa ini.');
        }

        // 3. Bind student ke container
        $this->bindStudent($studentPublicId, $user, 'wali');

        return $next($request);
    }

    /**
     * Validate public_id format.
     * NanoID format: 10 alphanumeric characters.
     */
    protected function isValidPublicId(string $publicId): bool
    {
        // Allow 10-12 chars alphanumeric (flexible for different NanoID configs)
        return preg_match('/^[A-Za-z0-9_-]{8,12}$/', $publicId) === 1;
    }

    /**
     * Bind current student to Service Container.
     */
    protected function bindStudent(string $publicId, $user, string $accessType): void
    {
        // @todo: Implement when Student model is ready
        // $student = Cache::remember(
        //     "student_public_{$publicId}",
        //     60 * 60 * 24,
        //     fn () => \App\Models\Student::findByPublicId($publicId)
        // );
        //
        // if ($student) {
        //     app()->instance('current_student', $student);
        //
        //     session([
        //         'current_student_id' => $student->id,
        //         'current_student_public_id' => $student->public_id,
        //         'current_student_name' => $student->name,
        //         'student_access_type' => $accessType,
        //         'student_access_at' => now()->toIso8601String(),
        //     ]);
        //
        //     view()->share('currentStudent', $student);
        //     return;
        // }

        // Placeholder implementation until Student model is ready
        app()->instance('current_student', null);

        session([
            'current_student_public_id' => $publicId,
            'student_access_type' => $accessType,
            'student_access_at' => now()->toIso8601String(),
        ]);
    }
}
