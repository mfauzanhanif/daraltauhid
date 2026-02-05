<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class LoginController extends Controller
{
    /**
     * Show the institution selection screen.
     */
    public function selectInstitution()
    {
        $user = auth()->user();
        $institutions = $user->getInstitutions();

        // If user has no roles/institutions, maybe redirect to a specific error page or dashboard with limited access
        if ($institutions->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke lembaga manapun.');
        }

        return Inertia::render('Auth/InstitutionSelect', [
            'institutions' => $institutions,
        ]);
    }
}
