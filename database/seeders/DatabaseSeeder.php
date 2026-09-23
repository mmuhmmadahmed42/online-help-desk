<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Project Manager',
            'email' => 'pm@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'project_manager',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Backend Team',
            'email' => 'backend@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'backend_team',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Frontend Team',
            'email' => 'frontend@helpdesk.com',
            'password' => Hash::make('password'),
            'role' => 'frontend_team',
            'is_active' => true,
        ]);
    }
}