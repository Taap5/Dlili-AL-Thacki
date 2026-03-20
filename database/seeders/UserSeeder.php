<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'user_name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $user1 = User::create([
            'user_name' => 'Ali',
            'email' => 'ali@test.com',
            'password' => bcrypt('password'),
        ]);
        $user1->assignRole('registered');

        $user2 = User::create([
            'user_name' => 'Sara',
            'email' => 'sara@test.com',
            'password' => bcrypt('password'),
        ]);
        $user2->assignRole('registered');
    }
}
