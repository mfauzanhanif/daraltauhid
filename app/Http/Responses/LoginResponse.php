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
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $institutions = $user->getInstitutions();

        // Global Admin: redirect ke halaman select institution
        if ($user->isGlobalAdmin()) {
            return redirect()->route('institution.select');
        }

        // User dengan banyak institusi: redirect ke halaman select
        if ($institutions->count() > 1) {
            return redirect()->route('institution.select');
        }

        // User dengan 1 institusi: auto-set session dan redirect ke dashboard
        if ($institutions->count() === 1) {
            $institution = $institutions->first();
            session(['current_institution_id' => $institution->id]);

            return redirect()->intended(config('fortify.home'));
        }

        // User tanpa institusi (seharusnya tidak terjadi)
        return redirect()->intended(config('fortify.home'));
    }
}
