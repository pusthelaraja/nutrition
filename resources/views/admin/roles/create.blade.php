@extends('layouts.admin')

@section('title', 'Create Role')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Create Role</h1>
            <p class="text-muted">Create a new user role with permissions</p>
        </div>
        <div>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Roles
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">Role Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            <div class="row">
                                @foreach($permissions as $permission)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                   value="{{ $permission->id }}" id="permission_{{ $permission->id }}"
                                                   {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('permissions')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Role
                            </button>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">Help</h5>
                </div>
                <div class="card-body">
                    <h6>Role Name</h6>
                    <p class="text-muted small">Enter a unique name for the role (e.g., manager, editor, viewer).</p>

                    <h6>Permissions</h6>
                    <p class="text-muted small">Select the permissions this role should have. Users with this role will be able to perform these actions.</p>

                    <h6>Available Permissions</h6>
                    <ul class="list-unstyled small">
                        <li><strong>view-dashboard:</strong> Access admin dashboard</li>
                        <li><strong>manage-products:</strong> Create, edit, delete products</li>
                        <li><strong>manage-categories:</strong> Manage product categories</li>
                        <li><strong>manage-orders:</strong> Process and manage orders</li>
                        <li><strong>manage-customers:</strong> View and manage customers</li>
                        <li><strong>view-activity-logs:</strong> View system activity logs</li>
                        <li><strong>manage-users:</strong> Manage users and roles</li>
                        <li><strong>manage-settings:</strong> Access system settings</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
