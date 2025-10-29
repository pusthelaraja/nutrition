@extends('layouts.admin')

@section('title', 'Edit Permission')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Edit Permission</h1>
            <p class="text-muted">Update permission information</p>
        </div>
        <div>
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Permissions
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">Permission Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $permission->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Use kebab-case format (e.g., manage-inventory)</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Permission
                            </button>
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
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
                    <h5 class="card-title mb-0">Permission Details</h5>
                </div>
                <div class="card-body">
                    <h6>Current Name</h6>
                    <p class="text-muted">{{ $permission->name }}</p>

                    <h6>Created</h6>
                    <p class="text-muted">{{ $permission->created_at->format('M d, Y H:i') }}</p>

                    <h6>Roles with this Permission</h6>
                    @if($permission->roles->count() > 0)
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($permission->roles as $role)
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No roles have this permission</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
