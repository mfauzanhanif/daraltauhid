<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        // Check if global admin
        if ($user->isGlobalAdmin()) {
            return redirect()->intended(config('fortify.home'));
        }

        $institutions = $user->getInstitutions();

        // If user has roles in multiple institutions, redirect to selector
        if ($institutions->count() > 1) {
            return redirect()->route('institution.select');
        }

        // If user has role in only one institution, redirect there (or to dashboard with context)
        // For now, let's just go to dashboard, but ideally we'd set the current institution in session
        if ($institutions->count() === 1) {
            $institution = $institutions->first();
            // Optionally set session here
            // session(['current_institution_id' => $institution->id]);
            return redirect()->intended(config('fortify.home'));
        }

        // Default fallback
        return redirect()->intended(config('fortify.home'));
    }
}
