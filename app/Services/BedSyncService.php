<?php

namespace App\Services;

use App\Events\BedAvailabilityUpdated;
use App\Models\Room;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BedSyncService
{
    public function sync(): void
    {
        if (!config('mjkn.enabled')) {
            return;
        }

        try {
            $data = Http::withToken(config('mjkn.api_key'))
                ->get(config('mjkn.base_url') . '/bed-availability')
                ->throw()
                ->json();

            foreach ($data['rooms'] as $apiRoom) {
                $room = Room::where('name', $apiRoom['room_name'])->first();
                if ($room) {
                    $room->update([
                        'male_occupied'   => $apiRoom['male_occupied'],
                        'female_occupied' => $apiRoom['female_occupied'],
                        'last_synced_at'  => now(),
                    ]);
                }
            }

            Broadcast::event(new BedAvailabilityUpdated());
        } catch (\Exception $e) {
            Log::error('MJKN sync failed: ' . $e->getMessage());
            // Data terakhir dipertahankan, tidak ada rollback
        }
    }
}
