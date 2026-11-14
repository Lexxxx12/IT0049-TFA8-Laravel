<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReceiptController extends Controller
{
    public function staff(Sale $sale): View
    {
        return $this->receipt($sale, false);
    }

    public function customer(Request $request, int $id): View
    {
        $sale = Sale::where('customer_id', $request->user('customer')->customer_id)->findOrFail($id);

        return $this->receipt($sale, true);
    }

    private function receipt(Sale $sale, bool $customerView): View
    {
        return view('receipt', ['sale' => $sale->load(['product', 'customer', 'staff']), 'customerView' => $customerView]);
    }
}
