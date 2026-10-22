<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::firstOrNew(['email' => 'admin@example.com']);

        $user->forceFill([
            'name'              => 'Admin',
            'last_name'         => 'Admin',
            'password'          => Hash::make('password'),
            'permissions'       => 'admin',
            'is_active'         => '1',
            'email_verified_at' => now(),
        ])->save();
    }
}
