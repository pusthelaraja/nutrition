<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        try {
            $roles = Role::with('permissions')->paginate(15);
            return view('admin.roles.index', compact('roles'));
        } catch (\Exception $e) {
            \Log::error('Roles listing failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load roles.');
        }
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        try {
            $permissions = Permission::all();
            return view('admin.roles.create', compact('permissions'));
        } catch (\Exception $e) {
            \Log::error('Role creation form failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load role creation form.');
        }
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
                'permissions' => 'array'
            ]);

            $role = Role::create(['name' => $request->name]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            \Log::error('Role creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create role.');
        }
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        try {
            $role->load('permissions');
            $users = $role->users;
            return view('admin.roles.show', compact('role', 'users'));
        } catch (\Exception $e) {
            \Log::error('Role details failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load role details.');
        }
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        try {
            $permissions = Permission::all();
            $role->load('permissions');
            return view('admin.roles.edit', compact('role', 'permissions'));
        } catch (\Exception $e) {
            \Log::error('Role edit form failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load role edit form.');
        }
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
                'permissions' => 'array'
            ]);

            $role->update(['name' => $request->name]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }

            return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Role update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update role.');
        }
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        try {
            // Prevent deleting admin role
            if ($role->name === 'admin') {
                return redirect()->back()->with('error', 'Cannot delete the admin role.');
            }

            $role->delete();
            return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Role deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete role.');
        }
    }
}
