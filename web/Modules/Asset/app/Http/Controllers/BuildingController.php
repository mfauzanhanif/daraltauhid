<?php

namespace Modules\Asset\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Asset\Models\Building;
use Modules\Asset\Http\Requests\StoreBuildingRequest;

class BuildingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Building::with(['institution', 'rooms'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            })
            ->latest();

        $buildings = $query->paginate(15)->withQueryString();

        return view('asset::buildings.index', compact('buildings'));
    }

    public function create(): View
    {
        return view('asset::buildings.create');
    }

    public function store(StoreBuildingRequest $request): RedirectResponse
    {
        Building::create($request->validated());

        return redirect()
            ->route('asset.buildings.index')
            ->with('success', 'Gedung berhasil ditambahkan.');
    }

    public function show(Building $building): View
    {
        $building->load(['institution', 'rooms']);

        return view('asset::buildings.show', compact('building'));
    }

    public function edit(Building $building): View
    {
        return view('asset::buildings.edit', compact('building'));
    }

    public function update(StoreBuildingRequest $request, Building $building): RedirectResponse
    {
        $building->update($request->validated());

        return redirect()
            ->route('asset.buildings.show', $building)
            ->with('success', 'Gedung berhasil diperbarui.');
    }

    public function destroy(Building $building): RedirectResponse
    {
        $building->delete();

        return redirect()
            ->route('asset.buildings.index')
            ->with('success', 'Gedung berhasil dihapus.');
    }
}
