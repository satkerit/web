<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function super_admin_can_view_users_index(): void
    {
        $superAdmin = $this->createSuperAdmin();
        User::factory()->count(3)->create();

        $response = $this->actingAs($superAdmin)
            ->withoutSecurityMiddleware()
            ->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas('users');
    }

    #[Test]
    public function super_admin_can_create_user(): void
    {
        $superAdmin = $this->createSuperAdmin();
        $role = Role::where('name', 'editor')->firstOrFail();

        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role_id' => $role->id,
            'is_active' => true,
        ];

        $response = $this->actingAs($superAdmin)
            ->withoutSecurityMiddleware()
            ->post(route('admin.users.store'), $userData);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    #[Test]
    public function super_admin_can_update_user(): void
    {
        $superAdmin = $this->createSuperAdmin();
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $role = Role::where('name', 'admin')->firstOrFail();

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role_id' => $role->id,
            'is_active' => true,
        ];

        $response = $this->actingAs($superAdmin)
            ->withoutSecurityMiddleware()
            ->put(route('admin.users.update', $user), $updateData);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role_id' => $role->id,
        ]);
    }

    #[Test]
    public function super_admin_can_delete_user(): void
    {
        $superAdmin = $this->createSuperAdmin();
        $user = User::factory()->create();

        $response = $this->actingAs($superAdmin)
            ->withoutSecurityMiddleware()
            ->delete(route('admin.users.destroy', $user));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    #[Test]
    public function super_admin_can_change_user_role(): void
    {
        $superAdmin = $this->createSuperAdmin();
        $user = User::factory()->editor()->create();
        $adminRole = Role::where('name', 'admin')->firstOrFail();

        $updateData = [
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $adminRole->id,
            'is_active' => true,
        ];

        $response = $this->actingAs($superAdmin)
            ->withoutSecurityMiddleware()
            ->put(route('admin.users.update', $user), $updateData);

        $response->assertRedirect(route('admin.users.index'));

        $user->refresh();
        $this->assertEquals('admin', $user->roleModel->name);
    }

    #[Test]
    public function super_admin_can_toggle_user_active_status(): void
    {
        $superAdmin = $this->createSuperAdmin();
        $user = User::factory()->editor()->create(['is_active' => true]);

        $updateData = [
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'is_active' => false,
        ];

        $response = $this->actingAs($superAdmin)
            ->withoutSecurityMiddleware()
            ->put(route('admin.users.update', $user), $updateData);

        $response->assertRedirect(route('admin.users.index'));

        $user->refresh();
        $this->assertFalse($user->is_active);
    }

    #[Test]
    public function admin_cannot_access_user_management(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)
            ->withoutSecurityMiddleware()
            ->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    #[Test]
    public function editor_cannot_access_user_management(): void
    {
        $editor = $this->createEditor();

        $response = $this->actingAs($editor)
            ->withoutSecurityMiddleware()
            ->get(route('admin.users.index'));

        $response->assertStatus(403);
    }
}
