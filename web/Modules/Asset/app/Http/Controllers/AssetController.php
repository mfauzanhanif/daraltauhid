<?php

namespace Modules\Asset\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Asset\Models\Asset;
use Modules\Asset\Models\AssetCategory;
use Modules\Asset\Models\Room;
use Modules\Asset\Http\Requests\StoreAssetRequest;

class AssetController extends Controller
{
    public function index(Request $request): View
    {
        $query = Asset::with(['category', 'room.building', 'institution'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('code', 'like', "%{$search}%")
                          ->orWhere('brand', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->inCategory($request->category);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->status($request->status);
            })
            ->when($request->filled('condition'), function ($q) use ($request) {
                $q->condition($request->condition);
            })
            ->when($request->filled('room'), function ($q) use ($request) {
                $q->inRoom($request->room);
            })
            ->latest();

        $assets = $query->paginate(15)->withQueryString();
        $categories = AssetCategory::all();

        return view('asset::assets.index', compact('assets', 'categories'));
    }

    public function create(): View
    {
        $categories = AssetCategory::all();
        $rooms = Room::with('building')->get();

        return view('asset::assets.create', compact('categories', 'rooms'));
    }

    public function store(StoreAssetRequest $request): RedirectResponse
    {
        $asset = Asset::create($request->validated());

        return redirect()
            ->route('asset.assets.show', $asset)
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    public function show(Asset $asset): View
    {
        $asset->load(['category', 'room.building', 'institution', 'mutations', 'lendings', 'maintenances']);

        return view('asset::assets.show', compact('asset'));
    }

    public function edit(Asset $asset): View
    {
        $categories = AssetCategory::all();
        $rooms = Room::with('building')->get();

        return view('asset::assets.edit', compact('asset', 'categories', 'rooms'));
    }

    public function update(StoreAssetRequest $request, Asset $asset): RedirectResponse
    {
        $asset->update($request->validated());

        return redirect()
            ->route('asset.assets.show', $asset)
            ->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Asset $asset): RedirectResponse
    {
        $asset->delete();

        return redirect()
            ->route('asset.assets.index')
            ->with('success', 'Aset berhasil dihapus.');
    }
}
