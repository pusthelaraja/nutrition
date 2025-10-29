@extends('layouts.admin')

@section('title', 'Permission Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ $permission->name }}</h1>
            <p class="text-muted">Permission details and role assignments</p>
        </div>
        <div>
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Permissions
            </a>
            <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Permission
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Permission Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Permission Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Permission Name</h6>
                            <p class="text-muted">{{ $permission->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Created</h6>
                            <p class="text-muted">{{ $permission->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles with this Permission -->
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">Roles with this Permission ({{ $roles->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($roles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Role Name</th>
                                        <th>Total Permissions</th>
                                        <th>Users</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.roles.show', $role) }}" class="text-decoration-none">
                                                    {{ $role->name }}
                                                </a>
                                            </td>
                                            <td>{{ $role->permissions->count() }}</td>
                                            <td>{{ $role->users->count() }}</td>
                                            <td>{{ $role->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-shield fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No roles assigned</h5>
                            <p class="text-muted">No roles have this permission yet.</p>
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
                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Permission
                        </a>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-shield"></i> Manage Roles
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-users"></i> Manage Users
                        </a>
                        <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this permission?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash"></i> Delete Permission
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
