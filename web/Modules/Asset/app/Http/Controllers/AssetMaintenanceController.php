<?php

namespace Modules\Asset\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Asset\Models\Asset;
use Modules\Asset\Models\AssetMaintenance;
use Modules\Asset\Http\Requests\StoreMaintenanceRequest;

class AssetMaintenanceController extends Controller
{
    public function index(Request $request): View
    {
        $query = AssetMaintenance::with(['asset', 'reportedBy', 'institution'])
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->status($request->status);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('ticket_number', 'like', "%{$request->search}%");
            })
            ->latest();

        $maintenances = $query->paginate(15)->withQueryString();

        return view('asset::maintenances.index', compact('maintenances'));
    }

    public function create(): View
    {
        $assets = Asset::active()->get();

        return view('asset::maintenances.create', compact('assets'));
    }

    public function store(StoreMaintenanceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['reported_by_user_id'] = Auth::id();
        $data['status'] = 'REPORTED';

        // Handle evidence photo upload
        if ($request->hasFile('evidence_photo')) {
            $data['evidence_photo_path'] = $request->file('evidence_photo')->store('maintenances/evidence', 'public');
        }

        $maintenance = AssetMaintenance::create($data);

        // Update asset status to maintenance
        $maintenance->asset->update(['status' => 'MAINTENANCE']);

        return redirect()
            ->route('asset.maintenances.show', $maintenance)
            ->with('success', 'Laporan kerusakan berhasil diajukan.');
    }

    public function show(AssetMaintenance $maintenance): View
    {
        $maintenance->load(['asset', 'reportedBy', 'institution']);

        return view('asset::maintenances.show', compact('maintenance'));
    }

    /**
     * Start review process.
     */
    public function review(AssetMaintenance $maintenance): RedirectResponse
    {
        $maintenance->update(['status' => 'IN_REVIEW']);

        return redirect()
            ->route('asset.maintenances.show', $maintenance)
            ->with('success', 'Sedang dalam review.');
    }

    /**
     * Start repair process.
     */
    public function startRepair(Request $request, AssetMaintenance $maintenance): RedirectResponse
    {
        $request->validate([
            'technician_name' => ['nullable', 'string', 'max:255'],
        ]);

        $maintenance->update([
            'status' => 'IN_REPAIR',
            'repair_started_at' => now(),
            'technician_name' => $request->technician_name,
        ]);

        return redirect()
            ->route('asset.maintenances.show', $maintenance)
            ->with('success', 'Perbaikan dimulai.');
    }

    /**
     * Resolve repair.
     */
    public function resolve(Request $request, AssetMaintenance $maintenance): RedirectResponse
    {
        $request->validate([
            'repair_cost' => ['nullable', 'numeric', 'min:0'],
            'repair_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $maintenance->update([
            'status' => 'RESOLVED',
            'repair_finished_at' => now(),
            'repair_cost' => $request->repair_cost ?? 0,
            'repair_notes' => $request->repair_notes,
        ]);

        // Update asset status back to active
        $maintenance->asset->update(['status' => 'ACTIVE']);

        return redirect()
            ->route('asset.maintenances.show', $maintenance)
            ->with('success', 'Perbaikan selesai.');
    }

    /**
     * Mark as irreparable.
     */
    public function irreparable(AssetMaintenance $maintenance): RedirectResponse
    {
        $maintenance->update([
            'status' => 'IRREPARABLE',
            'repair_finished_at' => now(),
        ]);

        return redirect()
            ->route('asset.maintenances.show', $maintenance)
            ->with('warning', 'Aset ditandai tidak dapat diperbaiki.');
    }
}
