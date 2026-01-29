<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DebugPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    /** @test */
    public function check_admin_permissions()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $this->assertNotNull($adminRole, 'Admin role not found');

        $permission = Permission::where('name', 'products.view')->first();
        $this->assertNotNull($permission, 'products.view permission not found');

        $hasPermission = $adminRole->permissions()->where('permissions.id', $permission->id)->exists();
        $this->assertTrue($hasPermission, 'Admin role does not have products.view permission in DB');

        $this->assertTrue($adminRole->hasPermission('products.view'), 'Admin role hasPermission() returns false');

        $user = User::factory()->create([
            'role' => 'admin',
            'role_id' => $adminRole->id,
        ]);

        $this->assertTrue($user->hasPermission('products.view'), 'User hasPermission() returns false');
    }
}
