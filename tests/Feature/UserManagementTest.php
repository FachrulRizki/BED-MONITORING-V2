<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_user_management_page(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertOk();
    }

    public function test_non_admin_cannot_access_user_management_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Petugas Baru',
            'username' => 'petugas.baru',
            'email' => 'petugas.baru@hospital.com',
            'role' => 'petugas',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'username' => 'petugas.baru',
            'email' => 'petugas.baru@hospital.com',
            'role' => 'petugas',
        ]);
    }

    public function test_admin_can_update_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->put(route('users.update', $user), [
            'name' => 'Petugas Update',
            'username' => 'petugas.update',
            'email' => 'petugas.update@hospital.com',
            'role' => 'admin',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect(route('users.index'));

        $user->refresh();

        $this->assertSame('Petugas Update', $user->name);
        $this->assertSame('petugas.update', $user->username);
        $this->assertSame('petugas.update@hospital.com', $user->email);
        $this->assertSame('admin', $user->role);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $this->assertModelMissing($user);
    }

    public function test_admin_cannot_delete_own_user_from_management_page(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->delete(route('users.destroy', $admin));

        $response->assertRedirect(route('users.index'));
        $this->assertModelExists($admin);
    }
}
