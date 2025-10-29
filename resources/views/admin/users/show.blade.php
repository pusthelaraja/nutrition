@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ $user->name }}</h1>
            <p class="text-muted">User details and role information</p>
        </div>
        <div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit User
            </a>
        </div>
    </div>

    <div class="row">
        <!-- User Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Full Name</h6>
                            <p class="text-muted">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Email Address</h6>
                            <p class="text-muted">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Account Created</h6>
                            <p class="text-muted">{{ $user->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Last Updated</h6>
                            <p class="text-muted">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    @if($user->id === auth()->id())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Current User:</strong> This is your own account.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Roles -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Roles ({{ $user->roles->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($user->roles->count() > 0)
                        <div class="row">
                            @foreach($user->roles as $role)
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $role->name }}</h6>
                                            <p class="text-muted small mb-2">Permissions ({{ $role->permissions->count() }}):</p>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($role->permissions as $permission)
                                                    <span class="badge bg-info">{{ $permission->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-shield fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No roles assigned</h5>
                            <p class="text-muted">This user doesn't have any roles yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Direct Permissions -->
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">Direct Permissions ({{ $user->permissions->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($user->permissions->count() > 0)
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($user->permissions as $permission)
                                <span class="badge bg-success">{{ $permission->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-key fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No direct permissions</h5>
                            <p class="text-muted">This user doesn't have any direct permissions assigned.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit User
                        </a>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-shield"></i> Manage Roles
                        </a>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-key"></i> Manage Permissions
                        </a>
                        @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-trash"></i> Delete User
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
