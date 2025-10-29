@extends('layouts.frontend')

@section('title', 'Order Details - ' . config('app.name'))

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
            <li class="breadcrumb-item">
                <a href="{{ route('customer.orders') }}" class="text-white-50">
                    <i class="fas fa-shopping-bag me-1"></i>My Orders
                </a>
            </li>
            <li class="breadcrumb-item active text-white" aria-current="page">
                <i class="fas fa-eye me-1"></i>Order #{{ $order->order_number }}
            </li>
        </ol>
    </div>
</nav>

<!-- Order Details Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="order-header bg-white rounded-4 shadow-sm p-4 mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h2 class="fw-bold text-dark mb-0">
                                <i class="fas fa-receipt text-primary me-2"></i>Order #{{ $order->order_number }}
                            </h2>
                            <p class="text-muted mb-0">Ordered on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="order-status">
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'info')) }} fs-5 px-4 py-2">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Order Items -->
            <div class="col-lg-8 mb-4">
                <div class="order-items-card bg-white rounded-4 shadow-sm p-4">
                    <h4 class="fw-bold text-dark mb-4">
                        <i class="fas fa-box me-2"></i>Order Items
                    </h4>

                    @foreach($order->items as $item)
                        <div class="order-item mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="item-image">
                                        <img src="{{ $item->product->featured_image ?? 'https://via.placeholder.com/80x80/10b981/ffffff?text=Product' }}"
                                             alt="{{ $item->product->name }}"
                                             class="img-fluid rounded"
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="item-details">
                                        <h5 class="fw-bold text-dark mb-1">{{ $item->product->name }}</h5>
                                        @if($item->product_sku)
                                            <p class="text-muted mb-1">SKU: {{ $item->product_sku }}</p>
                                        @endif
                                        @if($item->product_attributes && count($item->product_attributes) > 0)
                                            <div class="product-attributes">
                                                @foreach($item->product_attributes as $key => $value)
                                                    <small class="text-muted">{{ ucfirst($key) }}: {{ $value }}</small>
                                                    @if(!$loop->last) | @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <div class="item-quantity">
                                        <span class="badge bg-light text-dark fs-6 px-3 py-2">
                                            Qty: {{ $item->quantity }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <div class="item-price">
                                        <h6 class="fw-bold text-dark mb-0">₹{{ number_format($item->unit_price, 2) }}</h6>
                                        <small class="text-muted">per unit</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4 mb-4">
                <div class="order-summary-card bg-white rounded-4 shadow-sm p-4">
                    <h4 class="fw-bold text-dark mb-4">
                        <i class="fas fa-calculator me-2"></i>Order Summary
                    </h4>

                    <div class="summary-item d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span class="fw-bold">₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>

                    @if($order->tax_amount > 0)
                        <div class="summary-item d-flex justify-content-between mb-2">
                            <span class="text-muted">Tax:</span>
                            <span class="fw-bold">₹{{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                    @endif

                    @if($order->shipping_amount > 0)
                        <div class="summary-item d-flex justify-content-between mb-2">
                            <span class="text-muted">Shipping:</span>
                            <span class="fw-bold">₹{{ number_format($order->shipping_amount, 2) }}</span>
                        </div>
                    @endif

                    @if($order->coupon_code)
                        <div class="summary-item d-flex justify-content-between mb-2">
                            <span class="text-success">Discount ({{ $order->coupon_code }}):</span>
                            <span class="fw-bold text-success">-₹{{ number_format($order->subtotal - $order->total_amount + $order->tax_amount + $order->shipping_amount, 2) }}</span>
                        </div>
                    @endif

                    <hr class="my-3">

                    <div class="summary-total d-flex justify-content-between mb-3">
                        <span class="fw-bold fs-5">Total:</span>
                        <span class="fw-bold fs-5 text-primary">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>

                    <div class="payment-info">
                        <h6 class="fw-bold text-dark mb-2">Payment Information</h6>
                        <div class="payment-method mb-2">
                            <span class="text-muted">Method:</span>
                            <span class="fw-bold text-capitalize">{{ $order->payment_method }}</span>
                        </div>
                        <div class="payment-status mb-2">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                @if($order->shipping_address)
                    <div class="shipping-address-card bg-white rounded-4 shadow-sm p-4 mt-4">
                        <h4 class="fw-bold text-dark mb-4">
                            <i class="fas fa-shipping-fast me-2"></i>Shipping Address
                        </h4>

                        @if(is_array($order->shipping_address))
                            <address class="mb-0">
                                <strong>{{ $order->shipping_address['name'] ?? '' }}</strong><br>
                                {{ $order->shipping_address['address'] ?? '' }}<br>
                                {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }}<br>
                                {{ $order->shipping_address['pincode'] ?? '' }}<br>
                                {{ $order->shipping_address['country'] ?? '' }}
                            </address>
                        @else
                            <p class="mb-0">{{ $order->shipping_address }}</p>
                        @endif
                    </div>
                @endif

                <!-- Order Actions -->
                <div class="order-actions-card bg-white rounded-4 shadow-sm p-4 mt-4">
                    <h4 class="fw-bold text-dark mb-4">
                        <i class="fas fa-cog me-2"></i>Actions
                    </h4>

                    <div class="d-grid gap-2">
                        @if($order->payment_status === 'pending')
                            <button class="btn btn-success btn-lg" onclick="payNow({{ $order->id }})">
                                <i class="fas fa-credit-card me-2"></i>Pay Now (₹{{ number_format($order->total_amount, 2) }})
                            </button>
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Payment Pending:</strong> Please complete the payment to proceed with your order.
                            </div>
                        @endif

                        @if($order->status === 'pending' && $order->payment_status === 'paid')
                            <button class="btn btn-outline-danger" onclick="cancelOrder({{ $order->id }})">
                                <i class="fas fa-times me-1"></i>Cancel Order
                            </button>
                        @endif

                        @if($order->status === 'delivered')
                            <button class="btn btn-outline-success" onclick="reorderItems({{ $order->id }})">
                                <i class="fas fa-redo me-1"></i>Reorder Items
                            </button>
                        @endif

                        <a href="{{ route('customer.orders') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Orders
                        </a>

                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-1"></i>Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Notes -->
        @if($order->notes)
            <div class="row">
                <div class="col-12">
                    <div class="order-notes-card bg-white rounded-4 shadow-sm p-4">
                        <h4 class="fw-bold text-dark mb-3">
                            <i class="fas fa-sticky-note me-2"></i>Order Notes
                        </h4>
                        <p class="text-muted mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function cancelOrder(orderId) {
    if (confirm('Are you sure you want to cancel this order?')) {
        // TODO: Implement order cancellation
        alert('Order cancellation feature will be implemented soon.');
    }
}

function reorderItems(orderId) {
    if (confirm('Add all items from this order to your cart?')) {
        // TODO: Implement reorder functionality
        alert('Reorder feature will be implemented soon.');
    }
}

function payNow(orderId) {
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
    btn.disabled = true;

    fetch(`{{ url('/payment') }}/${orderId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            throw new Error('Invalid response from server');
        }
    })
    .then(data => {
        if (data.success) {
            initializeRazorpay(data);
        } else {
            alert(data.message || 'Failed to initialize payment. Please try again.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

function initializeRazorpay(data) {
    const options = {
        "key": data.razorpay.key_id,
        "amount": data.razorpay.amount,
        "currency": data.razorpay.currency,
        "name": "Nutrition Store",
        "description": "Order #" + data.order_number,
        "image": "https://your-logo-url.com/logo.png",
        "order_id": data.razorpay.order_id,
        "handler": function (response) {
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
                location.reload(); // Reload page to update payment status
            }
        }
    };

    const rzp = new Razorpay(options);
    rzp.open();
}

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

@section('styles')
<style>
.order-item {
    transition: background-color 0.3s ease;
    padding: 1rem;
    border-radius: 8px;
}

.order-item:hover {
    background-color: #f8f9fa;
}

.summary-item {
    padding: 0.5rem 0;
}

.summary-total {
    padding: 1rem 0;
    border-top: 2px solid #dee2e6;
}

.order-actions-card .btn {
    border-radius: 25px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.order-actions-card .btn:hover {
    transform: translateY(-1px);
}

.badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

@media (max-width: 768px) {
    .order-item .row > div {
        margin-bottom: 1rem;
    }

    .item-image {
        text-align: center;
    }

    .item-details {
        text-align: center;
    }

    .item-quantity {
        text-align: center;
    }

    .item-price {
        text-align: center;
    }
}
</style>
@endsection
