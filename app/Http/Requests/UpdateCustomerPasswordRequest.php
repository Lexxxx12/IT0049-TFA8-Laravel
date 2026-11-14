<?php

namespace App\Http\Requests;

use App\Rules\PasswordBytes;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('customer') !== null;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['bail', 'required', 'string', new PasswordBytes, 'current_password:customer'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password', new PasswordBytes],
        ];
    }

    public function attributes(): array
    {
        return ['password' => 'new password'];
    }
}
