@extends('layouts.frontend')

@section('title', 'Checkout - Nutrition Store')
@section('description', 'Complete your order securely with our fast and reliable checkout process.')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-1">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
            <li class="breadcrumb-item active">Checkout</li>
        </ol>
    </div>
</nav>

<!-- Checkout Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h4 class="mb-2">Checkout</h4>
            </div>
        </div>

        <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Left Column - Form -->
                <div class="col-lg-8">
                    <!-- Checkout Steps -->
                    <div class="checkout-steps mb-4">
                        <div class="step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-title">Shipping</div>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-title">Review</div>
                        </div>
                    </div>

                    <!-- Step 1: Shipping Information -->
                    <div class="checkout-step" id="step-1">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Shipping Information</h5>
                            </div>
                            <div class="card-body">
                                <!-- Address Selection Options -->
                                <div class="mb-4">
                                    <h6 class="mb-3">Select Address Option</h6>

                                    <!-- Option 1: Use Saved Address -->
                                    <div class="card mb-3 border-2" id="saved-address-option">
                                        <div class="card-header bg-light">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="address_option" id="use_saved" value="saved" checked>
                                                <label class="form-check-label fw-bold" for="use_saved">
                                                    <i class="fas fa-bookmark me-2 text-primary"></i>Use Saved Address
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card-body" id="saved-addresses">
                                            @if($addresses && $addresses->count() > 0)
                                                @foreach($addresses as $address)
                                                <div class="card mb-2 address-card border" style="cursor: pointer;">
                                                    <div class="card-body p-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input address-radio" type="radio" name="saved_address_id"
                                                                   id="address_{{ $address->id }}" value="{{ $address->id }}"
                                                                   {{ $address->is_default ? 'checked' : '' }}>
                                                            <label class="form-check-label w-100" for="address_{{ $address->id }}">
                                                                <div class="d-flex justify-content-between align-items-start">
                                                                    <div>
                                                                        <h6 class="mb-1">
                                                                            {{ $address->first_name }} {{ $address->last_name }}
                                                                            @if($address->is_default)
                                                                                <span class="badge bg-primary ms-2">Default</span>
                                                                            @endif
                                                                        </h6>
                                                                        <p class="mb-1 text-muted">{{ $address->address_line_1 }}</p>
                                                                        @if($address->address_line_2)
                                                                            <p class="mb-1 text-muted">{{ $address->address_line_2 }}</p>
                                                                        @endif
                                                                        <p class="mb-1 text-muted">
                                                                            {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}
                                                                        </p>
                                                                        <p class="mb-0 text-muted">{{ $address->country }}</p>
                                                                        <p class="mb-1 text-muted">
                                                                            <i class="fas fa-phone me-1"></i>{{ $address->phone }}
                                                                        </p>
                                                                        @if($address->email)
                                                                        <p class="mb-0 text-muted">
                                                                            <i class="fas fa-envelope me-1"></i>{{ $address->email }}
                                                                        </p>
                                                                        @endif
                                                                    </div>
                                                                    <div class="text-end">
                                                                        <i class="fas fa-map-marker-alt text-primary fa-2x"></i>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @else
                                                <div class="text-center text-muted py-3">
                                                    <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                                                    <p class="mb-0">No saved addresses found</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Option 2: Enter New Address -->
                                    <div class="card border-2" id="new-address-option">
                                        <div class="card-header bg-light">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="address_option" id="use_new" value="new">
                                                <label class="form-check-label fw-bold" for="use_new">
                                                    <i class="fas fa-plus-circle me-2 text-success"></i>Enter New Address
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card-body" id="new-address-form" style="display: none;">
                                            <div class="text-center text-muted py-3">
                                                <i class="fas fa-edit fa-2x mb-2"></i>
                                                <p class="mb-0">Fill in the address form below</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label">First Name *</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $customer->first_name ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label">Last Name *</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $customer->last_name ?? '' }}" required>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $customer->email ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone *</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" value="{{ $customer->phone ?? '' }}" required>
                                    </div>
                                </div>

                                <!-- Address Information -->
                                <div class="mb-4">
                                    <label for="address" class="form-label">Address *</label>
                                    <input type="text" class="form-control" id="address" name="address" required>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="city" class="form-label">City *</label>
                                        <input type="text" class="form-control" id="city" name="city" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="state" class="form-label">State *</label>
                                        <input type="text" class="form-control" id="state" name="state" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="pincode" class="form-label">Pincode *</label>
                                        <input type="text" class="form-control" id="pincode" name="pincode" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="country" class="form-label">Country *</label>
                                    <select class="form-control" id="country" name="country" required>
                                        <option value="India" selected>India</option>
                                        <option value="USA">USA</option>
                                        <option value="UK">UK</option>
                                        <option value="Canada">Canada</option>
                                    </select>
                                </div>

                                <!-- Shipping Method -->
                                <div class="mb-4">
                                    <label class="form-label">Shipping Method *</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="shipping_method" id="standard" value="standard" checked>
                                        <label class="form-check-label" for="standard">
                                            <strong>Standard Delivery</strong> - 3-5 business days
                                            <span id="standard-shipping-cost">
                                                @if($cart->total_amount >= 500)
                                                    (Free)
                                                @else
                                                    (₹50)
                                                @endif
                                            </span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="shipping_method" id="express" value="express">
                                        <label class="form-check-label" for="express">
                                            <strong>Express Delivery</strong> - 1-2 business days (₹99)
                                        </label>
                                    </div>
                                </div>

                                <!-- Save Address -->
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="save_address" name="save_address" value="1">
                                    <label class="form-check-label" for="save_address">
                                        Save this address for future orders
                                    </label>
                                </div>

                                <button type="button" class="btn btn-primary" onclick="nextStep(2)">
                                    Continue to Review <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- Step 2: Review Your Order -->
                    <div class="checkout-step d-none" id="step-2">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Review Your Order</h5>
                            </div>
                            <div class="card-body">
                                <!-- Order Summary -->
                                <div class="mb-4">
                                    <h6>Order Items</h6>
                                    <div class="border rounded p-3">
                                        @if($cart->items && $cart->items->count() > 0)
                                            @foreach($cart->items as $item)
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <strong>{{ $item->product->name }}</strong>
                                                    @if($item->product->description)
                                                        <br><small class="text-muted">{{ Str::limit($item->product->description, 50) }}</small>
                                                    @endif
                                                    @if($item->product_attributes)
                                                        <br><small class="text-muted">{{ $item->product_attributes }}</small>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <div>₹{{ number_format($item->total_price, 2) }}</div>
                                                    <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                                </div>
                                            </div>
                                            @if(!$loop->last)
                                                <hr class="my-2">
                                            @endif
                                            @endforeach
                                        @else
                                            <div class="text-center text-muted py-3">
                                                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                                <p class="mb-0">No items in cart</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Shipping Address -->
                                <div class="mb-4">
                                    <h6>Shipping Address</h6>
                                    <div class="border rounded p-3">
                                        <div id="shipping-address">
                                            <p class="mb-0">Loading address...</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="mb-4">
                                    <h6>Payment Method</h6>
                                    <div class="border rounded p-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-credit-card text-primary me-2"></i>
                                            <div>
                                                <strong>Razorpay (Online Payment)</strong>
                                                <small class="d-block text-muted">Pay securely with cards, UPI, net banking, wallets</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden payment method field -->
                                <input type="hidden" name="payment_method" value="razorpay">

                                <!-- Terms and Conditions -->
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" class="text-primary">Terms and Conditions</a> and <a href="#" class="text-primary">Privacy Policy</a>
                                    </label>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary" onclick="prevStep(1)">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Shipping
                                    </button>
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-lock me-2"></i>Place Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="col-lg-4">
                    <div class="card sticky-top" style="top: 100px;">
                        <div class="card-header">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <!-- Cart Items -->
                            @if($cart->items && $cart->items->count() > 0)
                            <div class="mb-3">
                                <h6 class="mb-2">Items in Cart</h6>
                                @foreach($cart->items as $item)
                                <div class="d-flex align-items-center mb-2 p-2 border rounded">
                                    @if($item->product->featured_image)
                                        <img src="{{ $item->product->featured_image }}"
                                             alt="{{ $item->product->name }}"
                                             class="me-2"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="me-2 bg-light d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="fw-bold small">{{ $item->product->name }}</div>
                                        <div class="text-muted small">Qty: {{ $item->quantity }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold small">₹{{ number_format($item->total_price, 2) }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <hr>
                            @endif

                            <!-- Price Breakdown -->
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal ({{ $cart->total_items }} items)</span>
                                <span>₹{{ number_format($cart->total_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping</span>
                                <span class="text-success" id="shipping-cost">
                                    @if($cart->shipping_amount > 0)
                                        ₹{{ number_format($cart->shipping_amount, 2) }}
                                    @else
                                        Free
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax</span>
                                <span>₹{{ number_format($cart->tax_amount, 2) }}</span>
                            </div>
                            @if($cart->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount</span>
                                <span class="text-success">-₹{{ number_format($cart->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <strong>Total</strong>
                                <strong class="text-primary" id="order-total">₹{{ number_format($cart->final_amount, 2) }}</strong>
                            </div>

                            <!-- Security Badges -->
                            <div class="text-center mb-3">
                                <div class="d-flex justify-content-center gap-3 mb-2">
                                    <i class="fas fa-shield-alt text-success fa-2x"></i>
                                    <i class="fas fa-lock text-success fa-2x"></i>
                                    <i class="fas fa-credit-card text-success fa-2x"></i>
                                </div>
                                <p class="text-muted small mb-0">Secure checkout with SSL encryption</p>
                            </div>

                            <!-- Trust Indicators -->
                            <div class="trust-indicators">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-truck text-primary me-2"></i>
                                    <small>Free shipping on orders above ₹999</small>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-undo text-primary me-2"></i>
                                    <small>30-day return policy</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-headset text-primary me-2"></i>
                                    <small>24/7 customer support</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    let currentStep = 1;

    // Cart data from server
    const cartData = {
        subtotal: {{ $cart->total_amount ?? 0 }},
        shippingCost: {{ $cart->shipping_amount ?? 0 }},
        taxAmount: {{ $cart->tax_amount ?? 0 }},
        discountAmount: {{ $cart->discount_amount ?? 0 }},
        totalAmount: {{ $cart->final_amount ?? $cart->total_amount ?? 0 }},
        totalItems: {{ $cart->total_items ?? 0 }}
    };

    // Next step function
    function nextStep(step) {
        // Validate current step
        if (!validateStep(currentStep)) {
            return;
        }

        // Hide current step
        document.getElementById(`step-${currentStep}`).classList.add('d-none');
        document.querySelector(`[data-step="${currentStep}"]`).classList.remove('active');

        // Show next step
        document.getElementById(`step-${step}`).classList.remove('d-none');
        document.querySelector(`[data-step="${step}"]`).classList.add('active');

        currentStep = step;

        // Update review section if on step 3
        if (step === 3) {
            updateReviewSection();
        }
    }

    // Previous step function
    function prevStep(step) {
        // Hide current step
        document.getElementById(`step-${currentStep}`).classList.add('d-none');
        document.querySelector(`[data-step="${currentStep}"]`).classList.remove('active');

        // Show previous step
        document.getElementById(`step-${step}`).classList.remove('d-none');
        document.querySelector(`[data-step="${step}"]`).classList.add('active');

        currentStep = step;
    }

    // Validate step function
    function validateStep(step) {
        if (step === 1) {
            // Validate shipping form
            const requiredFields = ['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'state', 'pincode'];
            for (let field of requiredFields) {
                const input = document.getElementById(field);
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    alert(`Please fill in the ${field.replace('_', ' ')} field.`);
                    return false;
                } else {
                    input.classList.remove('is-invalid');
                }
            }
        } else if (step === 2) {
            // Validate review step - check terms and conditions
            const termsCheckbox = document.getElementById('terms');
            if (!termsCheckbox.checked) {
                alert('Please agree to the Terms and Conditions to proceed.');
                termsCheckbox.focus();
                return false;
            }
        }
        return true;
    }

    // Update review section
    function updateReviewSection() {
        // Update shipping address
        const shippingAddress = document.getElementById('shipping-address');
        const firstName = document.getElementById('first_name').value;
        const lastName = document.getElementById('last_name').value;
        const address = document.getElementById('address').value;
        const city = document.getElementById('city').value;
        const state = document.getElementById('state').value;
        const pincode = document.getElementById('pincode').value;

        shippingAddress.innerHTML = `
            <strong>${firstName} ${lastName}</strong><br>
            ${address}<br>
            ${city}, ${state} ${pincode}<br>
            India
        `;

        // Payment method is now static (Razorpay only)
        // No need to update it dynamically
    }

    // Payment method is now static, no change handler needed

    // Shipping method change handler
    document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const shippingCost = document.getElementById('shipping-cost');
            const orderTotal = document.getElementById('order-total');
            const standardShippingLabel = document.getElementById('standard-shipping-cost');

            let newShippingCost = cartData.shippingCost; // Start with cart's shipping cost

            if (this.value === 'express') {
                newShippingCost = 99; // Express delivery costs ₹99
            } else if (this.value === 'standard') {
                // Standard delivery: free if subtotal >= 500, otherwise ₹50
                newShippingCost = cartData.subtotal >= 500 ? 0 : 50;

                // Update the label text
                if (standardShippingLabel) {
                    standardShippingLabel.textContent = newShippingCost > 0 ? `(₹${newShippingCost})` : '(Free)';
                }
            }

            // Calculate new total
            const newTotal = cartData.subtotal + cartData.taxAmount + newShippingCost - cartData.discountAmount;

            // Update display
            if (newShippingCost > 0) {
                shippingCost.textContent = `₹${newShippingCost.toFixed(2)}`;
            } else {
                shippingCost.textContent = 'Free';
            }
            orderTotal.textContent = `₹${newTotal.toFixed(2)}`;

            // Update review section if on step 3
            if (currentStep === 3) {
                updateReviewSection();
            }
        });
    });

    // Address selection handler
    document.addEventListener('DOMContentLoaded', function() {
        const addressOptionRadios = document.querySelectorAll('input[name="address_option"]');
        const addressRadioButtons = document.querySelectorAll('input[name="saved_address_id"]');
        const addressCards = document.querySelectorAll('.address-card');
        const addressFields = ['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'state', 'pincode', 'country'];

        // Load saved addresses data
        const savedAddresses = @json($addresses ?? []);

        // Handle address option change
        addressOptionRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'saved') {
                    // Show saved address cards
                    document.getElementById('saved-addresses').style.display = 'block';
                    document.getElementById('new-address-form').style.display = 'none';
                    // Highlight saved address option
                    document.getElementById('saved-address-option').classList.add('border-primary');
                    document.getElementById('new-address-option').classList.remove('border-primary');
                    // Pre-fill with selected address
                    fillFormWithSavedAddress();
                } else {
                    // Hide saved address cards
                    document.getElementById('saved-addresses').style.display = 'none';
                    document.getElementById('new-address-form').style.display = 'block';
                    // Highlight new address option
                    document.getElementById('new-address-option').classList.add('border-primary');
                    document.getElementById('saved-address-option').classList.remove('border-primary');
                    // Clear form fields
                    clearAddressFields();
                }
            });
        });

        // Handle saved address radio button change
        addressRadioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                fillFormWithSavedAddress();
                updateCardSelection();
            });
        });

        // Handle address card clicks
        addressCards.forEach(card => {
            card.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                    fillFormWithSavedAddress();
                    updateCardSelection();
                }
            });
        });

        function updateCardSelection() {
            addressCards.forEach(card => {
                const radio = card.querySelector('input[type="radio"]');
                if (radio && radio.checked) {
                    card.classList.add('border-primary', 'bg-light');
                    card.classList.remove('border-secondary');
                } else {
                    card.classList.remove('border-primary', 'bg-light');
                    card.classList.add('border-secondary');
                }
            });
        }

        function fillFormWithSavedAddress() {
            const selectedRadio = document.querySelector('input[name="saved_address_id"]:checked');
            if (!selectedRadio) return;

            const selectedAddressId = selectedRadio.value;
            const selectedAddress = savedAddresses.find(addr => addr.id == selectedAddressId);

            if (selectedAddress) {
                document.getElementById('first_name').value = selectedAddress.first_name || '';
                document.getElementById('last_name').value = selectedAddress.last_name || '';
                document.getElementById('email').value = selectedAddress.email || '';
                document.getElementById('phone').value = selectedAddress.phone || '';
                document.getElementById('address').value = selectedAddress.address_line_1 || '';
                document.getElementById('city').value = selectedAddress.city || '';
                document.getElementById('state').value = selectedAddress.state || '';
                document.getElementById('pincode').value = selectedAddress.postal_code || '';
                document.getElementById('country').value = selectedAddress.country || 'India';
            }
        }

        function clearAddressFields() {
            addressFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    if (fieldId === 'country') {
                        field.value = 'India';
                    } else {
                        field.value = '';
                    }
                }
            });
        }

        // Initialize with default address if available
        if (savedAddresses.length > 0) {
            const defaultAddress = savedAddresses.find(addr => addr.is_default) || savedAddresses[0];
            if (defaultAddress) {
                fillFormWithSavedAddress();
                updateCardSelection();
            }
        }
    });

    // Form submission - AJAX to avoid redirect
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate the form before submission
        if (!validateStep(2)) {
            return;
        }

        // Show loading state
        const submitBtn = document.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Order...';
        submitBtn.disabled = true;

        // Get form data
        const formData = new FormData(this);

        // Add AJAX headers
        const token = document.querySelector('meta[name="csrf-token"]');
        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        };
        if (token) {
            headers['X-CSRF-TOKEN'] = token.getAttribute('content');
        }

        // Submit order via AJAX
        fetch(this.action, {
            method: 'POST',
            headers: headers,
            body: formData
        })
        .then(response => {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // If not JSON, return error
                throw new Error('Invalid response from server');
            }
        })
        .then(data => {
            if (data.success) {
                // Initialize Razorpay Checkout
                initializeRazorpay(data);
            } else {
                alert(data.message || 'Failed to create order. Please try again.');
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again or refresh the page.');
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        });
    });

    // Initialize Razorpay Checkout
    function initializeRazorpay(data) {
        const options = {
            "key": data.razorpay.key_id,
            "amount": data.razorpay.amount,
            "currency": data.razorpay.currency,
            "name": "Nutrition Store",
            "description": "Order #" + data.order_number,
            "image": "https://your-logo-url.com/logo.png", // Replace with your logo
            "order_id": data.razorpay.order_id,
            "handler": function (response) {
                // Handle successful payment
                verifyPayment(response, data.order_id);
            },
            "prefill": {
                "name": data.razorpay.name,
                "email": data.razorpay.email,
                "contact": data.razorpay.contact
            },
            "notes": {
                "address": data.razorpay.address
            },
            "theme": {
                "color": "#3399cc"
            },
            "modal": {
                "ondismiss": function() {
                    // Payment cancelled - order is already created with pending status
                    const submitBtn = document.querySelector('button[type="submit"]');
                    submitBtn.innerHTML = '<i class="fas fa-lock me-2"></i>Place Order';
                    submitBtn.disabled = false;
                    alert('Payment cancelled. Your order has been created with pending payment status.');
                }
            }
        };

        const rzp = new Razorpay(options);
        rzp.open();
    }

    // Verify payment after successful Razorpay transaction
    function verifyPayment(paymentResponse, orderId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("payment.razorpay.verify") }}';

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        form.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="razorpay_payment_id" value="${paymentResponse.razorpay_payment_id}">
            <input type="hidden" name="razorpay_order_id" value="${paymentResponse.razorpay_order_id}">
            <input type="hidden" name="razorpay_signature" value="${paymentResponse.razorpay_signature}">
            <input type="hidden" name="order_id" value="${orderId}">
        `;

        document.body.appendChild(form);
        form.submit();
    }
</script>
@endpush

@push('styles')
<style>
    /* Compact Design - No Scroll */
    body {
        font-size: 14px;
    }

    .container {
        max-width: 100%;
        padding: 0.5rem;
    }

    .py-5 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }

    .mb-4 {
        margin-bottom: 1rem !important;
    }

    .mb-3 {
        margin-bottom: 0.75rem !important;
    }

    .mb-2 {
        margin-bottom: 0.5rem !important;
    }

    .p-3 {
        padding: 0.75rem !important;
    }

    .p-2 {
        padding: 0.5rem !important;
    }

    .checkout-steps {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 0 1rem;
        position: relative;
    }

    .step-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #6c757d;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }

    .step.active .step-number {
        background: #28a745;
    }

    .step-title {
        font-weight: 600;
        color: #495057;
        font-size: 0.75rem;
        text-align: center;
    }

    .step.active .step-title {
        color: #28a745;
    }

    /* Compact Card Headers */
    .card-header {
        padding: 0.75rem 1rem;
    }

    .card-header h5 {
        font-size: 1rem;
        margin-bottom: 0;
    }

    .card-body {
        padding: 1rem;
    }

    /* Compact Form Elements */
    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .form-control {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }

    .form-check-label {
        font-size: 0.85rem;
    }

    /* Compact Buttons */
    .btn {
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
    }

    .btn-lg {
        font-size: 0.9rem;
        padding: 0.6rem 1.2rem;
    }

    /* Compact Order Summary */
    .sticky-top {
        top: 80px !important;
    }

    .order-summary-item {
        padding: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .order-summary-item img {
        width: 30px !important;
        height: 30px !important;
    }

    .order-summary-item .fw-bold {
        font-size: 0.8rem;
    }

    .order-summary-item .small {
        font-size: 0.75rem;
    }

    /* Compact Address Cards */
    .address-card .card-body {
        padding: 0.75rem;
    }

    .address-card h6 {
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }

    .address-card p {
        font-size: 0.75rem;
        margin-bottom: 0.25rem;
    }

    .address-card .fa-2x {
        font-size: 1.2rem !important;
    }

    /* Compact Alert */
    .alert {
        padding: 0.75rem;
        font-size: 0.85rem;
    }

    /* Compact Trust Indicators */
    .trust-indicators small {
        font-size: 0.75rem;
    }

    .trust-indicators .fa-2x {
        font-size: 1.2rem !important;
    }

    /* Compact Security Badges */
    .text-center .fa-2x {
        font-size: 1.2rem !important;
    }

    .text-center p {
        font-size: 0.75rem;
    }

    /* Additional Compact Styling */
    .row {
        margin-left: -0.25rem;
        margin-right: -0.25rem;
    }

    .col-lg-8, .col-lg-4, .col-md-6, .col-md-4, .col-12 {
        padding-left: 0.25rem;
        padding-right: 0.25rem;
    }

    .card {
        margin-bottom: 0.5rem;
    }

    .border {
        border-width: 1px !important;
    }

    .rounded {
        border-radius: 0.25rem !important;
    }

    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }

    .list-unstyled {
        margin-bottom: 0.25rem;
    }

    .list-unstyled li {
        margin-bottom: 0.1rem;
    }

    /* Address Selection Styling */
    #saved-address-option, #new-address-option {
        transition: all 0.3s ease;
    }

    #saved-address-option.border-primary, #new-address-option.border-primary {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .address-card {
        transition: all 0.2s ease;
    }

    .address-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .address-card.border-primary {
        border-color: #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.05);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .checkout-steps {
            flex-direction: column;
            gap: 1rem;
        }

        .step {
            flex-direction: row;
            margin: 0;
        }

        .step::after {
            display: none;
        }

        .step-number {
            margin-right: 1rem;
            margin-bottom: 0;
        }
    }
</style>
@endpush
