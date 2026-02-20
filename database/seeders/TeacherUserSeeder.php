<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TeacherUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'teacher@iris.local'],
            [
                'name' => 'Prof IRIS',
                'password' => Hash::make('Password123!'),
                'role' => 'teacher',
            ]
        );
    }
}
