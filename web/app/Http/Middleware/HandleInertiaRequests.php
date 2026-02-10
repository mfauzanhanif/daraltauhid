<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // Ambil institusi dari Service Container (hasil kerja middleware SetCurrentInstitution)
        $currentInstitution = current_institution();
        $currentStudent = current_student();

        return [
            ...parent::share($request),
            'name' => config('app.name'),

            // Auth Data
            'auth' => [
                'user' => $request->user(),
                // Kirim roles sesuai context
                'roles' => $request->user()
                    ? $request->user()->getRolesInInstitution($currentInstitution?->id)
                    : [],
                // Portal info untuk UI
                'available_portals' => $request->user()
                    ? $request->user()->getAvailablePortals()
                    : [],
            ],

            // Context: Institution (untuk Portal Lembaga)
            'current_institution' => $currentInstitution ? [
                'id' => $currentInstitution->id,
                'code' => $currentInstitution->code,
                'name' => $currentInstitution->name,
                'type' => $currentInstitution->type,
                'category' => $currentInstitution->category,
                'logo_path' => $currentInstitution->logo_path,
                'theme_color' => $currentInstitution->theme_color ?? null,
                'active_academic_year_id' => $currentInstitution->active_academic_year_id ?? null,
            ] : null,

            // Context: Student (untuk Portal Wali Santri)
            'current_student' => $currentStudent ? [
                'id' => $currentStudent->id ?? null,
                'public_id' => $currentStudent->public_id ?? null,
                'name' => $currentStudent->name ?? null,
            ] : null,

            // UI State
            'sidebarOpen' => !$request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',

            // Current Portal Context (for header switch button & logo link)
            'currentPortal' => $this->getCurrentPortal($request, $currentInstitution, $currentStudent),
        ];
    }

    /**
     * Get current portal context for frontend.
     *
     * Note: This may run before route middleware, so we need to handle
     * both cases: when current_institution() is set and when it's not.
     */
    private function getCurrentPortal(Request $request, $institution, $student): ?array
    {
        // If institution not set by middleware, try to get from route
        if (!$institution && $request->route('institution')) {
            $institutionCode = $request->route('institution');
            $institution = \App\Models\Institution::findByCode($institutionCode);
        }

        // If student not set by middleware, try to get from route
        if (!$student && $request->route('student')) {
            $studentId = $request->route('student');
            // For now, just use the ID from route
            $student = (object) ['public_id' => $studentId, 'name' => 'Santri'];
        }

        // Institution context (includes YDTP)
        if ($institution) {
            return [
                'type' => 'institution',
                'code' => $institution->code,
                'name' => $institution->nickname ?? $institution->name,
                'dashboardUrl' => "/{$institution->code}/dashboard",
            ];
        }

        // Wali context
        if ($student) {
            return [
                'type' => 'wali',
                'code' => $student->public_id,
                'name' => $student->name ?? 'Wali Santri',
                'dashboardUrl' => "/wali/{$student->public_id}/dashboard",
            ];
        }

        return null;
    }
}
