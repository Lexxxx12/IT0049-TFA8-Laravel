<?php

namespace App\Actions;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RecordSale
{
    public function execute(array $data, int $staffId, ?string $agreedUnitPrice = null): void
    {
        DB::transaction(function () use ($data, $staffId, $agreedUnitPrice) {
            if (! ($staff = User::whereKey($staffId)->lockForUpdate()->first())) {
                throw ValidationException::withMessages(['quantity' => 'Your staff account is no longer available.']);
            }
            $existing = Sale::where('submission_token', $data['submission_token'])->first();
            if ($existing) {
                if ($existing->sold_by === $staffId) {
                    return;
                }throw ValidationException::withMessages(['quantity' => 'This sale form has already been used.']);
            }
            $p = Product::lockForUpdate()->find($data['product_id']);
            if (! $p) {
                throw ValidationException::withMessages(['product_id' => 'This product is no longer available.']);
            }
            $customer = null;
            if (($data['customer_id'] ?? null) && ! ($customer = Customer::whereKey($data['customer_id'])->lockForUpdate()->first())) {
                throw ValidationException::withMessages(['customer_id' => 'This customer is no longer available.']);
            }
            if ($p->stock_quantity < $data['quantity']) {
                throw ValidationException::withMessages(['quantity' => 'Insufficient stock. Only '.$p->stock_quantity.' available.']);
            }
            $unitPrice = $agreedUnitPrice ?? $p->price;
            $total = bcmul($unitPrice, (string) $data['quantity'], 2);
            if (bccomp($total, '99999999.99', 2) > 0) {
                throw ValidationException::withMessages(['quantity' => 'The total exceeds the maximum sale amount.']);
            }
            Sale::create(['product_id' => $p->id, 'customer_id' => $data['customer_id'] ?? null, 'quantity' => $data['quantity'], 'submission_token' => $data['submission_token'], 'sold_by' => $staffId, 'total_price' => $total, 'product_name' => $p->name, 'customer_name' => $customer?->full_name ?? 'Walk-in', 'staff_name' => $staff->full_name, 'unit_price' => $unitPrice]);
            $p->decrement('stock_quantity', $data['quantity']);
            $p->touch();
        });

    }
}
