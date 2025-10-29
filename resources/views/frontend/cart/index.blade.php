@extends('layouts.frontend')

@section('title', 'Shopping Cart - ' . config('app.name'))

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
            <li class="breadcrumb-item active text-white" aria-current="page">
                <i class="fas fa-shopping-cart me-1"></i>Shopping Cart
            </li>
        </ol>
    </div>
</nav>

<!-- Cart Section -->
<section class="py-5 bg-light">
    <div class="container">
        @if($cart->isEmpty())
            <!-- Empty Cart -->
            <div class="text-center py-5">
                <div class="empty-cart-icon mb-4">
                    <i class="fas fa-shopping-cart text-muted" style="font-size: 5rem;"></i>
                </div>
                <h3 class="text-muted mb-3">Your cart is empty</h3>
                <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg rounded-pill px-4">
                    <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                </a>
            </div>
        @else
            <div class="row g-4">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="cart-items bg-white rounded-4 shadow-sm p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold text-dark mb-0">
                                <i class="fas fa-shopping-cart text-primary me-2"></i>Shopping Cart
                            </h4>
                            <span class="badge bg-primary fs-6 px-3 py-2">{{ $cart->total_items }} {{ Str::plural('item', $cart->total_items) }}</span>
                        </div>

                        @foreach($cart->items as $item)
                            <div class="cart-item border-bottom pb-4 mb-4" data-item-id="{{ $item->id }}">
                                <div class="row align-items-center">
                                    <!-- Product Image -->
                                    <div class="col-md-2 col-3">
                                        <div class="product-image-container">
                                            @if($item->product->featured_image)
                                                <img src="{{ $item->product->featured_image }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="img-fluid rounded-3 shadow-sm"
                                                     style="height: 80px; object-fit: cover;">
                                            @else
                                                <img src="https://via.placeholder.com/80x80/10b981/ffffff?text={{ urlencode(substr($item->product->name, 0, 2)) }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="img-fluid rounded-3 shadow-sm"
                                                     style="height: 80px; object-fit: cover;">
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="col-md-4 col-9">
                                        <h6 class="fw-bold text-dark mb-1">{{ $item->product->name }}</h6>
                                        <p class="text-muted small mb-0">{{ $item->product->short_description ?? 'Premium quality product' }}</p>
                                        @if($item->product->category)
                                            <span class="badge bg-light text-dark small">{{ $item->product->category->name }}</span>
                                        @endif
                                    </div>

                                    <!-- Quantity -->
                                    <div class="col-md-2 col-6 mt-3 mt-md-0">
                                        <label class="form-label small fw-bold">Quantity</label>
                                        <div class="quantity-selector">
                                            <div class="input-group">
                                                <button class="btn btn-outline-secondary btn-sm"
                                                        type="button"
                                                        onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number"
                                                       class="form-control text-center border-0 bg-light"
                                                       value="{{ $item->quantity }}"
                                                       min="1"
                                                       max="10"
                                                       onchange="updateQuantity({{ $item->id }}, this.value)">
                                                <button class="btn btn-outline-secondary btn-sm"
                                                        type="button"
                                                        onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                        {{ $item->quantity >= 10 ? 'disabled' : '' }}>
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="col-md-2 col-6 mt-3 mt-md-0 text-md-end">
                                        <div class="price-info">
                                            <div class="current-price fw-bold text-primary fs-5">
                                                ₹{{ number_format($item->price, 2) }}
                                            </div>
                                            <div class="total-price text-muted small">
                                                Total: ₹<span class="item-total">{{ number_format($item->total_price, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="col-md-2 col-12 mt-3 mt-md-0 text-md-end">
                                        <div class="action-buttons">
                                            <button class="btn btn-outline-danger btn-sm rounded-pill"
                                                    onclick="removeItem({{ $item->id }})"
                                                    title="Remove item">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Cart Actions -->
                        <div class="cart-actions mt-4 pt-4 border-top">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary rounded-pill">
                                        <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                                    </a>
                                </div>
                                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                    <button class="btn btn-outline-danger rounded-pill" onclick="clearCart()">
                                        <i class="fas fa-trash me-2"></i>Clear Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="order-summary bg-white rounded-4 shadow-sm p-4 sticky-top" style="top: 2rem;">
                        <h5 class="fw-bold text-dark mb-4">
                            <i class="fas fa-receipt text-primary me-2"></i>Order Summary
                        </h5>

                        <!-- Coupon Code -->
                        <div class="coupon-section mb-4">
                            <div class="input-group">
                                <input type="text"
                                       class="form-control"
                                       id="couponCode"
                                       placeholder="Enter coupon code"
                                       value="{{ $cart->coupon_code }}">
                                <button class="btn btn-outline-primary"
                                        type="button"
                                        onclick="applyCoupon()">
                                    Apply
                                </button>
                            </div>
                            @if($cart->coupon_code)
                                <div class="mt-2">
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>{{ $cart->coupon_code }}
                                    </span>
                                    <button class="btn btn-sm btn-outline-danger ms-2" onclick="removeCoupon()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Order Breakdown -->
                        <div class="order-breakdown">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal ({{ $cart->total_items }} items)</span>
                                <span class="fw-bold">₹{{ number_format($cart->total_amount, 2) }}</span>
                            </div>

                            @if($cart->discount_amount > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Discount</span>
                                    <span>-₹{{ number_format($cart->discount_amount, 2) }}</span>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Shipping</span>
                                <span class="fw-bold {{ $cart->shipping_amount == 0 ? 'text-success' : '' }}">
                                    {{ $cart->shipping_amount == 0 ? 'FREE' : '₹' . number_format($cart->shipping_amount, 2) }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tax (GST 18%)</span>
                                <span class="fw-bold">₹{{ number_format($cart->tax_amount, 2) }}</span>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-4">
                                <span class="fw-bold fs-5 text-dark">Total</span>
                                <span class="fw-bold fs-5 text-primary">₹{{ number_format($cart->final_amount, 2) }}</span>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <div class="checkout-section">
                            <a href="{{ route('checkout.index') }}"
                               class="btn btn-success btn-lg w-100 rounded-pill py-3 fw-bold">
                                <i class="fas fa-lock me-2"></i>Proceed to Checkout
                            </a>

                            <div class="security-badges mt-3 text-center">
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt text-success me-1"></i>
                                    Secure Checkout • SSL Encrypted
                                </small>
                            </div>
                        </div>

                        <!-- Shipping Info -->
                        <div class="shipping-info mt-4 p-3 bg-light rounded-3">
                            <h6 class="fw-bold text-dark mb-2">
                                <i class="fas fa-truck text-primary me-1"></i>Shipping Info
                            </h6>
                            <ul class="list-unstyled small text-muted mb-0">
                                <li><i class="fas fa-check text-success me-1"></i>Free shipping on orders over ₹500</li>
                                <li><i class="fas fa-check text-success me-1"></i>2-3 business days delivery</li>
                                <li><i class="fas fa-check text-success me-1"></i>Cash on delivery available</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
// Cart Management Functions
function updateQuantity(itemId, quantity) {
    if (quantity < 1 || quantity > 10) return;

    fetch(`/cart/update/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the item total
            document.querySelector(`[data-item-id="${itemId}"] .item-total`).textContent = data.item_total;

            // Update cart totals
            document.querySelector('.order-summary .fw-bold.fs-5.text-primary').textContent = `₹${data.cart_total}`;

            // Show success message
            showNotification(data.message, 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating cart', 'error');
    });
}

function removeItem(itemId) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        fetch(`/cart/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the item from DOM
                document.querySelector(`[data-item-id="${itemId}"]`).remove();

                // Update cart totals
                document.querySelector('.order-summary .fw-bold.fs-5.text-primary').textContent = `₹${data.cart_total}`;

                // Update item count
                const itemCount = document.querySelector('.badge.bg-primary');
                if (itemCount) {
                    itemCount.textContent = `${data.cart_count} ${data.cart_count === 1 ? 'item' : 'items'}`;
                }

                showNotification(data.message, 'success');

                // If cart is empty, reload page
                if (data.cart_count === 0) {
                    setTimeout(() => location.reload(), 1000);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error removing item', 'error');
        });
    }
}

function clearCart() {
    if (confirm('Are you sure you want to clear your entire cart?')) {
        fetch('/cart/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error clearing cart', 'error');
        });
    }
}

function applyCoupon() {
    const couponCode = document.getElementById('couponCode').value.trim();
    if (!couponCode) {
        showNotification('Please enter a coupon code', 'warning');
        return;
    }

    fetch('/cart/apply-coupon', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ coupon_code: couponCode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error applying coupon', 'error');
    });
}

function removeCoupon() {
    fetch('/cart/remove-coupon', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error removing coupon', 'error');
    });
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}
</script>
@endsection
