@extends('layouts.admin')

@section('title', 'Coupon Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Coupon Management</h1>
            <p class="text-muted">Manage discount coupons and promotional codes</p>
        </div>
        <div>
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Coupon
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="fixed_amount" {{ request('type') == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount</option>
                        <option value="free_shipping" {{ request('type') == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary mt-4">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary mt-4">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="card shadow">
        <div class="card-body">
            @if($coupons->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Value</th>
                                <th>Usage</th>
                                <th>Status</th>
                                <th>Expires</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $coupon->code }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $coupon->name }}</strong>
                                            @if($coupon->description)
                                                <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($coupon->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $coupon->type == 'fixed_amount' ? 'primary' : ($coupon->type == 'percentage' ? 'info' : 'success') }}">
                                            {{ ucfirst(str_replace('_', ' ', $coupon->type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($coupon->type == 'fixed_amount')
                                            ₹{{ number_format($coupon->value, 2) }}
                                        @elseif($coupon->type == 'percentage')
                                            {{ $coupon->value }}%
                                        @else
                                            Free Shipping
                                        @endif
                                        @if($coupon->minimum_amount)
                                            <br><small class="text-muted">Min: ₹{{ number_format($coupon->minimum_amount, 2) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $coupon->used_count }}/{{ $coupon->usage_limit ?? '∞' }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        @if($coupon->expires_at && $coupon->expires_at < now())
                                            <span class="badge bg-danger">Expired</span>
                                        @elseif($coupon->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($coupon->expires_at)
                                            {{ $coupon->expires_at->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.coupons.toggle-status', $coupon) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-{{ $coupon->is_active ? 'warning' : 'success' }}"
                                                        title="{{ $coupon->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas fa-{{ $coupon->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this coupon?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $coupons->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No coupons found</h5>
                    <p class="text-muted">Create your first coupon to start offering discounts to customers.</p>
                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create First Coupon
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
