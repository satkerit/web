<?php

namespace Tests\Unit\Models;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    #[Test]
    public function is_super_admin_returns_true_for_super_admin_role(): void
    {
        $user = User::factory()->create(['role' => 'super_admin']);

        $this->assertTrue($user->isSuperAdmin());
    }

    #[Test]
    public function is_super_admin_returns_false_for_other_roles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $editor = User::factory()->create(['role' => 'editor']);

        $this->assertFalse($admin->isSuperAdmin());
        $this->assertFalse($editor->isSuperAdmin());
    }

    #[Test]
    public function is_admin_returns_true_for_admin_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($admin->isAdmin());
    }

    #[Test]
    public function is_admin_returns_true_for_super_admin_role(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $this->assertTrue($superAdmin->isAdmin());
    }

    #[Test]
    public function is_admin_returns_false_for_editor_role(): void
    {
        $editor = User::factory()->create(['role' => 'editor']);

        $this->assertFalse($editor->isAdmin());
    }

    #[Test]
    public function is_editor_returns_true_for_editor_role(): void
    {
        $editor = User::factory()->create(['role' => 'editor']);

        $this->assertTrue($editor->isEditor());
    }

    #[Test]
    public function is_editor_returns_false_for_other_roles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $this->assertFalse($admin->isEditor());
        $this->assertFalse($superAdmin->isEditor());
    }

    #[Test]
    public function has_role_accepts_string_parameter(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($admin->hasRole('admin'));
        $this->assertFalse($admin->hasRole('super_admin'));
        $this->assertFalse($admin->hasRole('editor'));
    }

    #[Test]
    public function has_role_accepts_array_parameter(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($admin->hasRole(['admin', 'editor']));
        $this->assertTrue($admin->hasRole(['super_admin', 'admin']));
        $this->assertFalse($admin->hasRole(['super_admin', 'editor']));
    }

    #[Test]
    public function can_manage_users_returns_true_only_for_super_admin(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $editor = User::factory()->create(['role' => 'editor']);

        $this->assertTrue($superAdmin->canManageUsers());
        $this->assertFalse($admin->canManageUsers());
        $this->assertFalse($editor->canManageUsers());
    }

    #[Test]
    public function can_manage_settings_returns_true_for_admin_roles(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $editor = User::factory()->create(['role' => 'editor']);

        $this->assertTrue($superAdmin->canManageSettings());
        $this->assertTrue($admin->canManageSettings());
        $this->assertFalse($editor->canManageSettings());
    }

    #[Test]
    public function can_manage_content_returns_true_for_all_roles(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $editor = User::factory()->create(['role' => 'editor']);

        $this->assertTrue($superAdmin->canManageContent());
        $this->assertTrue($admin->canManageContent());
        $this->assertTrue($editor->canManageContent());
    }
}
