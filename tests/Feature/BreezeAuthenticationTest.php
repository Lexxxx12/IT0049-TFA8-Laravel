<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BreezeAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_and_authenticates_a_staff_user(): void
    {
        $this->get(route('register'))->assertOk()->assertSee('Create a staff account');

        $response = $this->post(route('register.store'), [
            'username' => 'newstaff',
            'full_name' => 'New Staff',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::query()->where('username', 'newstaff')->firstOrFail();
        $response->assertRedirect(route('sales.create'));
        $this->assertAuthenticatedAs($user, 'web');
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_login_and_logout_use_secure_laravel_sessions(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->post(route('login.store'), [
            'username' => $user->username,
            'password' => 'password123',
        ])->assertRedirect(route('sales.create'));
        $this->assertAuthenticatedAs($user, 'web');

        $tokenBeforeLogout = session()->token();
        $this->post(route('logout'))->assertRedirect(route('login'));

        $this->assertGuest('web');
        $this->assertNotSame($tokenBeforeLogout, session()->token());
    }

    public function test_malformed_registration_values_redirect_and_render_safely(): void
    {
        $this->from(route('register'))->post(route('register.store'), [
            'username' => ['invalid'],
            'full_name' => ['invalid'],
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('register'))->assertSessionHasErrors(['username', 'full_name']);

        $this->get(route('register'))->assertOk()->assertSee('Create a staff account');
        $this->assertDatabaseCount('users', 0);
    }
}
