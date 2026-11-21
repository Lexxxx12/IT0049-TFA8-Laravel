<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserResourceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_routes_redirect_guests_to_staff_login(): void
    {
        $user = User::factory()->create();

        $this->get(route('users.index'))->assertRedirect(route('login'));
        $this->get(route('users.create'))->assertRedirect(route('login'));
        $this->get(route('users.show', $user))->assertRedirect(route('login'));
        $this->get(route('users.edit', $user))->assertRedirect(route('login'));
        $this->post(route('users.store'), [])->assertRedirect(route('login'));
        $this->put(route('users.update', $user), [])->assertRedirect(route('login'));
        $this->delete(route('users.destroy', $user))->assertRedirect(route('login'));
    }

    public function test_authenticated_staff_can_manage_users_through_resource_routes(): void
    {
        $staff = User::factory()->create();
        $this->actingAs($staff, 'web');

        $this->get(route('users.index'))->assertOk();
        $this->get(route('users.create'))->assertOk();
        $this->post(route('users.store'), [
            'username' => 'resourceuser',
            'full_name' => 'Resource User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('users.index'));

        $user = User::query()->where('username', 'resourceuser')->firstOrFail();
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->get(route('users.show', $user))->assertRedirect(route('users.edit', $user));
        $this->get(route('users.edit', $user))->assertOk()->assertSee('Resource User');
        $this->put(route('users.update', $user), [
            'username' => 'resourceuser',
            'full_name' => 'Updated Resource User',
            'password' => '',
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'full_name' => 'Updated Resource User',
        ]);
        $this->assertTrue(Hash::check('password123', $user->fresh()->password));

        $this->delete(route('users.destroy', $user))->assertRedirect();
        $this->assertSoftDeleted($user);
    }
}
