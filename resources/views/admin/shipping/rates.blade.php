@extends('layouts.admin')

@section('title', 'Shipping Rates')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Shipping Rates - {{ $shipping->name }}</h1>
            <p class="text-muted">Manage shipping rates for this zone</p>
        </div>
        <div>
            <a href="{{ route('admin.shipping.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Shipping Zones
            </a>
            <a href="{{ route('admin.shipping.show', $shipping) }}" class="btn btn-outline-primary">
                <i class="fas fa-eye"></i> View Zone
            </a>
        </div>
    </div>

    <!-- Shipping Rates -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Shipping Rates</h6>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addRateModal">
                <i class="fas fa-plus"></i> Add Rate
            </button>
        </div>
        <div class="card-body">
            @if($rates->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Method</th>
                                <th>Base Rate</th>
                                <th>Rate per KG</th>
                                <th>Free Shipping</th>
                                <th>Est. Days</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rates as $rate)
                                <tr>
                                    <td>
                                        <strong>{{ $rate->shipping_method_id ?? 'Standard' }}</strong>
                                    </td>
                                    <td>₹{{ number_format($rate->base_rate, 2) }}</td>
                                    <td>₹{{ number_format($rate->rate_per_kg, 2) }}</td>
                                    <td>₹{{ number_format($rate->free_shipping_threshold, 2) }}</td>
                                    <td>{{ $rate->estimated_days }} days</td>
                                    <td>
                                        <span class="badge bg-{{ $rate->is_active ? 'success' : 'danger' }}">
                                            {{ $rate->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="editRate({{ $rate->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.shipping.rates.destroy', $rate) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this rate?')">
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
            @else
                <div class="text-center py-5">
                    <i class="fas fa-dollar-sign fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No shipping rates found</h5>
                    <p class="text-muted">Add shipping rates for this zone to start managing delivery costs.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRateModal">
                        <i class="fas fa-plus"></i> Add First Rate
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Rate Modal -->
<div class="modal fade" id="addRateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Shipping Rate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.shipping.rates.store', $shipping) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="shipping_method_id" class="form-label">Shipping Method</label>
                        <select name="shipping_method_id" id="shipping_method_id" class="form-select" required>
                            <option value="">Select Method</option>
                            <option value="1">Standard Delivery</option>
                            <option value="2">Express Delivery</option>
                            <option value="3">Same Day Delivery</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="base_rate" class="form-label">Base Rate (₹)</label>
                            <input type="number" class="form-control" id="base_rate" name="base_rate" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="rate_per_kg" class="form-label">Rate per KG (₹)</label>
                            <input type="number" class="form-control" id="rate_per_kg" name="rate_per_kg" step="0.01">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="free_shipping_threshold" class="form-label">Free Shipping Above (₹)</label>
                            <input type="number" class="form-control" id="free_shipping_threshold" name="free_shipping_threshold" step="0.01">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estimated_days" class="form-label">Estimated Days</label>
                            <input type="number" class="form-control" id="estimated_days" name="estimated_days" min="1" required>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                        <label class="form-check-label" for="is_active">
                            Active Rate
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Rate</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
