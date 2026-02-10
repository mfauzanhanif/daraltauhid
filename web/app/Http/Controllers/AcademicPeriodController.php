<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcademicPeriodRequest;
use App\Http\Requests\UpdateAcademicPeriodRequest;
use App\Models\AcademicPeriod;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AcademicPeriodController extends Controller
{
    /**
     * Display a listing of academic periods.
     */
    public function index()
    {
        $academicPeriods = AcademicPeriod::with('academicYear')
            ->orderBy('start_date', 'desc')
            ->get();

        return Inertia::render('Yayasan/Semester/Index', [
            'academicPeriods' => $academicPeriods,
        ]);
    }

    /**
     * Show the form for creating a new academic period.
     */
    public function create()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get(['id', 'name']);

        return Inertia::render('Yayasan/Semester/Create', [
            'academicYears' => $academicYears,
        ]);
    }

    /**
     * Store a newly created academic period in storage.
     */
    public function store(StoreAcademicPeriodRequest $request)
    {
        $validated = $request->validated();

        // Remove validator implementation details from data
        unset($validated['_unique_check']);

        if ($validated['is_active']) {
            // Deactivate other periods in same year
            AcademicPeriod::where('academic_year_id', $validated['academic_year_id'])
                ->update(['is_active' => false]);
        }

        AcademicPeriod::create($validated);

        return redirect()
            ->route('semesters.index')
            ->with('success', 'Semester berhasil ditambahkan.');
    }

    /**
     * Display the specified academic period.
     */
    public function show(AcademicPeriod $academicPeriod)
    {
        return redirect()->route('semesters.edit', $academicPeriod);
    }

    /**
     * Show the form for editing the specified academic period.
     */
    public function edit(AcademicPeriod $academicPeriod)
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get(['id', 'name']);

        return Inertia::render('Yayasan/Semester/Edit', [
            'academicPeriod' => $academicPeriod,
            'academicYears' => $academicYears,
        ]);
    }

    /**
     * Update the specified academic period in storage.
     */
    public function update(UpdateAcademicPeriodRequest $request, AcademicPeriod $academicPeriod)
    {
        $validated = $request->validated();

        if ($validated['is_active'] && !$academicPeriod->is_active) {
            // Deactivate other periods in same year
            AcademicPeriod::where('academic_year_id', $validated['academic_year_id'])
                ->where('id', '!=', $academicPeriod->id)
                ->update(['is_active' => false]);
        }

        $academicPeriod->update($validated);

        return redirect()
            ->route('semesters.index')
            ->with('success', 'Semester berhasil diperbarui.');
    }

    /**
     * Remove the specified academic period from storage.
     */
    public function destroy(AcademicPeriod $academicPeriod)
    {
        $academicPeriod->delete(); // Note: This is permanent delete as per model (no SoftDeletes)

        return redirect()
            ->route('semesters.index')
            ->with('success', 'Semester berhasil dihapus.');
    }

    /**
     * Set the specified academic period as active.
     */
    public function setActive(AcademicPeriod $academicPeriod)
    {
        $academicPeriod->setAsActive();

        return back()->with('success', 'Semester ' . $academicPeriod->name . ' berhasil diaktifkan.');
    }
}
