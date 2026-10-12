<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        User::create([
            'name' => 'Dean',
            'username' => 'deanuser',
            'email' => 'dean@example.com',
            'password' => Hash::make('password123'),
            'role' => 'dean',
        ]);

        
        User::create([
            'name' => 'Accounts',
            'username' => 'accounts1',
            'email' => 'accounts@example.com',
            'password' => Hash::make('password123'),
            'role' => 'accounts',
        ]);
    }
}
