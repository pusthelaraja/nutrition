@extends('layouts.admin')

@section('title', 'Shipping Zone Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ $shipping->name }}</h1>
            <p class="text-muted">Shipping zone details and management</p>
        </div>
        <div>
            <a href="{{ route('admin.shipping.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Shipping Zones
            </a>
            <a href="{{ route('admin.shipping.edit', $shipping) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Zone
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Zone Details -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Zone Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Zone Name:</strong><br>
                            {{ $shipping->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong><br>
                            <span class="badge bg-{{ $shipping->is_active ? 'success' : 'danger' }}">
                                {{ $shipping->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    @if($shipping->description)
                        <div class="mt-3">
                            <strong>Description:</strong><br>
                            {{ $shipping->description }}
                        </div>
                    @endif
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <strong>Sort Order:</strong> {{ $shipping->sort_order }}
                        </div>
                        <div class="col-md-6">
                            <strong>Created:</strong> {{ $shipping->created_at->format('M d, Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pincodes -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Pincodes ({{ $shipping->pincodeZones->count() }})</h6>
                    <a href="{{ route('admin.shipping.pincodes', $shipping) }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-map-marker-alt"></i> Manage Pincodes
                    </a>
                </div>
                <div class="card-body">
                    @if($shipping->pincodeZones->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Pincode</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Country</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shipping->pincodeZones->take(10) as $pincode)
                                        <tr>
                                            <td><strong>{{ $pincode->pincode }}</strong></td>
                                            <td>{{ $pincode->city }}</td>
                                            <td>{{ $pincode->state }}</td>
                                            <td>{{ $pincode->country }}</td>
                                            <td>
                                                <span class="badge bg-{{ $pincode->is_active ? 'success' : 'danger' }}">
                                                    {{ $pincode->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($shipping->pincodeZones->count() > 10)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.shipping.pincodes', $shipping) }}" class="btn btn-sm btn-outline-primary">
                                    View All {{ $shipping->pincodeZones->count() }} Pincodes
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No pincodes added to this zone</p>
                            <a href="{{ route('admin.shipping.pincodes', $shipping) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Pincodes
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Shipping Rates -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Shipping Rates ({{ $shipping->shippingRates->count() }})</h6>
                    <a href="{{ route('admin.shipping.rates', $shipping) }}" class="btn btn-sm btn-outline-info">
                        <i class="fas fa-dollar-sign"></i> Manage Rates
                    </a>
                </div>
                <div class="card-body">
                    @if($shipping->shippingRates->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th>Base Rate</th>
                                        <th>Rate per KG</th>
                                        <th>Free Shipping</th>
                                        <th>Est. Days</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shipping->shippingRates as $rate)
                                        <tr>
                                            <td>{{ $rate->shipping_method_id ?? 'Standard' }}</td>
                                            <td>₹{{ number_format($rate->base_rate, 2) }}</td>
                                            <td>₹{{ number_format($rate->rate_per_kg, 2) }}</td>
                                            <td>₹{{ number_format($rate->free_shipping_threshold, 2) }}</td>
                                            <td>{{ $rate->estimated_days }} days</td>
                                            <td>
                                                <span class="badge bg-{{ $rate->is_active ? 'success' : 'danger' }}">
                                                    {{ $rate->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-dollar-sign fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No shipping rates configured</p>
                            <a href="{{ route('admin.shipping.rates', $shipping) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Shipping Rates
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Pincodes:</span>
                        <strong>{{ $shipping->pincodeZones->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Active Pincodes:</span>
                        <strong>{{ $shipping->pincodeZones->where('is_active', true)->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping Rates:</span>
                        <strong>{{ $shipping->shippingRates->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Active Rates:</span>
                        <strong>{{ $shipping->shippingRates->where('is_active', true)->count() }}</strong>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.shipping.edit', $shipping) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit Zone
                        </a>
                        <a href="{{ route('admin.shipping.pincodes', $shipping) }}" class="btn btn-outline-warning">
                            <i class="fas fa-map-marker-alt"></i> Manage Pincodes
                        </a>
                        <a href="{{ route('admin.shipping.rates', $shipping) }}" class="btn btn-outline-info">
                            <i class="fas fa-dollar-sign"></i> Manage Rates
                        </a>
                    </div>
                </div>
            </div>

            <!-- Zone History -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Zone History</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Created:</strong><br>
                        {{ $shipping->created_at->format('M d, Y H:i') }}
                    </div>
                    <div class="mb-2">
                        <strong>Last Updated:</strong><br>
                        {{ $shipping->updated_at->format('M d, Y H:i') }}
                    </div>
                    <div class="mb-2">
                        <strong>Sort Order:</strong><br>
                        {{ $shipping->sort_order }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
