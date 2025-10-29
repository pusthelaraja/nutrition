@extends('layouts.admin')

@section('title', 'Coupon Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ $coupon->name }}</h1>
            <p class="text-muted">Coupon code: <code>{{ $coupon->code }}</code></p>
        </div>
        <div>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Coupons
            </a>
            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Coupon
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Coupon Details -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Coupon Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Coupon Code:</strong><br>
                            <code class="fs-5">{{ $coupon->code }}</code>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong><br>
                            @if($coupon->expires_at && $coupon->expires_at < now())
                                <span class="badge bg-danger">Expired</span>
                            @elseif($coupon->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>
                    @if($coupon->description)
                        <div class="mt-3">
                            <strong>Description:</strong><br>
                            {{ $coupon->description }}
                        </div>
                    @endif
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <strong>Discount Type:</strong>
                            <span class="badge bg-{{ $coupon->type == 'fixed' ? 'primary' : 'info' }}">
                                {{ ucfirst($coupon->type) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Discount Value:</strong>
                            @if($coupon->type == 'fixed')
                                ₹{{ number_format($coupon->value, 2) }}
                            @else
                                {{ $coupon->value }}%
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Usage Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h3 text-primary">{{ $coupon->used_count }}</div>
                                <div class="text-muted">Times Used</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h3 text-success">₹{{ number_format($coupon->couponUsages->sum('discount_amount'), 2) }}</div>
                                <div class="text-muted">Total Discount</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h3 text-info">{{ $coupon->couponUsages->pluck('user_id')->unique()->count() }}</div>
                                <div class="text-muted">Unique Users</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <div class="h3 text-warning">{{ $coupon->usage_limit ?? '∞' }}</div>
                                <div class="text-muted">Usage Limit</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Usage -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Usage</h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Usage tracking will be available when customers start using this coupon</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Coupon Settings -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Coupon Settings</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Minimum Amount:</strong><br>
                        {{ $coupon->minimum_amount ? '₹' . number_format($coupon->minimum_amount, 2) : 'No minimum' }}
                    </div>
                    <div class="mb-2">
                        <strong>Maximum Discount:</strong><br>
                        {{ $coupon->maximum_discount ? '₹' . number_format($coupon->maximum_discount, 2) : 'No limit' }}
                    </div>
                    <div class="mb-2">
                        <strong>Usage Limit:</strong><br>
                        {{ $coupon->usage_limit ?? 'Unlimited' }}
                    </div>
                    <div class="mb-2">
                        <strong>Per User Limit:</strong><br>
                        {{ $coupon->usage_limit_per_user ?? 'Unlimited' }}
                    </div>
                    <div class="mb-2">
                        <strong>Stackable:</strong><br>
                        <span class="badge bg-{{ $coupon->stackable ? 'success' : 'danger' }}">
                            {{ $coupon->stackable ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Date Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Date Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Created:</strong><br>
                        {{ $coupon->created_at->format('M d, Y H:i') }}
                    </div>
                    <div class="mb-2">
                        <strong>Last Updated:</strong><br>
                        {{ $coupon->updated_at->format('M d, Y H:i') }}
                    </div>
                    @if($coupon->starts_at)
                        <div class="mb-2">
                            <strong>Starts:</strong><br>
                            {{ $coupon->starts_at->format('M d, Y H:i') }}
                        </div>
                    @endif
                    @if($coupon->expires_at)
                        <div class="mb-2">
                            <strong>Expires:</strong><br>
                            {{ $coupon->expires_at->format('M d, Y H:i') }}
                            @if($coupon->expires_at < now())
                                <span class="badge bg-danger ms-2">Expired</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit Coupon
                        </a>
                        <form method="POST" action="{{ route('admin.coupons.toggle-status', $coupon) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-{{ $coupon->is_active ? 'warning' : 'success' }} w-100">
                                <i class="fas fa-{{ $coupon->is_active ? 'pause' : 'play' }}"></i>
                                {{ $coupon->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this coupon?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash"></i> Delete Coupon
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
