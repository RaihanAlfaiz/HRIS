<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'name'     => 'Administrator',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ],
            [
                'username' => 'hr',
                'name'     => 'HR Manager',
                'password' => Hash::make('password'),
                'role'     => 'hr',
            ],
            [
                'username' => 'viewer',
                'name'     => 'Staff Viewer',
                'password' => Hash::make('password'),
                'role'     => 'viewer',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['username' => $user['username']],
                $user,
            );
        }
    }
}
