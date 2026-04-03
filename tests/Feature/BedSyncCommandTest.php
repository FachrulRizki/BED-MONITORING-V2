<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BedSyncCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_bed_sync_command_updates_room_from_aplicare_response(): void
    {
        config()->set('aplicare.enabled', true);
        config()->set('aplicare.url', 'https://example.test/aplicaresws');
        config()->set('aplicare.kodeppk', '12345');
        config()->set('aplicare.consid', 'abc');
        config()->set('aplicare.userkey', 'userkey');
        config()->set('aplicare.secretkey', 'secret');
        config()->set('aplicare.read_start', 1);
        config()->set('aplicare.read_limit', 100);

        $room = Room::create([
            'name' => 'Ruang Mawar',
            'male_capacity' => 10,
            'female_capacity' => 10,
            'male_occupied' => 0,
            'female_occupied' => 0,
        ]);

        Http::fake([
            'example.test/*' => Http::response([
                'response' => [
                    'list' => [
                        [
                            'namaruang' => 'Ruang Mawar',
                            'kapasitas' => 20,
                            'tersedia' => 15,
                            'tersediapria' => 8,
                            'tersediawanita' => 7,
                        ],
                    ],
                ],
            ], 200),
        ]);

        $this->artisan('bed:sync-mjkn')
            ->expectsOutputToContain('Matched: 1')
            ->assertSuccessful();

        $room->refresh();

        $this->assertSame(2, $room->male_occupied);
        $this->assertSame(3, $room->female_occupied);
        $this->assertNotNull($room->last_synced_at);
    }

    public function test_bed_sync_command_creates_room_when_not_found(): void
    {
        config()->set('aplicare.enabled', true);
        config()->set('aplicare.url', 'https://example.test/aplicaresws');
        config()->set('aplicare.kodeppk', '12345');
        config()->set('aplicare.consid', 'abc');
        config()->set('aplicare.userkey', 'userkey');
        config()->set('aplicare.secretkey', 'secret');
        config()->set('aplicare.read_start', 1);
        config()->set('aplicare.read_limit', 100);

        Http::fake([
            'example.test/*' => Http::response([
                'response' => [
                    'list' => [
                        [
                            'namaruang' => 'VIP',
                            'kapasitas' => 10,
                            'tersedia' => 6,
                            'tersediapria' => 4,
                            'tersediawanita' => 2,
                        ],
                    ],
                ],
            ], 200),
        ]);

        $this->artisan('bed:sync-mjkn')
            ->expectsOutputToContain('Created: 1')
            ->assertSuccessful();

        $this->assertDatabaseHas('rooms', [
            'name' => 'VIP',
        ]);
    }

    public function test_bed_sync_command_merges_duplicate_room_names_from_aplicare_response(): void
    {
        config()->set('aplicare.enabled', true);
        config()->set('aplicare.url', 'https://example.test/aplicaresws');
        config()->set('aplicare.kodeppk', '12345');
        config()->set('aplicare.consid', 'abc');
        config()->set('aplicare.userkey', 'userkey');
        config()->set('aplicare.secretkey', 'secret');
        config()->set('aplicare.read_start', 1);
        config()->set('aplicare.read_limit', 100);

        Http::fake([
            'example.test/*' => Http::response([
                'response' => [
                    'list' => [
                        [
                            'namaruang' => 'PERINATOLOGI',
                            'kapasitas' => 4,
                            'tersedia' => 2,
                            'tersediapria' => 1,
                            'tersediawanita' => 1,
                        ],
                        [
                            'namaruang' => 'PERINATOLOGI',
                            'kapasitas' => 4,
                            'tersedia' => 0,
                            'tersediapria' => 0,
                            'tersediawanita' => 0,
                        ],
                    ],
                ],
            ], 200),
        ]);

        $this->artisan('bed:sync-mjkn')->assertSuccessful();

        $this->assertDatabaseCount('rooms', 1);
        $this->assertDatabaseHas('rooms', [
            'name' => 'PERINATOLOGI',
            'male_capacity' => 4,
            'female_capacity' => 4,
        ]);
    }
}
