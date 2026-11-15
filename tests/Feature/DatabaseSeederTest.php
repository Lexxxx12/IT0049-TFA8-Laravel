<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_configured_bootstrap_admin_is_created_only_once(): void
    {
        config()->set('app.bootstrap_admin', [
            'username' => 'render.admin',
            'full_name' => 'Render Administrator',
            'password' => 'secure-test-password',
        ]);

        $this->seed();
        $this->seed();

        $user = User::where('username', 'render.admin')->sole();

        $this->assertSame('Render Administrator', $user->full_name);
        $this->assertTrue(Hash::check('secure-test-password', $user->password));
        $this->assertSame(1, User::where('username', 'render.admin')->count());
    }
}
