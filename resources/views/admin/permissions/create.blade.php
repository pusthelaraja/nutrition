@extends('layouts.admin')

@section('title', 'Create Permission')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Create Permission</h1>
            <p class="text-muted">Create a new system permission</p>
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
                    <form action="{{ route('admin.permissions.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" required placeholder="e.g., manage-inventory">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Use kebab-case format (e.g., manage-products, view-reports)</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Permission
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
                    <h5 class="card-title mb-0">Help</h5>
                </div>
                <div class="card-body">
                    <h6>Permission Name</h6>
                    <p class="text-muted small">Enter a unique name for the permission. Use kebab-case format for consistency.</p>

                    <h6>Naming Convention</h6>
                    <ul class="list-unstyled small">
                        <li><strong>view-*:</strong> Read-only access</li>
                        <li><strong>manage-*:</strong> Full CRUD access</li>
                        <li><strong>create-*:</strong> Create new records</li>
                        <li><strong>edit-*:</strong> Edit existing records</li>
                        <li><strong>delete-*:</strong> Delete records</li>
                    </ul>

                    <h6>Examples</h6>
                    <ul class="list-unstyled small">
                        <li>• manage-products</li>
                        <li>• view-reports</li>
                        <li>• create-orders</li>
                        <li>• edit-users</li>
                        <li>• delete-coupons</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
