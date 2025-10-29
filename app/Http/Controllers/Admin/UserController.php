<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        try {
            $users = User::with('roles')->paginate(15);
            $roles = Role::all();
            $permissions = Permission::all();

            return view('admin.users.index', compact('users', 'roles', 'permissions'));
        } catch (\Exception $e) {
            \Log::error('Users listing failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load users.');
        }
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        try {
            $roles = Role::all();
            return view('admin.users.create', compact('roles'));
        } catch (\Exception $e) {
            \Log::error('User creation form failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load user creation form.');
        }
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'roles' => 'array'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            if ($request->has('roles')) {
                $user->assignRole($request->roles);
            }

            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            \Log::error('User creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create user.');
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        try {
            $user->load('roles', 'permissions');
            return view('admin.users.show', compact('user'));
        } catch (\Exception $e) {
            \Log::error('User details failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load user details.');
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        try {
            $roles = Role::all();
            $user->load('roles');
            return view('admin.users.edit', compact('user', 'roles'));
        } catch (\Exception $e) {
            \Log::error('User edit form failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load user edit form.');
        }
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'roles' => 'array'
            ]);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            \Log::error('User update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update user.');
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        try {
            // Prevent deleting the current user
            if ($user->id === auth()->id()) {
                return redirect()->back()->with('error', 'You cannot delete your own account.');
            }

            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('User deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete user.');
        }
    }
}
