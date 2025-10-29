@extends('layouts.admin')

@section('title', 'Shipping Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Shipping Zones</h1>
            <p class="text-muted">Manage shipping zones and delivery areas</p>
        </div>
        <div>
            <a href="{{ route('admin.shipping.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Shipping Zone
            </a>
        </div>
    </div>

    <!-- Shipping Zones Table -->
    <div class="card shadow">
        <div class="card-body">
            @if($shippingZones->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Pincodes</th>
                                <th>Rates</th>
                                <th>Status</th>
                                <th>Sort Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shippingZones as $zone)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $zone->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-muted">
                                            {{ Str::limit($zone->description, 50) }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $zone->pincodeZones->count() }} pincodes
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $zone->shippingRates->count() }} rates
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $zone->is_active ? 'success' : 'danger' }}">
                                            {{ $zone->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $zone->sort_order }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.shipping.show', $zone) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.shipping.edit', $zone) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.shipping.rates', $zone) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-dollar-sign"></i>
                                            </a>
                                            <a href="{{ route('admin.shipping.pincodes', $zone) }}" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.shipping.destroy', $zone) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this shipping zone?')">
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
                    {{ $shippingZones->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shipping-fast fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No shipping zones found</h5>
                    <p class="text-muted">Create your first shipping zone to start managing delivery areas.</p>
                    <a href="{{ route('admin.shipping.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Shipping Zone
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
