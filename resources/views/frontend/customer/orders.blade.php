@extends('layouts.frontend')

@section('title', 'My Orders - ' . config('app.name'))

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
                <i class="fas fa-shopping-bag me-1"></i>My Orders
            </li>
        </ol>
    </div>
</nav>

<!-- Orders Section -->
<section class="py-5 bg-light">
    <div class="container">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="orders-header bg-white rounded-4 shadow-sm p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold text-dark mb-2">
                                <i class="fas fa-shopping-bag text-primary me-2"></i>My Orders
                            </h2>
                            <p class="text-muted mb-0">
                                <i class="fas fa-info-circle me-1"></i>Track and manage all your orders in one place
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-shopping-bag me-1"></i>Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Statistics -->
        @php
            $totalOrders = $orders->total();
            $pendingOrders = $orders->where('status', 'pending')->where('payment_status', 'paid')->count();
            $completedOrders = $orders->where('status', 'completed')->count();
            $pendingPayment = $orders->where('payment_status', 'pending')->count();
        @endphp

        @if($totalOrders > 0)
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card bg-white rounded-3 shadow-sm p-3 text-center">
                    <div class="stat-icon text-primary mb-2">
                        <i class="fas fa-shopping-bag fa-2x"></i>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $totalOrders }}</h4>
                    <small class="text-muted">Total Orders</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card bg-white rounded-3 shadow-sm p-3 text-center">
                    <div class="stat-icon text-warning mb-2">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $pendingOrders }}</h4>
                    <small class="text-muted">Pending Orders</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card bg-white rounded-3 shadow-sm p-3 text-center">
                    <div class="stat-icon text-success mb-2">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $completedOrders }}</h4>
                    <small class="text-muted">Completed</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card bg-white rounded-3 shadow-sm p-3 text-center">
                    <div class="stat-icon text-danger mb-2">
                        <i class="fas fa-credit-card fa-2x"></i>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $pendingPayment }}</h4>
                    <small class="text-muted">Payment Pending</small>
                </div>
            </div>
        </div>
        @endif

        @if($orders->count() > 0)
            <div class="row">
                @foreach($orders as $order)
                    <div class="col-12 mb-4">
                        <div class="order-card bg-white rounded-4 shadow-sm overflow-hidden">
                            <!-- Order Header -->
                            <div class="order-card-header bg-gradient-primary p-4 text-white">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <div class="order-icon me-3">
                                                <i class="fas fa-receipt fa-2x"></i>
                                            </div>
                                            <div>
                                                <h4 class="fw-bold mb-1">Order #{{ $order->order_number }}</h4>
                                                <p class="mb-0 opacity-75">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    {{ $order->created_at->format('M d, Y \a\t g:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <div class="order-status-badges">
                                            @if($order->status === 'completed')
                                                <span class="badge bg-success bg-light text-dark fs-6 px-3 py-2 mb-2 d-inline-block">
                                                    <i class="fas fa-check-circle me-1"></i>Completed
                                                </span>
                                            @elseif($order->status === 'pending')
                                                <span class="badge bg-warning bg-light text-dark fs-6 px-3 py-2 mb-2 d-inline-block">
                                                    <i class="fas fa-clock me-1"></i>Pending
                                                </span>
                                            @elseif($order->status === 'cancelled')
                                                <span class="badge bg-danger bg-light text-dark fs-6 px-3 py-2 mb-2 d-inline-block">
                                                    <i class="fas fa-times-circle me-1"></i>Cancelled
                                                </span>
                                            @else
                                                <span class="badge bg-info bg-light text-dark fs-6 px-3 py-2 mb-2 d-inline-block">
                                                    <i class="fas fa-shipping-fast me-1"></i>{{ ucfirst($order->status) }}
                                                </span>
                                            @endif
                                            @if($order->payment_status === 'pending')
                                                <span class="badge bg-danger bg-light text-dark fs-6 px-3 py-2 d-inline-block">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Payment Pending
                                                </span>
                                            @elseif($order->payment_status === 'paid')
                                                <span class="badge bg-success bg-light text-dark fs-6 px-3 py-2 d-inline-block">
                                                    <i class="fas fa-check-circle me-1"></i>Paid
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Body -->
                            <div class="order-card-body p-4">
                                <div class="row">
                                    <!-- Order Items Preview -->
                                    <div class="col-lg-8 mb-4 mb-lg-0">
                                        <h6 class="fw-bold text-dark mb-3">
                                            <i class="fas fa-box me-2 text-primary"></i>Order Items ({{ $order->items->count() }})
                                        </h6>
                                        <div class="order-items-list">
                                            @foreach($order->items->take(3) as $item)
                                                <div class="order-item-row d-flex align-items-center p-3 mb-2 bg-light rounded-3">
                                                    <div class="item-image me-3">
                                                        <img src="{{ $item->product->featured_image ?? 'https://via.placeholder.com/60x60/10b981/ffffff?text=Product' }}"
                                                             alt="{{ $item->product->name }}"
                                                             class="img-fluid rounded"
                                                             style="width: 60px; height: 60px; object-fit: cover;">
                                                    </div>
                                                    <div class="item-details flex-grow-1">
                                                        <h6 class="mb-1 fw-bold text-dark">{{ $item->product->name }}</h6>
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-secondary me-2">Qty: {{ $item->quantity }}</span>
                                                            <span class="text-muted">₹{{ number_format($item->unit_price, 2) }} each</span>
                                                        </div>
                                                    </div>
                                                    <div class="item-total text-end">
                                                        <h6 class="fw-bold text-primary mb-0">₹{{ number_format($item->total_price, 2) }}</h6>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if($order->items->count() > 3)
                                                <div class="text-center mt-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-plus-circle me-1"></i>
                                                        {{ $order->items->count() - 3 }} more {{ Str::plural('item', $order->items->count() - 3) }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Order Summary -->
                                    <div class="col-lg-4">
                                        <div class="order-summary-card bg-gradient-light rounded-3 p-4 h-100">
                                            <h6 class="fw-bold text-dark mb-3">
                                                <i class="fas fa-receipt me-2 text-primary"></i>Order Summary
                                            </h6>
                                            <div class="summary-row d-flex justify-content-between mb-2">
                                                <span class="text-muted">Subtotal:</span>
                                                <span class="fw-bold">₹{{ number_format($order->subtotal ?? $order->total_amount, 2) }}</span>
                                            </div>
                                            @if($order->shipping_amount > 0)
                                            <div class="summary-row d-flex justify-content-between mb-2">
                                                <span class="text-muted">Shipping:</span>
                                                <span class="fw-bold">₹{{ number_format($order->shipping_amount, 2) }}</span>
                                            </div>
                                            @endif
                                            @if($order->tax_amount > 0)
                                            <div class="summary-row d-flex justify-content-between mb-2">
                                                <span class="text-muted">Tax:</span>
                                                <span class="fw-bold">₹{{ number_format($order->tax_amount, 2) }}</span>
                                            </div>
                                            @endif
                                            @if($order->discount_amount > 0)
                                            <div class="summary-row d-flex justify-content-between mb-2 text-success">
                                                <span>Discount:</span>
                                                <span class="fw-bold">-₹{{ number_format($order->discount_amount, 2) }}</span>
                                            </div>
                                            @endif
                                            <hr class="my-3">
                                            <div class="summary-total d-flex justify-content-between mb-3">
                                                <span class="fw-bold fs-5">Total:</span>
                                                <span class="fw-bold fs-5 text-primary">₹{{ number_format($order->total_amount, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Footer -->
                            <div class="order-card-footer bg-light p-4 border-top">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="payment-info">
                                            <small class="text-muted">
                                                <i class="fas fa-credit-card me-1"></i>
                                                Payment Method: <span class="fw-bold text-capitalize">{{ $order->payment_method }}</span>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <div class="order-actions">
                                            <a href="{{ route('customer.order-details', $order->id) }}" class="btn btn-outline-primary me-2">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                            @if($order->payment_status === 'pending')
                                                <button class="btn btn-success me-2" onclick="payNow({{ $order->id }})">
                                                    <i class="fas fa-credit-card me-1"></i>Pay Now
                                                </button>
                                            @endif
                                            @if($order->status === 'pending' && $order->payment_status === 'paid')
                                                <button class="btn btn-outline-danger" onclick="cancelOrder({{ $order->id }})">
                                                    <i class="fas fa-times me-1"></i>Cancel
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="empty-orders bg-white rounded-4 shadow-sm text-center py-5 px-4">
                        <div class="empty-icon mb-4">
                            <div class="empty-icon-circle bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                <i class="fas fa-shopping-bag text-muted" style="font-size: 4rem;"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-dark mb-3">No Orders Yet</h3>
                        <p class="text-muted mb-4 fs-5">You haven't placed any orders yet.<br>Start shopping to see your orders here.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                        </a>
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

function payNow(orderId) {
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Loading...';
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

@push('styles')
<style>
/* Enhanced Orders Page Styles */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

/* Statistics Cards */
.stat-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
    border-color: #667eea;
}

.stat-icon {
    transition: transform 0.3s ease;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1);
}

/* Order Cards */
.order-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15) !important;
}

.order-card-header {
    border-bottom: 2px solid rgba(255,255,255,0.2);
}

.order-icon {
    opacity: 0.9;
}

.order-status-badges .badge {
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* Order Items */
.order-item-row {
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.order-item-row:hover {
    background-color: #fff !important;
    border-color: #667eea;
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.order-item-row img {
    transition: transform 0.3s ease;
}

.order-item-row:hover img {
    transform: scale(1.1);
}

/* Order Summary Card */
.order-summary-card {
    border: 2px solid #e9ecef;
}

.summary-row {
    padding: 0.5rem 0;
    font-size: 0.95rem;
}

.summary-total {
    padding-top: 1rem;
    border-top: 2px solid #dee2e6;
    font-size: 1.1rem;
}

/* Buttons */
.btn {
    border-radius: 25px;
    padding: 10px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
    letter-spacing: 0.3px;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
}

.btn-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #0d7a70 0%, #2fc964 100%);
}

/* Empty State */
.empty-orders {
    border: 2px dashed #dee2e6;
}

.empty-icon-circle {
    transition: all 0.3s ease;
}

.empty-orders:hover .empty-icon-circle {
    transform: scale(1.1);
    background-color: #f8f9fa !important;
}

/* Badges */
.badge {
    font-weight: 600;
    padding: 0.5rem 1rem;
    letter-spacing: 0.3px;
}

/* Header */
.orders-header {
    border-left: 4px solid #667eea;
}

/* Responsive */
@media (max-width: 768px) {
    .order-card-header {
        text-align: center;
    }

    .order-card-header .row > div {
        margin-bottom: 1rem;
    }

    .order-status-badges {
        text-align: center !important;
    }

    .order-status-badges .badge {
        display: block;
        margin: 0.25rem auto;
    }

    .order-actions {
        margin-top: 1rem;
        text-align: center;
    }

    .order-actions .btn {
        margin-bottom: 0.5rem;
        width: 100%;
        display: block;
    }

    .order-item-row {
        flex-direction: column;
        text-align: center;
    }

    .item-total {
        text-align: center !important;
        margin-top: 0.5rem;
    }

    .payment-info {
        text-align: center;
        margin-bottom: 1rem;
    }

    .stat-card {
        margin-bottom: 1rem;
    }
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.order-card {
    animation: fadeIn 0.5s ease-out;
}

.stat-card {
    animation: fadeIn 0.5s ease-out;
}

/* Pagination */
.pagination {
    justify-content: center;
}

.pagination .page-link {
    border-radius: 25px;
    margin: 0 2px;
    border: none;
    color: #667eea;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.pagination .page-link:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
}
</style>
@endpush
