<?php

namespace Tests\Feature;

use App\Models\CustomerAccount;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerOrderTest extends TestCase
{
    use RefreshDatabase;

    private function checkout(CustomerAccount $account, array $cart): string
    {
        $this->actingAs($account, 'customer')->withSession(['shopping_cart' => [$account->id => $cart]]);

        return $this->get(route('customer.cart'))->assertOk()->viewData('token');
    }

    private function place(CustomerAccount $account, array $cart): Order
    {
        $token = $this->checkout($account, $cart);
        $this->post(route('customer.orders.store'), ['submission_token' => $token])->assertSessionHasNoErrors();

        return Order::where('submission_token', $token)->sole();
    }

    public function test_shop_cart_and_orders_require_customer_login(): void
    {
        foreach (['/customer/shop', '/customer/cart', '/customer/orders'] as $url) {
            $this->get($url)->assertRedirect('/customer/login');
        }
        $this->actingAs(User::factory()->create(), 'web')->get('/customer/shop')->assertRedirect('/customer/login');
    }

    public function test_cart_uses_server_prices_and_rejects_unavailable_quantities(): void
    {
        $account = CustomerAccount::factory()->create();
        $product = Product::factory()->create(['price' => '25.50', 'stock_quantity' => 3]);
        $this->actingAs($account, 'customer');

        $this->put(route('customer.cart.update', $product), ['quantity' => 2, 'price' => '0.01'])->assertSessionHasNoErrors();
        $this->get(route('customer.cart'))->assertOk()->assertViewHas('total', '51.00');
        $this->put(route('customer.cart.update', $product), ['quantity' => 4])->assertSessionHasErrors('quantity');
        $this->assertSame(2, session('shopping_cart.'.$account->id.'.'.$product->id));
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_is_idempotent_and_pending_orders_do_not_reduce_stock(): void
    {
        $account = CustomerAccount::factory()->create();
        $product = Product::factory()->create(['price' => '25.50', 'stock_quantity' => 5]);
        $token = $this->checkout($account, [$product->id => 2]);

        $response = $this->post(route('customer.orders.store'), ['submission_token' => $token, 'total_price' => '0.01', 'status' => 'completed']);
        $order = Order::sole();
        $response->assertRedirect(route('customer.orders.show', $order));
        $this->withSession(['shopping_cart' => [$account->id => [$product->id => 1]]]);
        $this->post(route('customer.orders.store'), ['submission_token' => $token])->assertRedirect(route('customer.orders.show', $order));
        $this->assertSame(1, session('shopping_cart.'.$account->id.'.'.$product->id));

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'pending', 'total_price' => '51.00', 'customer_id' => $account->customer_id]);
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'unit_price' => '25.50', 'quantity' => 2]);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock_quantity' => 5]);
        $this->assertDatabaseCount('sales', 0);
    }

    public function test_changed_prices_require_customer_to_review_the_cart_again(): void
    {
        $account = CustomerAccount::factory()->create();
        $product = Product::factory()->create(['price' => '25.50', 'stock_quantity' => 5]);
        $token = $this->checkout($account, [$product->id => 2]);
        $product->update(['price' => '30.00']);

        $this->post(route('customer.orders.store'), ['submission_token' => $token])->assertSessionHasErrors('cart');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_rechecks_stock_and_rejects_archived_products(): void
    {
        $account = CustomerAccount::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 5]);
        $token = $this->checkout($account, [$product->id => 2]);
        $product->delete();

        $this->post(route('customer.orders.store'), ['submission_token' => $token])->assertSessionHasErrors('cart');
        $this->put(route('customer.cart.update', $product), ['quantity' => 0])->assertSessionHasNoErrors();

        $this->assertDatabaseCount('orders', 0);
        $this->assertSame([], session('shopping_cart.'.$account->id));
    }

    public function test_customer_cannot_view_cancel_or_complete_someone_elses_order(): void
    {
        $order = Order::factory()->create();
        $this->actingAs(CustomerAccount::factory()->create(), 'customer');

        $this->get(route('customer.orders.show', $order))->assertNotFound();
        $this->post(route('customer.orders.cancel', $order))->assertNotFound();
        $this->post(route('orders.complete', $order))->assertRedirect('/login');

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'pending']);
    }

    public function test_customer_can_cancel_their_pending_order_without_stock_changes(): void
    {
        $account = CustomerAccount::factory()->create();
        $product = Product::factory()->create(['stock_quantity' => 5]);
        $order = $this->place($account, [$product->id => 2]);

        $this->post(route('customer.orders.cancel', $order))->assertSessionHasNoErrors();

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'cancelled']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock_quantity' => 5]);
        $this->assertDatabaseCount('sales', 0);
    }

    public function test_staff_completion_records_the_agreed_price_and_only_reduces_stock_once(): void
    {
        $account = CustomerAccount::factory()->create();
        $product = Product::factory()->create(['price' => '25.50', 'stock_quantity' => 5]);
        $order = $this->place($account, [$product->id => 2]);
        $product->update(['price' => '40.00']);
        $staff = User::factory()->create();
        $this->actingAs($staff, 'web');

        $this->post(route('orders.complete', $order))->assertRedirect(route('orders.show', $order));
        $this->post(route('orders.complete', $order))->assertRedirect(route('orders.show', $order));

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'completed', 'completed_by' => $staff->id]);
        $this->assertDatabaseHas('sales', ['customer_id' => $account->customer_id, 'sold_by' => $staff->id, 'quantity' => 2, 'unit_price' => '25.50', 'total_price' => '51.00']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock_quantity' => 3]);
        $this->assertDatabaseCount('sales', 1);
        $this->actingAs($account, 'customer')->get(route('customer.orders.show', $order))->assertOk()->assertSee('View receipt');
    }

    public function test_multi_item_completion_rolls_back_every_sale_when_one_item_has_insufficient_stock(): void
    {
        $account = CustomerAccount::factory()->create();
        $first = Product::factory()->create(['stock_quantity' => 5]);
        $second = Product::factory()->create(['stock_quantity' => 5]);
        $order = $this->place($account, [$first->id => 2, $second->id => 3]);
        $second->update(['stock_quantity' => 1]);
        $this->actingAs(User::factory()->create(), 'web');

        $this->post(route('orders.complete', $order))->assertSessionHasErrors('quantity');

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'pending']);
        $this->assertDatabaseHas('products', ['id' => $first->id, 'stock_quantity' => 5]);
        $this->assertDatabaseHas('products', ['id' => $second->id, 'stock_quantity' => 1]);
        $this->assertDatabaseCount('sales', 0);
        $this->assertDatabaseMissing('order_items', ['order_id' => $order->id, 'sale_id' => 1]);
    }

    public function test_cancelled_orders_cannot_be_completed(): void
    {
        $order = Order::factory()->create(['status' => 'cancelled']);
        $this->actingAs(User::factory()->create(), 'web');

        $this->post(route('orders.complete', $order))->assertSessionHasErrors('order');

        $this->assertDatabaseCount('sales', 0);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'cancelled']);
    }

    public function test_catalog_search_and_stock_filter_exclude_archived_and_unavailable_items(): void
    {
        Product::factory()->create(['name' => 'Notebook available', 'stock_quantity' => 5]);
        Product::factory()->create(['name' => 'Notebook empty', 'stock_quantity' => 0]);
        Product::factory()->create(['name' => 'Archived notebook'])->delete();
        $this->actingAs(CustomerAccount::factory()->create(), 'customer');

        $this->get(route('customer.shop', ['q' => 'Notebook', 'available' => 1]))->assertOk()
            ->assertSee('Notebook available')->assertDontSee('Notebook empty')->assertDontSee('Archived notebook');
    }

    public function test_staff_can_cancel_a_pending_order_but_cannot_cancel_a_completed_order(): void
    {
        $pending = Order::factory()->create();
        $completed = Order::factory()->create(['status' => 'completed']);
        $this->actingAs(User::factory()->create(), 'web');

        $this->post(route('orders.cancel', $pending))->assertRedirect(route('orders.show', $pending));
        $this->post(route('orders.cancel', $completed))->assertSessionHasErrors('order');

        $this->assertDatabaseHas('orders', ['id' => $pending->id, 'status' => 'cancelled']);
        $this->assertDatabaseHas('orders', ['id' => $completed->id, 'status' => 'completed']);
        $this->assertDatabaseCount('sales', 0);
    }

    public function test_staff_order_list_does_not_display_uncompiled_blade_directives(): void
    {
        $this->actingAs(User::factory()->create(), 'web');

        $this->get(route('orders.index'))->assertOk()
            ->assertSee('Customer orders')
            ->assertDontSee('@endsection', false);
    }

    public function test_customer_order_list_renders_its_browse_action_inside_the_page_heading(): void
    {
        $this->actingAs(CustomerAccount::factory()->create(), 'customer');

        $this->get(route('customer.orders.index'))->assertOk()
            ->assertDontSee('@endsection', false)
            ->assertSeeInOrder(['class="page-heading"', 'Browse products', 'class="table-card"'], false);
    }
}
