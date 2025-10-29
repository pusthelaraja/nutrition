<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@nutrition.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Super Admin role (which has all permissions)
        $admin->assignRole('Super Admin');

        $this->command->info('Super Admin user created/updated successfully!');
        $this->command->info('Email: admin@nutrition.com');
        $this->command->info('Password: password');
        $this->command->info('Role: Super Admin (All Permissions)');
    }
}
