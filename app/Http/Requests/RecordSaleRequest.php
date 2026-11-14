<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('web') !== null;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|integer',
            'customer_id' => 'nullable|integer',
            'quantity' => 'required|integer|min:1|max:2147483647',
            'submission_token' => 'required|uuid',
        ];
    }

    public function attributes(): array
    {
        return ['product_id' => 'product', 'customer_id' => 'customer', 'submission_token' => 'sale form token'];
    }
}
