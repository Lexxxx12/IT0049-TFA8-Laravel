<?php

namespace App\Http\Requests;

use App\Models\Customer;
use App\Rules\PasswordBytes;
use Illuminate\Foundation\Http\FormRequest;

class RegisterCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('email'))) {
            $this->merge(['email' => mb_strtolower(trim($this->input('email')))]);
        }
    }

    public function rules(): array
    {
        return ['full_name' => 'required|string|max:100', 'email' => ['required', 'email', 'max:100', 'unique:customer_accounts,email', function ($attribute, $value, $fail) {
            if (Customer::withTrashed()->whereRaw('LOWER(email) = ?', [$value])->exists()) {
                $fail('This email is already in our customer records. Please contact staff to enable your account.');
            }
        }], 'phone' => 'nullable|string|max:20', 'password' => ['required', 'string', 'min:8', 'confirmed', new PasswordBytes]];
    }
}
