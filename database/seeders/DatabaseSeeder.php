<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $username = config('app.bootstrap_admin.username');
        $password = config('app.bootstrap_admin.password');

        if (! is_string($username) || $username === '' || ! is_string($password) || $password === '') {
            return;
        }

        User::firstOrCreate(
            ['username' => $username],
            [
                'full_name' => config('app.bootstrap_admin.full_name', 'Administrator'),
                'password' => $password,
            ],
        );
    }
}
