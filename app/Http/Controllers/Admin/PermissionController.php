<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions.
     */
    public function index()
    {
        try {
            $permissions = Permission::with('roles')->paginate(15);
            return view('admin.permissions.index', compact('permissions'));
        } catch (\Exception $e) {
            \Log::error('Permissions listing failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load permissions.');
        }
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        try {
            return view('admin.permissions.create');
        } catch (\Exception $e) {
            \Log::error('Permission creation form failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load permission creation form.');
        }
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:permissions,name',
            ]);

            Permission::create(['name' => $request->name]);

            return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
        } catch (\Exception $e) {
            \Log::error('Permission creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create permission.');
        }
    }

    /**
     * Display the specified permission.
     */
    public function show(Permission $permission)
    {
        try {
            $permission->load('roles');
            $roles = $permission->roles;
            return view('admin.permissions.show', compact('permission', 'roles'));
        } catch (\Exception $e) {
            \Log::error('Permission details failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load permission details.');
        }
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission)
    {
        try {
            return view('admin.permissions.edit', compact('permission'));
        } catch (\Exception $e) {
            \Log::error('Permission edit form failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load permission edit form.');
        }
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, Permission $permission)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            ]);

            $permission->update(['name' => $request->name]);

            return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Permission update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update permission.');
        }
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(Permission $permission)
    {
        try {
            $permission->delete();
            return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Permission deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete permission.');
        }
    }
}
