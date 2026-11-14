<?php

namespace App\Http\Controllers;

use App\Actions\CompleteOrder;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StaffOrderController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate(['status' => 'nullable|in:pending,completed,cancelled,all']);
        $status = $filters['status'] ?? 'pending';
        $orders = Order::query()->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->withCount('items')->latest('id')->paginate(12)->withQueryString();

        return view('orders', ['orders' => $orders, 'customerView' => false, 'status' => $status]);
    }

    public function show(Order $order): View
    {
        return view('order', ['order' => $order->load('items.sale'), 'customerView' => false]);
    }

    public function complete(Request $request, Order $order, CompleteOrder $completeOrder): RedirectResponse
    {
        $completeOrder->execute($order, $request->user('web')->id);

        return redirect()->route('orders.show', $order)->with('status', 'Payment confirmed. Order completed and stock updated.');
    }

    public function cancel(Order $order): RedirectResponse
    {
        DB::transaction(function () use ($order): void {
            $locked = Order::lockForUpdate()->findOrFail($order->id);
            if ($locked->status !== 'pending') {
                throw ValidationException::withMessages(['order' => 'Only pending orders can be cancelled.']);
            }
            $locked->update(['status' => 'cancelled']);
        });

        return redirect()->route('orders.show', $order)->with('status', 'Order cancelled. No stock or payment changes were recorded.');
    }
}
