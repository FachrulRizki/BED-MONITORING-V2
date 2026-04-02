<?php

namespace App\Events;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BedAvailabilityUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct()
    {
        //
    }

    public function broadcastOn(): Channel
    {
        return new Channel('bed-availability');
    }

    public function broadcastWith(): array
    {
        return [
            'rooms' => Room::all(['id', 'name', 'male_capacity', 'male_occupied', 'female_capacity', 'female_occupied']),
        ];
    }
}
