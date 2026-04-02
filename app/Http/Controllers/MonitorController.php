<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\View\View;

class MonitorController extends Controller
{
    public function index(): View
    {
        $rooms = Room::all()->map(function ($room) {
            $totalCapacity = $room->male_capacity + $room->female_capacity;
            $totalOccupied = $room->male_occupied + $room->female_occupied;
            $percentage    = $totalCapacity > 0
                ? round(($totalOccupied / $totalCapacity) * 100, 1)
                : 0;
            $isFull        = $totalOccupied >= $totalCapacity;

            return array_merge($room->toArray(), [
                'usage_percentage' => $percentage,
                'is_full'          => $isFull,
                'status_color'     => $isFull ? 'red' : 'green',
            ]);
        });

        return view('monitor.index', compact('rooms'));
    }
}
