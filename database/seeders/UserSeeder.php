<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() <= 0) {
            User::create([
                'name' => 'User',
                'email' => 'user@user.com',
                'password' => bcrypt('password')
            ]);
        }
    }
}
