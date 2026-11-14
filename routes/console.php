<?php

use App\Models\User;
use App\Rules\PasswordBytes;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

Artisan::command('pos:create-staff', function () {
    $data = ['username' => $this->ask('Username'), 'full_name' => $this->ask('Full name'), 'password' => $this->secret('Password (at least 8 characters)')];
    $v = Validator::make($data, ['username' => 'required|alpha_dash|max:50|unique:users', 'full_name' => 'required|string|max:100', 'password' => ['required', 'string', 'min:8', new PasswordBytes]]);
    if ($v->fails()) {
        foreach ($v->errors()->all() as $message) {
            $this->error($message);
        }

        return 1;
    }
    User::create($data);
    $this->info('Staff account created.');
})->purpose('Create a staff account securely');
