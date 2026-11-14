<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceOrderRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\CustomerAccount;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CustomerOrderController extends Controller
{
    private function cartKey(Request $request): string
    {
        return 'shopping_cart.'.$request->user('customer')->id;
    }

    private function rows(Request $request, bool $lock = false): array
    {
        $cart = $request->session()->get($this->cartKey($request), []);
        $query = Product::withTrashed()->whereIn('id', array_keys($cart))->orderBy('id');
        $products = ($lock ? $query->lockForUpdate() : $query)->get()->keyBy('id');

        return collect($cart)->map(function ($quantity, $id) use ($products): array {
            $product = $products->get($id);

            return [
                'id' => $id, 'product' => $product, 'quantity' => (int) $quantity,
                'available' => $product && ! $product->trashed() && $product->stock_quantity >= $quantity,
                'total' => $product ? bcmul($product->price, (string) $quantity, 2) : '0.00',
            ];
        })->sortBy('id')->values()->all();
    }

    private function fingerprint(array $rows): string
    {
        return hash('sha256', json_encode(array_map(fn ($row) => [$row['id'], $row['quantity'], $row['product']?->price], $rows)));
    }

    public function catalog(Request $request): View
    {
        $filters = $request->validate(['q' => 'nullable|string|max:100', 'available' => 'nullable|boolean']);
        $products = Product::query()
            ->when(filled($filters['q'] ?? null), fn ($query) => $query->where('name', 'like', '%'.$filters['q'].'%'))
            ->when($request->boolean('available'), fn ($query) => $query->where('stock_quantity', '>', 0))
            ->orderBy('name')->paginate(12)->withQueryString();

        return view('catalog', ['products' => $products, 'filters' => $filters, 'cart' => $request->session()->get($this->cartKey($request), [])]);
    }

    public function updateCart(UpdateCartRequest $request, Product $product): RedirectResponse
    {
        $quantity = (int) $request->validated('quantity');
        $cart = $request->session()->get($this->cartKey($request), []);
        if ($quantity === 0) {
            unset($cart[$product->id]);
        } else {
            if ($product->trashed() || $quantity > $product->stock_quantity) {
                throw ValidationException::withMessages(['quantity' => 'This quantity is unavailable. Check the current stock and try again.']);
            }
            if (! isset($cart[$product->id]) && count($cart) >= 30) {
                throw ValidationException::withMessages(['quantity' => 'Your cart can contain up to 30 different products.']);
            }
            $cart[$product->id] = $quantity;
        }
        $request->session()->put($this->cartKey($request), $cart);

        return back()->with('status', $quantity === 0 ? 'Item removed from cart.' : 'Cart updated.');
    }

    public function cart(Request $request): View
    {
        $rows = $this->rows($request);
        $total = '0.00';
        foreach ($rows as $row) {
            $total = bcadd($total, $row['total'], 2);
        }
        $token = (string) Str::uuid();
        $tokensKey = 'checkout_tokens.'.$request->user('customer')->id;
        $tokens = $request->session()->get($tokensKey, []);
        $tokens[$token] = $this->fingerprint($rows);
        $request->session()->put($tokensKey, array_slice($tokens, -20, null, true));

        return view('cart', compact('rows', 'total', 'token'));
    }

    public function store(PlaceOrderRequest $request): RedirectResponse
    {
        $customerId = $request->user('customer')->customer_id;
        $token = $request->validated('submission_token');
        $existing = Order::where('submission_token', $token)->where('customer_id', $customerId)->first();
        if ($existing) {
            return redirect()->route('customer.orders.show', $existing)->with('status', 'This order was already placed.');
        }
        $order = DB::transaction(function () use ($request, $customerId, $token): Order {
            $account = CustomerAccount::lockForUpdate()->findOrFail($request->user('customer')->id);
            if (! $account->is_active || ! $account->customer) {
                abort(403);
            }
            $existing = Order::where('submission_token', $token)->where('customer_id', $customerId)->first();
            if ($existing) {
                return $existing;
            }
            $rows = $this->rows($request, true);
            $expected = $request->session()->get('checkout_tokens.'.$account->id.'.'.$token);
            if (! $rows || ! is_string($expected) || ! hash_equals($expected, $this->fingerprint($rows))) {
                throw ValidationException::withMessages(['cart' => 'Your cart or a price changed. Review your cart before placing the order.']);
            }
            $total = '0.00';
            foreach ($rows as $row) {
                if (! $row['available']) {
                    throw ValidationException::withMessages(['cart' => 'An item is unavailable or exceeds current stock. Update your cart before ordering.']);
                }
                $total = bcadd($total, $row['total'], 2);
            }
            if (bccomp($total, '99999999.99', 2) > 0) {
                throw ValidationException::withMessages(['cart' => 'Your order exceeds the maximum total. Reduce the quantities.']);
            }
            $customer = $account->customer;
            $order = Order::create([
                'customer_id' => $customerId, 'submission_token' => $token,
                'customer_name' => $customer->full_name, 'customer_email' => $customer->email,
                'customer_phone' => $customer->phone, 'total_price' => $total,
            ]);
            foreach ($rows as $row) {
                $order->items()->create([
                    'product_id' => $row['id'], 'product_name' => $row['product']->name,
                    'unit_price' => $row['product']->price, 'quantity' => $row['quantity'],
                    'total_price' => $row['total'], 'sale_token' => (string) Str::uuid(),
                ]);
            }

            return $order;
        }, 3);
        $request->session()->forget($this->cartKey($request));

        return redirect()->route('customer.orders.show', $order)->with('status', 'Order placed. Pay at pickup; staff will confirm payment and complete your order.');
    }

    public function index(Request $request): View
    {
        return view('orders', ['customerView' => true, 'orders' => Order::where('customer_id', $request->user('customer')->customer_id)->withCount('items')->latest('id')->paginate(12)]);
    }

    public function show(Request $request, int $id): View
    {
        $order = Order::where('customer_id', $request->user('customer')->customer_id)->with('items.sale')->findOrFail($id);

        return view('order', ['order' => $order, 'customerView' => true]);
    }

    public function cancel(Request $request, int $id): RedirectResponse
    {
        DB::transaction(function () use ($request, $id): void {
            $order = Order::where('customer_id', $request->user('customer')->customer_id)->lockForUpdate()->findOrFail($id);
            if ($order->status !== 'pending') {
                throw ValidationException::withMessages(['order' => 'Only pending orders can be cancelled.']);
            }
            $order->update(['status' => 'cancelled']);
        });

        return back()->with('status', 'Order cancelled.');
    }
}
