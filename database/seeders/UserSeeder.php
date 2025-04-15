<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un administrateur
        User::create([
            'name' => 'Admin',
            'email' => 'admin@saji.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Créer un manager
        User::create([
            'name' => 'Manager',
            'email' => 'manager@saji.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        // Créer quelques clients
        User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Marie Martin',
            'email' => 'marie@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Pierre Durand',
            'email' => 'pierre@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
    }
}
