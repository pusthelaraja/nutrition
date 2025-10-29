<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions for all admin modules
        $permissions = [
            // Dashboard
            'view-dashboard',

            // Products
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',

            // Categories
            'view-categories',
            'create-categories',
            'edit-categories',
            'delete-categories',

            // Orders
            'view-orders',
            'create-orders',
            'edit-orders',
            'delete-orders',
            'update-order-status',

            // Shipping
            'view-shipping',
            'create-shipping',
            'edit-shipping',
            'delete-shipping',
            'manage-shipping-zones',
            'manage-shipping-rates',

            // Coupons
            'view-coupons',
            'create-coupons',
            'edit-coupons',
            'delete-coupons',

            // Customers
            'view-customers',
            'create-customers',
            'edit-customers',
            'delete-customers',

            // User Management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',

            // Roles
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',

            // Permissions
            'view-permissions',
            'create-permissions',
            'edit-permissions',
            'delete-permissions',

            // File Manager
            'view-file-manager',
            'upload-files',
            'delete-files',
            'manage-files',

            // Inventory
            'view-inventory',
            'manage-inventory',
            'view-stock-reports',
            'manage-stock-alerts',

            // Activity Logs
            'view-activity-logs',
            'export-activity-logs',

            // System Settings
            'view-settings',
            'manage-settings',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $editorRole = Role::firstOrCreate(['name' => 'Editor']);
        $viewerRole = Role::firstOrCreate(['name' => 'Viewer']);

        // Super Admin gets all permissions
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin gets most permissions (except user/role management)
        $adminPermissions = [
            'view-dashboard',
            'view-products', 'create-products', 'edit-products', 'delete-products',
            'view-categories', 'create-categories', 'edit-categories', 'delete-categories',
            'view-orders', 'create-orders', 'edit-orders', 'delete-orders', 'update-order-status',
            'view-shipping', 'create-shipping', 'edit-shipping', 'delete-shipping',
            'manage-shipping-zones', 'manage-shipping-rates',
            'view-coupons', 'create-coupons', 'edit-coupons', 'delete-coupons',
            'view-customers', 'create-customers', 'edit-customers', 'delete-customers',
            'view-file-manager', 'upload-files', 'delete-files', 'manage-files',
            'view-inventory', 'manage-inventory', 'view-stock-reports', 'manage-stock-alerts',
            'view-activity-logs', 'export-activity-logs',
            'view-settings', 'manage-settings',
        ];
        $adminRole->givePermissionTo($adminPermissions);

        // Manager gets content management permissions
        $managerPermissions = [
            'view-dashboard',
            'view-products', 'create-products', 'edit-products',
            'view-categories', 'create-categories', 'edit-categories',
            'view-orders', 'edit-orders', 'update-order-status',
            'view-shipping', 'create-shipping', 'edit-shipping',
            'view-coupons', 'create-coupons', 'edit-coupons',
            'view-customers', 'edit-customers',
            'view-file-manager', 'upload-files', 'manage-files',
            'view-inventory', 'manage-inventory', 'view-stock-reports',
            'view-activity-logs',
        ];
        $managerRole->givePermissionTo($managerPermissions);

        // Editor gets limited permissions
        $editorPermissions = [
            'view-dashboard',
            'view-products', 'create-products', 'edit-products',
            'view-categories', 'create-categories', 'edit-categories',
            'view-orders',
            'view-coupons', 'create-coupons', 'edit-coupons',
            'view-customers',
            'view-file-manager', 'upload-files',
            'view-inventory',
        ];
        $editorRole->givePermissionTo($editorPermissions);

        // Viewer gets read-only permissions
        $viewerPermissions = [
            'view-dashboard',
            'view-products',
            'view-categories',
            'view-orders',
            'view-shipping',
            'view-coupons',
            'view-customers',
            'view-file-manager',
            'view-inventory',
            'view-activity-logs',
        ];
        $viewerRole->givePermissionTo($viewerPermissions);

        $this->command->info('Permissions and roles created successfully!');
    }
}
