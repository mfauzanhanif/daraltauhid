<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    /**
     * Show the institution selection screen.
     * For PTK (staff/teachers) users with multiple institutions.
     */
    public function selectInstitution(): Response|RedirectResponse
    {
        $user = Auth::user();
        $institutions = $user->getInstitutions();
        $isGlobalAdmin = $user->isGlobalAdmin();

        // If only one institution and not global admin, redirect directly
        if ($institutions->count() === 1 && ! $isGlobalAdmin) {
            $institution = $institutions->first();
            session(['current_institution_id' => $institution->id]);

            return redirect()->to($institution->getDashboardUrl());
        }

        // If global admin with no institution roles, go to admin dashboard
        if ($isGlobalAdmin && $institutions->isEmpty()) {
            return redirect()->to(root_dashboard_url());
        }

        return Inertia::render('auth/institution-select', [
            'institutions' => $institutions->map(fn (Institution $i) => [
                'id' => $i->id,
                'code' => $i->code,
                'name' => $i->name,
                'type' => $i->type,
                'url' => $i->getDashboardUrl(),
            ])->toArray(),
            'hasAdminAccess' => $isGlobalAdmin,
            'adminDashboardUrl' => root_dashboard_url(),
        ]);
    }

    /**
     * Show the student selection screen.
     * For Wali (parent/guardian) users with multiple children.
     */
    public function selectStudent(): Response|RedirectResponse
    {
        $user = Auth::user();
        $students = $user->getStudents();

        // If only one student, redirect directly
        if ($students->count() === 1) {
            $student = $students->first();
            session(['current_student_id' => $student->public_id]);

            return redirect("/wali/{$student->public_id}/dashboard");
        }

        return Inertia::render('auth/student-select', [
            'students' => $students->map(fn ($s) => [
                'id' => $s->id ?? null,
                'public_id' => $s->public_id ?? null,
                'name' => $s->name ?? null,
                'nis' => $s->nis ?? null,
                'class_name' => $s->currentClass?->name ?? null,
                'institution_name' => $s->institution?->name ?? null,
            ])->toArray(),
        ]);
    }

    /**
     * Show the institution switch page (within dashboard context).
     * GET /{institution}/switch-institution
     */
    public function switchInstitutionPage(string $institution): Response
    {
        $user = Auth::user();
        $institutions = $user->getInstitutions();
        $isGlobalAdmin = $user->isGlobalAdmin();

        return Inertia::render('auth/institution-switch', [
            'institutions' => $institutions->map(fn (Institution $i) => [
                'id' => $i->id,
                'code' => $i->code,
                'name' => $i->name,
                'type' => $i->type,
                'url' => $i->getDashboardUrl(),
            ])->toArray(),
            'hasAdminAccess' => $isGlobalAdmin,
            'adminDashboardUrl' => root_dashboard_url(),
            'currentInstitution' => $institution,
        ]);
    }

    /**
     * Switch to a specific institution context.
     * GET /switch-institution/{code}
     */
    public function switchInstitution(string $code): RedirectResponse
    {
        $user = Auth::user();
        $institution = Institution::findByCode($code);

        if (! $institution) {
            abort(404, 'Lembaga tidak ditemukan.');
        }

        // Validate access (except Global Admin)
        if (! $user->isGlobalAdmin() && ! $user->hasRoleInInstitution($institution->id)) {
            abort(403, 'Anda tidak memiliki akses ke lembaga ini.');
        }

        session(['current_institution_id' => $institution->id]);

        return redirect()->to($institution->getDashboardUrl());
    }

    /**
     * Switch to a specific student context (for Wali).
     * GET /switch-student/{publicId}
     */
    public function switchStudent(string $publicId): RedirectResponse
    {
        $user = Auth::user();

        // Validate wali access
        if (! $user->isGlobalAdmin() && ! $user->isWaliOf($publicId)) {
            abort(403, 'Anda tidak memiliki akses ke data siswa ini.');
        }

        session(['current_student_id' => $publicId]);

        return redirect("/wali/{$publicId}/dashboard");
    }
}
