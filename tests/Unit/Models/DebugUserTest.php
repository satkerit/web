<?php

namespace Tests\Unit\Models;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DebugUserTest extends TestCase
{
    #[Test]
    public function debug_can_manage_settings()
    {
        $admin = User::factory()->admin()->create();

        dump('Role: ' . $admin->roleModel->name);
        dump('Is Admin: ' . ($admin->isAdmin() ? 'true' : 'false'));
        dump('Can Manage Settings: ' . ($admin->canManageSettings() ? 'true' : 'false'));

        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($admin->canManageSettings());
    }
}
