<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WaliController extends Controller
{
    /**
     * Display the wali santri dashboard.
     * Route: /wali/{student}/dashboard
     */
    public function dashboard(): Response
    {
        $student = current_student();

        return Inertia::render('Wali/Dashboard', [
            'student' => $student ? [
                'id' => $student->id ?? null,
                'public_id' => $student->public_id ?? null,
                'name' => $student->name ?? null,
            ] : null,
        ]);
    }

    /**
     * Display student academic information.
     * Route: /wali/{student}/academic
     */
    public function academic(): Response
    {
        $student = current_student();

        return Inertia::render('Wali/Academic', [
            'student' => $student,
        ]);
    }

    /**
     * Display student finance/billing information.
     * Route: /wali/{student}/finance
     */
    public function finance(): Response
    {
        $student = current_student();

        return Inertia::render('Wali/Finance', [
            'student' => $student,
        ]);
    }
}
