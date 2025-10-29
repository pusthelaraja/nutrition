<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CheckUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user roles and permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking user roles and permissions...');

        // Check if Super Admin role exists
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $this->info('✅ Super Admin role exists');
            $this->info('Permissions: ' . $superAdminRole->permissions->count());
        } else {
            $this->error('❌ Super Admin role not found');
        }

        // Check admin user
        $admin = User::where('email', 'admin@nutrition.com')->first();
        if ($admin) {
            $this->info('✅ Admin user found: ' . $admin->name);
            $this->info('Roles: ' . $admin->roles->pluck('name')->implode(', '));
            $this->info('Has Super Admin role: ' . ($admin->hasRole('Super Admin') ? 'Yes' : 'No'));
            $this->info('Can view dashboard: ' . ($admin->can('view-dashboard') ? 'Yes' : 'No'));
        } else {
            $this->error('❌ Admin user not found');
        }

        // Check all roles
        $this->info('\nAll Roles:');
        foreach (Role::all() as $role) {
            $this->info('- ' . $role->name . ' (' . $role->permissions->count() . ' permissions)');
        }

        return 0;
    }
}
