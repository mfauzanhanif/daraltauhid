<?php

namespace Modules\Asset\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Asset\Models\Room;
use Modules\Asset\Models\Building;
use Modules\Asset\Http\Requests\StoreRoomRequest;

class RoomController extends Controller
{
    public function index(Request $request): View
    {
        $query = Room::with(['institution', 'building', 'picUser'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            })
            ->when($request->filled('building'), function ($q) use ($request) {
                $q->inBuilding($request->building);
            })
            ->latest();

        $rooms = $query->paginate(15)->withQueryString();
        $buildings = Building::all();

        return view('asset::rooms.index', compact('rooms', 'buildings'));
    }

    public function create(): View
    {
        $buildings = Building::all();

        return view('asset::rooms.create', compact('buildings'));
    }

    public function store(StoreRoomRequest $request): RedirectResponse
    {
        Room::create($request->validated());

        return redirect()
            ->route('asset.rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function show(Room $room): View
    {
        $room->load(['institution', 'building', 'picUser', 'assets']);

        return view('asset::rooms.show', compact('room'));
    }

    public function edit(Room $room): View
    {
        $buildings = Building::all();

        return view('asset::rooms.edit', compact('room', 'buildings'));
    }

    public function update(StoreRoomRequest $request, Room $room): RedirectResponse
    {
        $room->update($request->validated());

        return redirect()
            ->route('asset.rooms.show', $room)
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();

        return redirect()
            ->route('asset.rooms.index')
            ->with('success', 'Ruangan berhasil dihapus.');
    }
}
