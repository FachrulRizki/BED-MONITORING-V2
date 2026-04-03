<?php

namespace App\Services;

use App\Events\BedAvailabilityUpdated;
use App\Models\Room;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class BedSyncService
{
    public function sync(): array
    {
        if (!config('aplicare.enabled')) {
            return [
                'ok' => false,
                'message' => 'Aplicare integration is disabled.',
                'updated' => 0,
                'matched' => 0,
                'created' => 0,
                'total' => 0,
                'unique' => 0,
                'duplicates_merged' => 0,
            ];
        }

        try {
            $baseUrl   = rtrim((string) config('aplicare.url'), '/');
            $kodeppk   = trim((string) config('aplicare.kodeppk'));
            $consid    = config('aplicare.consid');
            $secretkey = config('aplicare.secretkey');
            $userkey   = config('aplicare.userkey');
            $start     = max(1, (int) config('aplicare.read_start', 1));
            $limit     = max(1, (int) config('aplicare.read_limit', 100));
            $timeout   = max(5, (int) config('aplicare.timeout', 20));
            $timestamp = now()->getTimestamp();

            if ($baseUrl === '' || $kodeppk === '' || blank($consid) || blank($secretkey) || blank($userkey)) {
                throw new RuntimeException('Configurasi Aplicare belum lengkap. Pastikan URL, KODEPPK, CONSID, USERKEY, dan SECRETKEY terisi.');
            }

            $signature = base64_encode(hash_hmac('sha256', $consid.'&'.$timestamp, $secretkey, true));
            $endpoint = "{$baseUrl}/rest/bed/read/{$kodeppk}/{$start}/{$limit}";

            $response = Http::withHeaders([
                'X-cons-id'   => $consid,
                'X-timestamp' => (string) $timestamp,
                'X-signature' => $signature,
                'user_key'    => $userkey,
                'Accept'      => 'application/json',
            ])
              ->timeout($timeout)
              ->retry(2, 500, throw: false)
              ->get($endpoint)
              ->throw()
              ->json();

            $list = $response['response']['list'] ?? [];
            $normalizedList = $this->mergeDuplicateRooms($list);
            $updated = 0;
            $matched = 0;
            $created = 0;
            $rooms = Room::all();

            foreach ($normalizedList as $item) {
                $roomName = $this->extractRoomName($item);

                if (!$roomName) {
                    continue;
                }

                $room = $rooms->first(function (Room $room) use ($roomName) {
                    return mb_strtolower(trim($room->name)) === mb_strtolower(trim($roomName));
                });

                $payload = $this->buildRoomPayload($item, $room);

                if ($room) {
                    $matched++;
                    $room->update($payload);
                    $updated++;
                } else {
                    $room = Room::create(array_merge($payload, [
                        'name' => $roomName,
                    ]));
                    $rooms->push($room);
                    $created++;
                }
            }

            event(new BedAvailabilityUpdated());

            Log::info('Aplicare sync finished.', [
                'endpoint' => $endpoint,
                'total' => count($list),
                'unique' => count($normalizedList),
                'duplicates_merged' => max(0, count($list) - count($normalizedList)),
                'matched' => $matched,
                'updated' => $updated,
                'created' => $created,
            ]);

            return [
                'ok' => true,
                'message' => 'Aplicare sync finished.',
                'updated' => $updated,
                'matched' => $matched,
                'created' => $created,
                'total' => count($list),
                'unique' => count($normalizedList),
                'duplicates_merged' => max(0, count($list) - count($normalizedList)),
            ];
        } catch (\Exception $e) {
            Log::error('Aplicare sync failed: '.$e->getMessage(), [
                'exception' => get_class($e),
            ]);

            return [
                'ok' => false,
                'message' => $e->getMessage(),
                'updated' => 0,
                'matched' => 0,
                'created' => 0,
                'total' => 0,
                'unique' => 0,
                'duplicates_merged' => 0,
            ];
        }
    }

    protected function extractRoomName(array $item): ?string
    {
        return $item['namaRuang']
            ?? $item['namaruang']
            ?? $item['namaPoli']
            ?? $item['namapoli']
            ?? null;
    }

    protected function buildRoomPayload(array $item, ?Room $existingRoom = null): array
    {
        $totalCapacity = max(0, (int) ($item['kapasitas'] ?? 0));
        $totalAvailable = max(0, (int) ($item['tersedia'] ?? 0));
        $maleAvailable = isset($item['tersediapria']) ? max(0, (int) $item['tersediapria']) : null;
        $femaleAvailable = isset($item['tersediawanita']) ? max(0, (int) $item['tersediawanita']) : null;
        $occupiedTotal = max(0, $totalCapacity - $totalAvailable);

        if ($existingRoom) {
            $payload = [
                'last_synced_at' => now(),
            ];

            if ($maleAvailable !== null) {
                $payload['male_occupied'] = max(0, $existingRoom->male_capacity - $maleAvailable);
            }

            if ($femaleAvailable !== null) {
                $payload['female_occupied'] = max(0, $existingRoom->female_capacity - $femaleAvailable);
            }

            if (!isset($payload['male_occupied'], $payload['female_occupied']) && $totalCapacity > 0) {
                $currentCapacity = $existingRoom->male_capacity + $existingRoom->female_capacity;

                if ($currentCapacity > 0) {
                    $maleRatio = $existingRoom->male_capacity / $currentCapacity;
                    $maleOccupied = (int) round($occupiedTotal * $maleRatio);
                    $femaleOccupied = max(0, $occupiedTotal - $maleOccupied);

                    $payload['male_occupied'] = min($existingRoom->male_capacity, $maleOccupied);
                    $payload['female_occupied'] = min($existingRoom->female_capacity, $femaleOccupied);
                }
            }

            return $payload;
        }

        $maleCapacity = 0;
        $femaleCapacity = 0;

        if ($maleAvailable !== null && $femaleAvailable !== null && ($maleAvailable + $femaleAvailable) > 0) {
            $availableSplit = $maleAvailable + $femaleAvailable;
            $remainingCapacity = max(0, $totalCapacity - $availableSplit);

            $maleShare = $availableSplit > 0 ? $maleAvailable / $availableSplit : 1;
            $extraMale = (int) round($remainingCapacity * $maleShare);
            $extraFemale = max(0, $remainingCapacity - $extraMale);

            $maleCapacity = $maleAvailable + $extraMale;
            $femaleCapacity = $femaleAvailable + $extraFemale;
        } elseif ($totalCapacity > 0) {
            $maleCapacity = $totalCapacity;
            $femaleCapacity = 0;
        }

        $maleOccupied = 0;
        $femaleOccupied = 0;

        if ($maleCapacity + $femaleCapacity > 0 && $occupiedTotal > 0) {
            $maleRatio = $maleCapacity / max(1, ($maleCapacity + $femaleCapacity));
            $maleOccupied = min($maleCapacity, (int) round($occupiedTotal * $maleRatio));
            $femaleOccupied = min($femaleCapacity, max(0, $occupiedTotal - $maleOccupied));
        }

        return [
            'male_capacity' => $maleCapacity,
            'female_capacity' => $femaleCapacity,
            'male_occupied' => $maleOccupied,
            'female_occupied' => $femaleOccupied,
            'last_synced_at' => now(),
        ];
    }

    protected function mergeDuplicateRooms(array $items): array
    {
        return collect($items)
            ->filter(fn (array $item) => filled($this->extractRoomName($item)))
            ->groupBy(fn (array $item) => mb_strtolower(trim((string) $this->extractRoomName($item))))
            ->map(function (Collection $group): array {
                $first = $group->first();

                $first['kapasitas'] = $group->sum(fn (array $item) => (int) ($item['kapasitas'] ?? 0));
                $first['tersedia'] = $group->sum(fn (array $item) => (int) ($item['tersedia'] ?? 0));
                $first['tersediapria'] = $group->sum(fn (array $item) => (int) ($item['tersediapria'] ?? 0));
                $first['tersediawanita'] = $group->sum(fn (array $item) => (int) ($item['tersediawanita'] ?? 0));

                return $first;
            })
            ->values()
            ->all();
    }
}
