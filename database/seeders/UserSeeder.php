<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => Role::ADMIN->value,
            ]
        );

        // Create responder user
        User::firstOrCreate(
            ['email' => 'responder@example.com'],
            [
                'name' => 'Responder User',
                'password' => Hash::make('password'),
                'role' => Role::RESPONDER->value,
            ]
        );

        // Create viewer user
        User::firstOrCreate(
            ['email' => 'viewer@example.com'],
            [
                'name' => 'Viewer User',
                'password' => Hash::make('password'),
                'role' => Role::VIEWER->value,
            ]
        );

        $this->command->info('✓ Created 3 test users (admin/responder/viewer)');
        $this->command->info('  Email: admin@example.com | Password: password');
        $this->command->info('  Email: responder@example.com | Password: password');
        $this->command->info('  Email: viewer@example.com | Password: password');
    }
}
