@extends('layouts.admin')

@section('title', 'Create Coupon')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Create Coupon</h1>
            <p class="text-muted">Add a new discount coupon</p>
        </div>
        <div>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Coupons
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Coupon Details</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.coupons.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                       id="code" name="code" value="{{ old('code') }}" required maxlength="50"
                                       placeholder="e.g., WELCOME10">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">This code will be used by customers to apply the discount.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Coupon Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required maxlength="255">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3"
                                      placeholder="Describe this coupon...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">Discount Type <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Select Type</option>
                                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                    <option value="fixed_amount" {{ old('type') == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                                    <option value="free_shipping" {{ old('type') == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="value" class="form-label">Discount Value <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('value') is-invalid @enderror"
                                       id="value" name="value" value="{{ old('value') }}" required min="0" step="0.01">
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="minimum_amount" class="form-label">Minimum Order Amount</label>
                                <input type="number" class="form-control @error('minimum_amount') is-invalid @enderror"
                                       id="minimum_amount" name="minimum_amount" value="{{ old('minimum_amount') }}" min="0" step="0.01">
                                @error('minimum_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="maximum_discount" class="form-label">Maximum Discount</label>
                                <input type="number" class="form-control @error('maximum_discount') is-invalid @enderror"
                                       id="maximum_discount" name="maximum_discount" value="{{ old('maximum_discount') }}" min="0" step="0.01">
                                @error('maximum_discount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">For percentage discounts only</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="usage_limit" class="form-label">Total Usage Limit</label>
                                <input type="number" class="form-control @error('usage_limit') is-invalid @enderror"
                                       id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" min="1">
                                @error('usage_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="usage_limit_per_user" class="form-label">Usage Limit Per User</label>
                                <input type="number" class="form-control @error('usage_limit_per_user') is-invalid @enderror"
                                       id="usage_limit_per_user" name="usage_limit_per_user" value="{{ old('usage_limit_per_user') }}" min="1">
                                @error('usage_limit_per_user')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="starts_at" class="form-label">Start Date</label>
                                <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror"
                                       id="starts_at" name="starts_at" value="{{ old('starts_at') }}">
                                @error('starts_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="expires_at" class="form-label">Expiry Date</label>
                                <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror"
                                       id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                                @error('expires_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active Coupon
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="stackable" name="stackable"
                                           {{ old('stackable') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="stackable">
                                        Stackable with other coupons
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Coupon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Help Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Coupon Help</h6>
                </div>
                <div class="card-body">
                    <h6>Coupon Types:</h6>
                    <ul class="small text-muted">
                        <li><strong>Fixed Amount:</strong> Deducts a fixed amount (e.g., ₹50 off)</li>
                        <li><strong>Percentage:</strong> Deducts a percentage (e.g., 10% off)</li>
                    </ul>

                    <h6>Usage Limits:</h6>
                    <ul class="small text-muted">
                        <li><strong>Total Limit:</strong> How many times the coupon can be used</li>
                        <li><strong>Per User Limit:</strong> How many times one user can use it</li>
                    </ul>

                    <h6>Examples:</h6>
                    <ul class="small text-muted">
                        <li><code>WELCOME10</code> - 10% off for new users</li>
                        <li><code>SAVE50</code> - ₹50 off on orders above ₹500</li>
                        <li><code>FREESHIP</code> - Free shipping coupon</li>
                    </ul>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Coupons:</span>
                        <strong>{{ \App\Models\Coupon::count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Active Coupons:</span>
                        <strong>{{ \App\Models\Coupon::where('is_active', true)->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Total Usage:</span>
                        <strong>{{ \App\Models\CouponUsage::count() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
