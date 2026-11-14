<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = ['id'];

    public function receiptNumber(): string
    {
        return 'LP-'.str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    public function recordedProduct(): string
    {
        return $this->product_name ?? $this->product->name;
    }

    public function recordedCustomer(): string
    {
        return $this->customer_name ?? $this->customer?->full_name ?? 'Walk-in';
    }

    public function recordedStaff(): string
    {
        return $this->staff_name ?? $this->staff->full_name;
    }

    public function recordedUnitPrice(): string
    {
        return $this->unit_price ?? bcdiv((string) $this->total_price, (string) $this->quantity, 2);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'sold_by')->withTrashed();
    }
}
