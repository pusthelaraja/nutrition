@extends('layouts.admin')

@section('title', 'Create Shipping Zone')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Create Shipping Zone</h1>
            <p class="text-muted">Add a new shipping zone for delivery management</p>
        </div>
        <div>
            <a href="{{ route('admin.shipping.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Shipping Zones
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Shipping Zone Details</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.shipping.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Zone Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3"
                                      placeholder="Describe this shipping zone...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                       id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active Zone
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.shipping.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Shipping Zone
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
                    <h6 class="m-0 font-weight-bold text-primary">Shipping Zone Help</h6>
                </div>
                <div class="card-body">
                    <h6>What is a Shipping Zone?</h6>
                    <p class="text-muted small">
                        A shipping zone defines a geographical area where you deliver products.
                        You can set different shipping rates for different zones.
                    </p>

                    <h6>Next Steps:</h6>
                    <ol class="small text-muted">
                        <li>Create the shipping zone</li>
                        <li>Add pincodes to this zone</li>
                        <li>Set shipping rates for this zone</li>
                        <li>Configure warehouse shipping rates</li>
                    </ol>

                    <h6>Examples:</h6>
                    <ul class="small text-muted">
                        <li>Metro Cities (Delhi, Mumbai, Bangalore)</li>
                        <li>North India</li>
                        <li>South India</li>
                        <li>Remote Areas</li>
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
                        <span>Total Zones:</span>
                        <strong>{{ \App\Models\ShippingZone::count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Active Zones:</span>
                        <strong>{{ \App\Models\ShippingZone::where('is_active', true)->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Total Pincodes:</span>
                        <strong>{{ \App\Models\PincodeZone::count() }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
