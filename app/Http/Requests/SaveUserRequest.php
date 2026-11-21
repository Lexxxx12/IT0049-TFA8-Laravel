<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\PasswordBytes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('web') !== null;
    }

    public function rules(): array
    {
        $user = $this->route('user');
        $userId = $user instanceof User ? $user->id : null;

        return [
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users')->ignore($userId)],
            'full_name' => ['required', 'string', 'max:100'],
            'password' => [$userId ? 'nullable' : 'required', 'string', 'min:8', 'confirmed', new PasswordBytes],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096', 'dimensions:max_width=6000,max_height=6000'],
        ];
    }

    public function attributes(): array
    {
        return ['full_name' => 'full name', 'avatar' => 'profile picture'];
    }
}
