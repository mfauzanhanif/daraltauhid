<?php

namespace Modules\Asset\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Modules\Asset\Models\Asset;
use Modules\Asset\Models\AssetLending;
use Modules\Asset\Http\Requests\StoreLendingRequest;

class AssetLendingController extends Controller
{
    public function index(Request $request): View
    {
        $query = AssetLending::with(['asset', 'borrower', 'approvedBy', 'institution'])
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->status($request->status);
            })
            ->latest();

        $lendings = $query->paginate(15)->withQueryString();

        return view('asset::lendings.index', compact('lendings'));
    }

    public function create(): View
    {
        $assets = Asset::active()->get();

        return view('asset::lendings.create', compact('assets'));
    }

    public function store(StoreLendingRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = 'REQUESTED';

        AssetLending::create($data);

        return redirect()
            ->route('asset.lendings.index')
            ->with('success', 'Permintaan peminjaman berhasil diajukan.');
    }

    public function show(AssetLending $lending): View
    {
        $lending->load(['asset', 'borrower', 'approvedBy', 'institution']);

        return view('asset::lendings.show', compact('lending'));
    }

    /**
     * Approve a lending request.
     */
    public function approve(AssetLending $lending): RedirectResponse
    {
        $lending->update([
            'status' => 'APPROVED',
            'approved_by_user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('asset.lendings.show', $lending)
            ->with('success', 'Peminjaman disetujui.');
    }

    /**
     * Reject a lending request.
     */
    public function reject(AssetLending $lending): RedirectResponse
    {
        $lending->update([
            'status' => 'REJECTED',
            'approved_by_user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('asset.lendings.show', $lending)
            ->with('success', 'Peminjaman ditolak.');
    }

    /**
     * Mark as picked up (on loan).
     */
    public function pickup(AssetLending $lending): RedirectResponse
    {
        $lending->update([
            'status' => 'ON_LOAN',
            'borrowed_at' => now(),
        ]);

        // Update asset status
        $lending->asset->update(['status' => 'BORROWED']);

        return redirect()
            ->route('asset.lendings.show', $lending)
            ->with('success', 'Aset sudah dipinjam.');
    }

    /**
     * Mark as returned.
     */
    public function return(Request $request, AssetLending $lending): RedirectResponse
    {
        $request->validate([
            'notes_condition_after' => ['nullable', 'string', 'max:1000'],
        ]);

        $lending->update([
            'status' => 'RETURNED',
            'returned_at' => now(),
            'notes_condition_after' => $request->notes_condition_after,
        ]);

        // Update asset status
        $lending->asset->update(['status' => 'ACTIVE']);

        return redirect()
            ->route('asset.lendings.show', $lending)
            ->with('success', 'Aset sudah dikembalikan.');
    }
}
