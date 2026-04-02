<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private string $validEmail = 'admin@hospital.com';
    private string $validPassword = 'password';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\UserSeeder::class);
    }

    public function test_dashboard_dapat_diakses_tanpa_login(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_login_berhasil_dengan_kredensial_valid_redirect_ke_dashboard(): void
    {
        $response = $this->post('/login', [
            'email'    => $this->validEmail,
            'password' => $this->validPassword,
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_login_gagal_dengan_kredensial_tidak_valid_mengembalikan_422(): void
    {
        $response = $this->post('/login', [
            'email'    => $this->validEmail,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_logout_menghapus_sesi_dan_redirect_ke_dashboard(): void
    {
        $user = User::where('email', $this->validEmail)->first();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
