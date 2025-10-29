@extends('layouts.admin')

@section('title', 'Edit Shipping Zone')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Edit Shipping Zone</h1>
            <p class="text-muted">Update shipping zone details</p>
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
                    <form method="POST" action="{{ route('admin.shipping.update', $shipping) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Zone Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $shipping->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3"
                                      placeholder="Describe this shipping zone...">{{ old('description', $shipping->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label">Sort Order</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                       id="sort_order" name="sort_order" value="{{ old('sort_order', $shipping->sort_order) }}" min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                           {{ old('is_active', $shipping->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active Zone
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.shipping.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Shipping Zone
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Zone Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Zone Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Created:</strong> {{ $shipping->created_at->format('M d, Y') }}
                    </div>
                    <div class="mb-2">
                        <strong>Last Updated:</strong> {{ $shipping->updated_at->format('M d, Y H:i') }}
                    </div>
                    <div class="mb-2">
                        <strong>Pincodes:</strong> {{ $shipping->pincodeZones->count() }}
                    </div>
                    <div class="mb-2">
                        <strong>Shipping Rates:</strong> {{ $shipping->shippingRates->count() }}
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.shipping.pincodes', $shipping) }}" class="btn btn-outline-warning">
                            <i class="fas fa-map-marker-alt"></i> Manage Pincodes
                        </a>
                        <a href="{{ route('admin.shipping.rates', $shipping) }}" class="btn btn-outline-info">
                            <i class="fas fa-dollar-sign"></i> Manage Rates
                        </a>
                        <a href="{{ route('admin.shipping.show', $shipping) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
