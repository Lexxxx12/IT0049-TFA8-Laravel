<?php

namespace Tests\Feature;

use App\Models\CustomerAccount;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class MalformedFormInputTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_registration_redirect_renders_array_input_safely_and_never_repopulates_passwords(): void
    {
        $this->followingRedirects()
            ->from('/customer/register')
            ->post('/customer/register', [
                'full_name' => 'Audit Registration',
                'email' => ['invalid'],
                'phone' => '09123456789',
                'password' => 'AuditPassword123',
                'password_confirmation' => 'AuditPassword123',
            ])->assertOk()
            ->assertSee('The email field must be a valid email address.')
            ->assertSee('value="Audit Registration"', false)
            ->assertSee('value="09123456789"', false)
            ->assertDontSee('AuditPassword123');

        $this->assertDatabaseCount('customer_accounts', 0);
    }

    public function test_login_redirects_render_array_identifiers_safely(): void
    {
        $this->followingRedirects()
            ->from('/customer/login')
            ->post('/customer/login', [
                'email' => ['invalid'],
                'password' => 'AuditPassword123',
            ])->assertOk()
            ->assertSee('The email field must be a valid email address.')
            ->assertDontSee('AuditPassword123');

        $this->followingRedirects()
            ->from('/login')
            ->post('/login', [
                'username' => ['invalid'],
                'password' => 'AuditPassword123',
            ])->assertOk()
            ->assertSee('The username field must be a string.')
            ->assertDontSee('AuditPassword123');
    }

    public function test_crud_profile_and_sale_redirects_render_malformed_old_input_safely(): void
    {
        $staff = User::factory()->create();
        $account = CustomerAccount::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 5]);

        $this->actingAs($staff, 'web');
        $this->followingRedirects()
            ->from('/products/create')
            ->post('/products', [
                'name' => ['invalid'],
                'price' => '10.00',
                'stock_quantity' => '5',
            ])->assertOk()
            ->assertSee('The name field must be a string.');

        $this->followingRedirects()
            ->from('/sales/create')
            ->post('/sales', [
                'product_id' => $product->id,
                'quantity' => ['invalid'],
                'submission_token' => ['invalid'],
            ])->assertOk()
            ->assertSee('The quantity field must be an integer.');

        $this->actingAs($account, 'customer');
        $this->followingRedirects()
            ->from('/customer/account')
            ->put('/customer/account', [
                'full_name' => ['invalid'],
                'phone' => '09123456789',
            ])->assertOk()
            ->assertSee('The full name field must be a string.');

        $this->assertDatabaseCount('sales', 0);
    }
}
