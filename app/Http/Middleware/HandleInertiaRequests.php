<?php

namespace App\Http\Middleware;

use App\Models\Institution;
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
        $user = $request->user();

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $user,
                'institution' => $this->getCurrentInstitution(),
                'institutions' => $user ? $this->getUserInstitutions($user) : [],
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }

    /**
     * Get current institution from service container.
     */
    protected function getCurrentInstitution(): ?Institution
    {
        if (app()->bound('current_institution')) {
            return app('current_institution');
        }

        return null;
    }

    /**
     * Get all institutions the user has access to.
     * For Global Admin, includes all institutions.
     */
    protected function getUserInstitutions($user): array
    {
        // Global Admin: bisa akses semua institusi
        if ($user->isGlobalAdmin()) {
            return Institution::where('is_active', true)
                ->orderBy('name')
                ->get()
                ->toArray();
        }

        // Regular user: hanya institusi yang punya role
        return $user->getInstitutions()->toArray();
    }
}
