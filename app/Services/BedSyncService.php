<?php

namespace App\Services;

use App\Events\BedAvailabilityUpdated;
use App\Models\Room;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BedSyncService
{
    public function sync(): void
    {
        if (!config('aplicare.enabled')) {
            return;
        }

        try {
            $consid    = config('aplicare.consid');
            $secretkey = config('aplicare.secretkey');
            $userkey   = config('aplicare.userkey');
            $timestamp = now()->timestamp;

            $signature = hash_hmac('sha256', $consid . '&' . $timestamp, $secretkey);

            $response = Http::withHeaders([
                'X-cons-id'   => $consid,
                'X-timestamp' => (string) $timestamp,
                'X-signature' => $signature,
                'user_key'    => $userkey,
            ])->get(config('aplicare.url') . '/antrean/ketersediaan-tempat-tidur')
              ->throw()
              ->json();

            $list = $response['response']['list'] ?? [];

            foreach ($list as $item) {
                $roomName = $item['namaPoli'] ?? $item['namaRuang'] ?? null;
                if (!$roomName) {
                    continue;
                }

                $room = Room::where('name', $roomName)->first();
                if ($room) {
                    $room->update([
                        'male_occupied'  => $item['terisi'] ?? $room->male_occupied,
                        'last_synced_at' => now(),
                    ]);
                }
            }

            event(new BedAvailabilityUpdated());
        } catch (\Exception $e) {
            Log::error('Aplicare sync failed: ' . $e->getMessage());
            // Data terakhir dipertahankan, tidak ada rollback
        }
    }
}
