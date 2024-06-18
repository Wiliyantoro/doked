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
        // Menambahkan pengguna admin
        User::create([
            'name' => 'admin',
            'email' => 'admin@localhost.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}

