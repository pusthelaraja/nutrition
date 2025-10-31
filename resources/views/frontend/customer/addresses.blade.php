@extends('layouts.frontend')

@section('title', 'My Addresses - ' . config('app.name'))

@section('content')
<!-- Enhanced Breadcrumb -->
<nav aria-label="breadcrumb" class="py-3" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-white-50">
                    <i class="fas fa-home me-1"></i>Home
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('customer.dashboard') }}" class="text-white-50">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active text-white" aria-current="page">
                <i class="fas fa-map-marker-alt me-1"></i>My Addresses
            </li>
        </ol>
    </div>
</nav>

<!-- Addresses Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="addresses-header bg-white rounded-4 shadow-sm p-4 mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h2 class="fw-bold text-dark mb-0">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>My Addresses
                            </h2>
                            <p class="text-muted mb-0">Manage your shipping addresses</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="fas fa-plus me-1"></i>Add New Address
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($addresses->count() > 0)
            <div class="row">
                @foreach($addresses as $address)
                    <div class="col-lg-6 mb-4">
                        <div class="address-card bg-white rounded-4 shadow-sm p-4 h-100"
                             data-address-id="{{ $address->id }}"
                             data-first-name="{{ $address->first_name }}"
                             data-last-name="{{ $address->last_name }}"
                             data-address-line-1="{{ $address->address_line_1 }}"
                             data-address-line-2="{{ $address->address_line_2 ?? '' }}"
                             data-city="{{ $address->city }}"
                             data-state="{{ $address->state }}"
                             data-postal-code="{{ $address->postal_code }}"
                             data-country="{{ $address->country }}"
                             data-phone="{{ $address->phone }}"
                             data-email="{{ $address->email ?? '' }}"
                             data-is-default="{{ $address->is_default ? '1' : '0' }}">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="fw-bold text-dark mb-1">{{ $address->first_name }} {{ $address->last_name }}</h5>
                                    @if($address->is_default)
                                        <span class="badge bg-primary">Default Address</span>
                                    @endif
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="editAddress({{ $address->id }})">
                                            <i class="fas fa-edit me-2"></i>Edit
                                        </a></li>
                                        @if(!$address->is_default)
                                            <li><a class="dropdown-item" href="#" onclick="setDefaultAddress({{ $address->id }})">
                                                <i class="fas fa-star me-2"></i>Set as Default
                                            </a></li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteAddress({{ $address->id }})">
                                            <i class="fas fa-trash me-2"></i>Delete
                                        </a></li>
                                    </ul>
                                </div>
                            </div>

                            <address class="mb-3">
                                {{ $address->address_line_1 }}<br>
                                @if($address->address_line_2)
                                    {{ $address->address_line_2 }}<br>
                                @endif
                                {{ $address->city }}, {{ $address->state }}<br>
                                {{ $address->postal_code }}<br>
                                {{ $address->country }}
                            </address>

                            <div class="address-contact">
                                <p class="text-muted mb-0">
                                    <i class="fas fa-phone me-1"></i>{{ $address->phone }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="empty-addresses text-center py-5">
                        <div class="empty-icon mb-4">
                            <i class="fas fa-map-marker-alt text-muted" style="font-size: 5rem;"></i>
                        </div>
                        <h3 class="text-muted mb-3">No addresses yet</h3>
                        <p class="text-muted mb-4">You haven't added any addresses yet. Add your first address to get started.</p>
                        <button class="btn btn-primary btn-lg px-4" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                            <i class="fas fa-plus me-2"></i>Add Your First Address
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Add New Address
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('customer.store-address') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address_line_1" class="form-label">Address Line 1 *</label>
                        <input type="text" class="form-control" id="address_line_1" name="address_line_1" required>
                    </div>

                    <div class="mb-3">
                        <label for="address_line_2" class="form-label">Address Line 2</label>
                        <input type="text" class="form-control" id="address_line_2" name="address_line_2">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">State *</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="postal_code" class="form-label">Postal Code *</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">Country *</label>
                            <input type="text" class="form-control" id="country" name="country" value="India" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1">
                        <label class="form-check-label" for="is_default">
                            Set as default address
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Address
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAddressForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_first_name" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_last_name" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_address_line_1" class="form-label">Address Line 1 *</label>
                        <input type="text" class="form-control" id="edit_address_line_1" name="address_line_1" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_address_line_2" class="form-label">Address Line 2</label>
                        <input type="text" class="form-control" id="edit_address_line_2" name="address_line_2">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_city" class="form-label">City *</label>
                            <input type="text" class="form-control" id="edit_city" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_state" class="form-label">State *</label>
                            <input type="text" class="form-control" id="edit_state" name="state" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_postal_code" class="form-label">Postal Code *</label>
                            <input type="text" class="form-control" id="edit_postal_code" name="postal_code" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_country" class="form-label">Country *</label>
                            <input type="text" class="form-control" id="edit_country" name="country" value="India" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control" id="edit_phone" name="phone" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit_is_default" name="is_default" value="1">
                        <label class="form-check-label" for="edit_is_default">
                            Set as default address
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Update Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editAddress(addressId) {
    // Find the address card with this ID
    const addressCard = document.querySelector(`[data-address-id="${addressId}"]`);
    if (!addressCard) {
        alert('Address not found');
        return;
    }

    // Populate edit form fields from data attributes
    document.getElementById('edit_first_name').value = addressCard.dataset.firstName || '';
    document.getElementById('edit_last_name').value = addressCard.dataset.lastName || '';
    document.getElementById('edit_address_line_1').value = addressCard.dataset.addressLine1 || '';
    document.getElementById('edit_address_line_2').value = addressCard.dataset.addressLine2 || '';
    document.getElementById('edit_city').value = addressCard.dataset.city || '';
    document.getElementById('edit_state').value = addressCard.dataset.state || '';
    document.getElementById('edit_postal_code').value = addressCard.dataset.postalCode || '';
    document.getElementById('edit_country').value = addressCard.dataset.country || 'India';
    document.getElementById('edit_phone').value = addressCard.dataset.phone || '';
    document.getElementById('edit_email').value = addressCard.dataset.email || '';
    document.getElementById('edit_is_default').checked = addressCard.dataset.isDefault === '1';

    // Set form action URL
    const form = document.getElementById('editAddressForm');
    form.action = `{{ route('customer.update-address', ':id') }}`.replace(':id', addressId);

    // Open modal
    const modal = new bootstrap.Modal(document.getElementById('editAddressModal'));
    modal.show();
}

function setDefaultAddress(addressId) {
    if (!confirm('Set this address as your default address?')) {
        return;
    }

    const addressCard = document.querySelector(`[data-address-id="${addressId}"]`);
    if (!addressCard) {
        alert('Address not found');
        return;
    }

    fetch(`{{ route('customer.update-address', ':id') }}`.replace(':id', addressId), {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            is_default: 1,
            // Get current address data from card
            first_name: addressCard.dataset.firstName,
            last_name: addressCard.dataset.lastName,
            address_line_1: addressCard.dataset.addressLine1,
            address_line_2: addressCard.dataset.addressLine2 || '',
            city: addressCard.dataset.city,
            state: addressCard.dataset.state,
            postal_code: addressCard.dataset.postalCode,
            country: addressCard.dataset.country,
            phone: addressCard.dataset.phone,
            email: addressCard.dataset.email || ''
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success !== false) {
            location.reload();
        } else {
            alert(data.message || 'Failed to set default address');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Fallback: reload page (Laravel will handle the redirect response)
        location.reload();
    });
}

function deleteAddress(addressId) {
    if (!confirm('Are you sure you want to delete this address? This action cannot be undone.')) {
        return;
    }

    fetch(`{{ route('customer.delete-address', ':id') }}`.replace(':id', addressId), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            location.reload();
        } else {
            return response.json();
        }
    })
    .then(data => {
        if (data && data.success === false) {
            alert(data.message || 'Failed to delete address');
        } else {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Fallback: reload page (Laravel will handle the redirect response)
        location.reload();
    });
}
</script>
@endpush

@section('styles')
<style>
.address-card {
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.address-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    border-color: #dee2e6;
}

.empty-addresses {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.btn {
    border-radius: 25px;
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.form-control {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 12px 16px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

@media (max-width: 768px) {
    .addresses-header .col-md-6:last-child {
        margin-top: 1rem;
        text-align: left !important;
    }
}
</style>
@endsection
