<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerResourceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_routes_redirect_guests_to_staff_login(): void
    {
        $customer = Customer::create([
            'full_name' => 'Protected Customer',
            'email' => 'protected@example.com',
        ]);

        $this->get(route('customers.index'))->assertRedirect(route('login'));
        $this->get(route('customers.create'))->assertRedirect(route('login'));
        $this->get(route('customers.show', $customer))->assertRedirect(route('login'));
        $this->get(route('customers.edit', $customer))->assertRedirect(route('login'));
        $this->post(route('customers.store'), [])->assertRedirect(route('login'));
        $this->put(route('customers.update', $customer), [])->assertRedirect(route('login'));
        $this->delete(route('customers.destroy', $customer))->assertRedirect(route('login'));
    }

    public function test_authenticated_staff_can_manage_customers_through_resource_routes(): void
    {
        $this->actingAs(User::factory()->create(), 'web');

        $this->get(route('customers.index'))->assertOk();
        $this->get(route('customers.create'))->assertOk();
        $this->post(route('customers.store'), [
            'full_name' => 'Resource Customer',
            'email' => 'resource@example.com',
            'phone' => '09123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('customers.index'));

        $customer = Customer::query()->where('email', 'resource@example.com')->firstOrFail();
        $this->get(route('customers.show', $customer))->assertRedirect(route('customers.edit', $customer));
        $this->get(route('customers.edit', $customer))->assertOk()->assertSee('Resource Customer');
        $this->put(route('customers.update', $customer), [
            'full_name' => 'Updated Resource Customer',
            'email' => 'updated-resource@example.com',
            'phone' => '09987654321',
            'password' => '',
        ])->assertRedirect(route('customers.index'));

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'full_name' => 'Updated Resource Customer',
            'email' => 'updated-resource@example.com',
        ]);
        $this->assertDatabaseHas('customer_accounts', [
            'customer_id' => $customer->id,
            'email' => 'updated-resource@example.com',
        ]);

        $this->delete(route('customers.destroy', $customer))->assertRedirect();
        $this->assertSoftDeleted($customer);
    }
}
