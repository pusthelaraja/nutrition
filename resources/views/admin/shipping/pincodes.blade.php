@extends('layouts.admin')

@section('title', 'Zone Pincodes')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Pincodes - {{ $shipping->name }}</h1>
            <p class="text-muted">Manage pincodes for this shipping zone</p>
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

    <!-- Add Pincode Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add New Pincode</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.shipping.add-pincode', $shipping) }}">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="pincode" class="form-label">Pincode <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('pincode') is-invalid @enderror"
                               id="pincode" name="pincode" value="{{ old('pincode') }}" required maxlength="10">
                        @error('pincode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                               id="city" name="city" value="{{ old('city') }}" required>
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('state') is-invalid @enderror"
                               id="state" name="state" value="{{ old('state') }}" required>
                        @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('country') is-invalid @enderror"
                               id="country" name="country" value="{{ old('country', 'India') }}" required>
                        @error('country')
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
                                Active Pincode
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Pincode
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Pincodes List -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Zone Pincodes ({{ $pincodes->total() }})</h6>
        </div>
        <div class="card-body">
            @if($pincodes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Pincode</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Country</th>
                                <th>Status</th>
                                <th>Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pincodes as $pincode)
                                <tr>
                                    <td>
                                        <strong>{{ $pincode->pincode }}</strong>
                                    </td>
                                    <td>{{ $pincode->city }}</td>
                                    <td>{{ $pincode->state }}</td>
                                    <td>{{ $pincode->country }}</td>
                                    <td>
                                        <span class="badge bg-{{ $pincode->is_active ? 'success' : 'danger' }}">
                                            {{ $pincode->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $pincode->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.shipping.remove-pincode', $pincode) }}"
                                              class="d-inline" onsubmit="return confirm('Are you sure you want to remove this pincode?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $pincodes->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No pincodes found</h5>
                    <p class="text-muted">Add pincodes to this zone to start managing delivery areas.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
