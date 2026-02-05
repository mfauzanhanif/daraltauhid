<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAcademicYearRequest;
use App\Http\Requests\UpdateAcademicYearRequest;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of academic years.
     */
    public function index()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        return Inertia::render('AcademicYear/Index', [
            'academicYears' => $academicYears,
        ]);
    }

    /**
     * Show the form for creating a new academic year.
     */
    public function create()
    {
        return Inertia::render('AcademicYear/Create');
    }

    /**
     * Store a newly created academic year in storage.
     */
    public function store(StoreAcademicYearRequest $request)
    {
        $validated = $request->validated();
        
        if ($validated['is_active']) {
            // If new year is active, deactivate others (handled by model logic or observer usually, 
            // but for now explicit handling or reliant on setAsActive)
            // Ideally, setAsActive logic should be used if 'is_active' is true.
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        AcademicYear::create($validated);

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    /**
     * Display the specified academic year.
     */
    public function show(AcademicYear $academicYear)
    {
        // Usually not needed for simple lists, but can show details/periods
        return redirect()->route('academic-years.edit', $academicYear);
    }

    /**
     * Show the form for editing the specified academic year.
     */
    public function edit(AcademicYear $academicYear)
    {
        return Inertia::render('AcademicYear/Edit', [
            'academicYear' => $academicYear,
        ]);
    }

    /**
     * Update the specified academic year in storage.
     */
    public function update(UpdateAcademicYearRequest $request, AcademicYear $academicYear)
    {
        $validated = $request->validated();

        if ($validated['is_active'] && !$academicYear->is_active) {
             AcademicYear::where('id', '!=', $academicYear->id)->update(['is_active' => false]);
        }

        $academicYear->update($validated);

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified academic year from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        if ($academicYear->academicPeriods()->exists()) {
             return back()->with('error', 'Tidak dapat menghapus tahun ajaran yang memiliki semester aktif/terdaftar.');
        }

        $academicYear->delete();

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    /**
     * Set the specified academic year as active.
     */
    public function setActive(AcademicYear $academicYear)
    {
        $academicYear->setAsActive();

        return back()->with('success', 'Tahun ajaran ' . $academicYear->name . ' diaktifkan.');
    }
}
