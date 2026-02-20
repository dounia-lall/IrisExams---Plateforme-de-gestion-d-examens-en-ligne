<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class StudentUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'student@iris.local'],
            [
                'name' => 'Étudiant IRIS',
                'password' => Hash::make('Password123!'),
                'role' => 'student',
            ]
        );
    }
}