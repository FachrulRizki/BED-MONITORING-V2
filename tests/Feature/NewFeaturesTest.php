<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * End-to-end tests untuk fitur baru
 * Validates: Requirements 6.7, 7.1, 8.1, 9.1
 */
class NewFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Login redirect ke /dashboard (bukan /)
     * Validates: Requirements 6.7, 7.1
     */
    public function test_login_redirects_to_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
    }

    /**
     * Test: GET /dashboard mengembalikan HTTP 200 untuk user yang login
     * Validates: Requirements 7.1
     */
    public function test_dashboard_accessible_when_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test: GET / (Monitor_Publik) dapat diakses tanpa auth dan menampilkan data ruangan dengan persentase
     * Validates: Requirements 9.1, 6.1
     */
    public function test_monitor_publik_accessible_without_auth(): void
    {
        Room::create([
            'name'            => 'Test Room',
            'male_capacity'   => 10,
            'female_capacity' => 10,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Test Room');
        $response->assertSee('%');
    }

    /**
     * Test: GET /profile dapat diakses oleh user yang login
     * Validates: Requirements 8.1
     */
    public function test_profile_accessible_when_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
    }
}
