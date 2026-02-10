<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFiscalPeriodRequest;
use App\Http\Requests\UpdateFiscalPeriodRequest;
use App\Models\FiscalPeriod;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FiscalPeriodController extends Controller
{
    /**
     * Display a listing of fiscal periods.
     */
    public function index()
    {
        $fiscalPeriods = FiscalPeriod::orderBy('start_date', 'desc')->get();

        return Inertia::render('Yayasan/FiscalPeriod/Index', [
            'fiscalPeriods' => $fiscalPeriods,
        ]);
    }

    /**
     * Show the form for creating a new fiscal period.
     */
    public function create()
    {
        return Inertia::render('Yayasan/FiscalPeriod/Create');
    }

    /**
     * Store a newly created fiscal period in storage.
     */
    public function store(StoreFiscalPeriodRequest $request)
    {
        $validated = $request->validated();

        if ($validated['is_active']) {
            FiscalPeriod::where('is_active', true)->update(['is_active' => false]);
        }

        FiscalPeriod::create($validated);

        return redirect()
            ->route('fiscal-periods.index')
            ->with('success', 'Periode fiskal berhasil ditambahkan.');
    }

    /**
     * Display the specified fiscal period.
     */
    public function show(FiscalPeriod $fiscalPeriod)
    {
        return redirect()->route('fiscal-periods.edit', $fiscalPeriod);
    }

    /**
     * Show the form for editing the specified fiscal period.
     */
    public function edit(FiscalPeriod $fiscalPeriod)
    {
        return Inertia::render('Yayasan/FiscalPeriod/Edit', [
            'fiscalPeriod' => $fiscalPeriod,
        ]);
    }

    /**
     * Update the specified fiscal period in storage.
     */
    public function update(UpdateFiscalPeriodRequest $request, FiscalPeriod $fiscalPeriod)
    {
        $validated = $request->validated();

        if ($validated['is_active'] && !$fiscalPeriod->is_active) {
            FiscalPeriod::where('id', '!=', $fiscalPeriod->id)->update(['is_active' => false]);
        }

        $fiscalPeriod->update($validated);

        return redirect()
            ->route('fiscal-periods.index')
            ->with('success', 'Periode fiskal berhasil diperbarui.');
    }

    /**
     * Remove the specified fiscal period from storage.
     */
    public function destroy(FiscalPeriod $fiscalPeriod)
    {
        if ($fiscalPeriod->isAudited()) {
            return back()->with('error', 'Tidak dapat menghapus periode fiskal yang sudah diaudit.');
        }

        // Prevent deletion if transactions exist (this logic belongs to a Service or Model check,
        // but adding a placeholder here)
        // if ($fiscalPeriod->transactions()->exists()) ...

        $fiscalPeriod->delete(); // Permanent delete as per migration

        return redirect()
            ->route('fiscal-periods.index')
            ->with('success', 'Periode fiskal berhasil dihapus.');
    }

    /**
     * Close the specified fiscal period.
     */
    public function close(FiscalPeriod $fiscalPeriod)
    {
        $fiscalPeriod->close();

        return back()->with('success', 'Periode fiskal ditutup.');
    }

    /**
     * Reopen the specified fiscal period.
     */
    public function reopen(FiscalPeriod $fiscalPeriod)
    {
        $fiscalPeriod->reopen();

        return back()->with('success', 'Periode fiskal dibuka kembali.');
    }

    /**
     * Mark the specified fiscal period as audited.
     */
    public function markAsAudited(FiscalPeriod $fiscalPeriod)
    {
        $fiscalPeriod->markAsAudited();

        return back()->with('success', 'Periode fiskal ditandai telah diaudit.');
    }
}
