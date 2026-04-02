<?php

namespace Tests\Feature;

use App\Events\BedAvailabilityUpdated;
use App\Models\AmprahanReport;
use App\Models\Room;
use App\Models\User;
use App\Notifications\NewAmprahanNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EndToEndTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: submit amprahan creates notification in DB
     * Validates: Requirements 3.5, 4.1
     */
    public function test_submit_amprahan_creates_notification_in_db(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $room = Room::create([
            'name'            => 'Ruang Mawar',
            'male_capacity'   => 10,
            'female_capacity' => 10,
        ]);

        $response = $this->actingAs($user)->post('/amprahans', [
            'room_id'              => $room->id,
            'report_time'          => '08:00',
            'shift'                => 'pagi',
            'male_patient_count'   => 3,
            'female_patient_count' => 2,
            'officer_name'         => 'Budi',
        ]);

        $response->assertRedirect();

        // Assert AmprahanReport was created in DB
        $this->assertDatabaseHas('amprahan_reports', [
            'room_id'              => $room->id,
            'shift'                => 'pagi',
            'male_patient_count'   => 3,
            'female_patient_count' => 2,
            'submitted_by'         => $user->id,
        ]);

        // Assert notification was sent to the user
        Notification::assertSentTo($user, NewAmprahanNotification::class);
    }

    /**
     * Test 2: update room dispatches BedAvailabilityUpdated event
     * Validates: Requirements 1.5
     */
    public function test_update_room_dispatches_bed_availability_updated_event(): void
    {
        Event::fake();

        $user = User::factory()->admin()->create();
        $room = Room::create([
            'name'            => 'Ruang Melati',
            'male_capacity'   => 8,
            'female_capacity' => 8,
        ]);

        $response = $this->actingAs($user)->put("/rooms/{$room->id}", [
            'name'            => 'Ruang Melati Updated',
            'male_capacity'   => 12,
            'female_capacity' => 10,
        ]);

        $response->assertRedirect();

        // Assert BedAvailabilityUpdated event was dispatched
        Event::assertDispatched(BedAvailabilityUpdated::class);
    }

    /**
     * Test 3: dashboard is accessible without auth
     * Validates: Requirements 6.1, 1.1
     */
    public function test_dashboard_is_accessible_without_auth(): void
    {
        $rooms = collect([
            Room::create(['name' => 'Ruang Anggrek', 'male_capacity' => 5, 'female_capacity' => 5]),
            Room::create(['name' => 'Ruang Dahlia',  'male_capacity' => 8, 'female_capacity' => 6]),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        foreach ($rooms as $room) {
            $response->assertSee($room->name);
        }
    }
}
