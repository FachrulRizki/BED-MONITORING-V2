<?php

namespace App\Http\Controllers;

use App\Events\BedAvailabilityUpdated;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(): View
    {
        $rooms = Room::all();

        return view('rooms.index', compact('rooms'));
    }

    public function create(): View
    {
        return view('rooms.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'             => 'required|unique:rooms,name',
            'male_capacity'    => 'required|integer|min:0',
            'female_capacity'  => 'required|integer|min:0',
        ]);

        Room::create($validated);

        broadcast(new BedAvailabilityUpdated())->toOthers();

        return redirect()->route('rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit(Room $room): View
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $validated = $request->validate([
            'name'             => "required|unique:rooms,name,{$room->id}",
            'male_capacity'    => 'required|integer|min:0',
            'female_capacity'  => 'required|integer|min:0',
            'male_occupied'    => 'nullable|integer|min:0',
            'female_occupied'  => 'nullable|integer|min:0',
        ]);

        $updateData = array_filter($validated, fn($v) => !is_null($v));
        $room->update($updateData);

        broadcast(new BedAvailabilityUpdated())->toOthers();

        return redirect()->route('rooms.index')
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();

        broadcast(new BedAvailabilityUpdated())->toOthers();

        return redirect()->route('rooms.index')
            ->with('success', 'Ruangan berhasil dihapus.');
    }
}
