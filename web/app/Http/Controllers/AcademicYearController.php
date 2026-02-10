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

        return Inertia::render('Yayasan/AcademicYear/Index', [
            'academicYears' => $academicYears,
        ]);
    }

    /**
     * Show the form for creating a new academic year.
     */
    public function create()
    {
        return Inertia::render('Yayasan/AcademicYear/Create');
    }

    /**
     * Store a newly created academic year in storage.
     */
    public function store(StoreAcademicYearRequest $request)
    {
        $validated = $request->validated();

        // Constraint "hanya satu aktif" sudah di-enforce oleh model (saving event)
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
        return Inertia::render('Yayasan/AcademicYear/Edit', [
            'academicYear' => $academicYear,
        ]);
    }

    /**
     * Update the specified academic year in storage.
     */
    public function update(UpdateAcademicYearRequest $request, AcademicYear $academicYear)
    {
        $validated = $request->validated();

        // Constraint "hanya satu aktif" sudah di-enforce oleh model (saving event)
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
