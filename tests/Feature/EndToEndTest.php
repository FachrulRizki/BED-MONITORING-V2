<?php

namespace Tests\Feature;

use App\Events\AmprahanNotificationCreated;
use App\Events\BedAvailabilityUpdated;
use App\Models\AmprahanReport;
use App\Models\Room;
use App\Models\User;
use App\Notifications\NewAmprahanNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
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
        Event::fake([AmprahanNotificationCreated::class]);
        Storage::fake('public');

        $user = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $room = Room::create([
            'name'            => 'Ruang Mawar',
            'male_capacity'   => 10,
            'female_capacity' => 10,
        ]);

        $response = $this->actingAs($user)->post('/amprahans', [
            'room_id'              => $room->id,
            'report_time'          => '08:00',
            'shift'                => 'pagi',
            'next_shift'           => 'sore',
            'male_patient_count'   => 3,
            'female_patient_count' => 2,
            'officer_name'         => 'Budi',
            'next_officer_name'    => 'Siti',
            'action_plan'          => 'Observasi ulang sebelum serah terima.',
            'image'                => UploadedFile::fake()->image('amprahan.jpg'),
        ]);

        $response->assertRedirect();

        // Assert AmprahanReport was created in DB
        $this->assertDatabaseHas('amprahan_reports', [
            'room_id'              => $room->id,
            'shift'                => 'pagi',
            'next_shift'           => 'sore',
            'male_patient_count'   => 3,
            'female_patient_count' => 2,
            'officer_name'         => 'Budi',
            'next_officer_name'    => 'Siti',
            'action_plan'          => 'Observasi ulang sebelum serah terima.',
            'submitted_by'         => $user->id,
        ]);

        // Assert notification was sent to the user
        Notification::assertSentTo($user, NewAmprahanNotification::class);
        Notification::assertSentTo($admin, NewAmprahanNotification::class);
        Event::assertDispatchedTimes(AmprahanNotificationCreated::class, 2);
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

    public function test_amprahan_print_can_be_filtered_by_date_range(): void
    {
        $user = User::factory()->create();
        $room = Room::create([
            'name' => 'Ruang Filter',
            'male_capacity' => 5,
            'female_capacity' => 5,
        ]);

        $insideRange = AmprahanReport::create([
            'room_id' => $room->id,
            'submitted_by' => $user->id,
            'report_time' => '08:00',
            'shift' => 'pagi',
            'next_shift' => 'sore',
            'officer_name' => 'Budi',
            'next_officer_name' => 'Siti',
            'male_patient_count' => 1,
            'female_patient_count' => 2,
            'action_plan' => null,
            'image_path' => 'amprahans/sample-a.jpg',
        ]);
        $insideRange->forceFill(['created_at' => Carbon::parse('2026-04-01 08:00:00'), 'updated_at' => Carbon::parse('2026-04-01 08:00:00')])->save();

        $outsideRange = AmprahanReport::create([
            'room_id' => $room->id,
            'submitted_by' => $user->id,
            'report_time' => '09:00',
            'shift' => 'sore',
            'next_shift' => 'malam',
            'officer_name' => 'Andi',
            'next_officer_name' => 'Rina',
            'male_patient_count' => 3,
            'female_patient_count' => 1,
            'action_plan' => null,
            'image_path' => 'amprahans/sample-b.jpg',
        ]);
        $outsideRange->forceFill(['created_at' => Carbon::parse('2026-04-10 09:00:00'), 'updated_at' => Carbon::parse('2026-04-10 09:00:00')])->save();

        $response = $this->actingAs($user)->get(route('amprahans.print', [
            'date_from' => '2026-04-01',
            'date_to' => '2026-04-05',
        ]));

        $response->assertOk();
        $response->assertSee('Budi');
        $response->assertDontSee('Andi');
    }
}
