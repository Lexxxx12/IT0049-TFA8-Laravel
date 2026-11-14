<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CompleteOrder
{
    public function __construct(private RecordSale $recordSale) {}

    public function execute(Order $order, int $staffId): void
    {
        DB::transaction(function () use ($order, $staffId): void {
            $locked = Order::lockForUpdate()->findOrFail($order->id);
            if ($locked->status === 'completed') {
                return;
            }
            if ($locked->status !== 'pending') {
                throw ValidationException::withMessages(['order' => 'This order has been cancelled.']);
            }
            $items = $locked->items()->get();
            if ($items->isEmpty()) {
                throw ValidationException::withMessages(['order' => 'This order has no items.']);
            }
            foreach ($items as $item) {
                $this->recordSale->execute([
                    'product_id' => $item->product_id,
                    'customer_id' => $locked->customer_id,
                    'quantity' => $item->quantity,
                    'submission_token' => $item->sale_token,
                ], $staffId, $item->unit_price);
                $sale = Sale::where('submission_token', $item->sale_token)->sole();
                $sale->update(['product_name' => $item->product_name, 'customer_name' => $locked->customer_name]);
                $item->update(['sale_id' => $sale->id]);
            }
            $locked->update(['status' => 'completed', 'completed_by' => $staffId, 'completed_at' => now()]);
        }, 3);
    }
}
