<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@iris.local'],
            [
                'name' => 'Admin IRIS',
                'password' => Hash::make('Password123!'),
                'role' => 'admin',
            ]
        );
    }
}