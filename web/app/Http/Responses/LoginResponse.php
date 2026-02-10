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
        $loginType = in_array($request->input('login_type'), ['staff', 'wali'], true)
            ? $request->input('login_type')
            : 'staff';

        // Store login type in session for reference
        session(['login_type' => $loginType]);

        if ($loginType === 'wali') {
            // Wali flow: redirect to student selection
            $students = $user->getStudents();

            if ($students->count() === 1) {
                $student = $students->first();
                session(['current_student_id' => $student->public_id]);

                return redirect()->to("/wali/{$student->public_id}/dashboard");
            }

            return redirect()->route('student.select');
        }

        // Staff/PTK flow: redirect to institution selection
        $institutions = $user->getInstitutions();
        $isGlobalAdmin = $user->isGlobalAdmin();

        // If only one institution and not global admin, go directly
        if ($institutions->count() === 1 && ! $isGlobalAdmin) {
            $institution = $institutions->first();
            session(['current_institution_id' => $institution->id]);

            return redirect()->to($institution->getDashboardUrl());
        }

        // If global admin with no institution roles, go to admin dashboard
        if ($isGlobalAdmin && $institutions->isEmpty()) {
            return redirect()->to(root_dashboard_url());
        }

        // Multiple institutions or global admin with institution roles -> selection page
        return redirect()->route('institution.select');
    }
}
