@extends('layouts.frontend')

@section('title', 'Track Your Order - ' . config('app.name'))

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
                <a href="{{ route('customer.orders') }}" class="text-white-50">
                    <i class="fas fa-shopping-bag me-1"></i>My Orders
                </a>
            </li>
            <li class="breadcrumb-item active text-white" aria-current="page">
                <i class="fas fa-truck me-1"></i>Track Order
            </li>
        </ol>
    </div>
</nav>

<!-- Order Tracking Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Tracking Header -->
                <div class="tracking-header text-center mb-5">
                    <h1 class="fw-bold text-dark mb-3">
                        <i class="fas fa-truck text-primary me-2"></i>Track Your Order
                    </h1>
                    <p class="text-muted fs-5">Enter your order number to track your package</p>
                </div>

                <!-- Order Search Form -->
                <div class="tracking-form-card bg-white rounded-4 shadow-sm p-5 mb-4">
                    <form method="GET" action="{{ route('orders.tracking') }}">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="order_number" class="form-label fw-bold">
                                    <i class="fas fa-receipt text-primary me-1"></i>Order Number
                                </label>
                                <input type="text"
                                       class="form-control form-control-lg @error('order_number') is-invalid @enderror"
                                       id="order_number"
                                       name="order_number"
                                       value="{{ $orderNumber }}"
                                       placeholder="Enter your order number (e.g., ORD-1234567890-1234)"
                                       required>
                                @error('order_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-search me-2"></i>Track Order
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                @if($order)
                    <!-- Order Found -->
                    <div class="order-tracking-card bg-white rounded-4 shadow-sm p-5 mb-4">
                        <!-- Order Header -->
                        <div class="order-header mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="fw-bold text-dark mb-1">Order #{{ $order->order_number }}</h4>
                                    <p class="text-muted mb-0">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'info')) }} fs-5 px-4 py-2">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Order Timeline -->
                        <div class="order-timeline mb-4">
                            <h5 class="fw-bold text-dark mb-3">
                                <i class="fas fa-route text-primary me-2"></i>Order Timeline
                            </h5>
                            <div class="timeline">
                                <div class="timeline-item {{ $order->status !== 'cancelled' ? 'completed' : '' }}">
                                    <div class="timeline-marker">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold">Order Placed</h6>
                                        <p class="text-muted mb-0">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                                        <small class="text-success">Your order has been placed successfully.</small>
                                    </div>
                                </div>

                                <div class="timeline-item {{ $order->payment_status === 'paid' ? 'completed' : '' }}">
                                    <div class="timeline-marker">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold">Payment {{ $order->payment_status === 'paid' ? 'Confirmed' : 'Pending' }}</h6>
                                        <p class="text-muted mb-0">{{ $order->payment_status === 'paid' ? $order->updated_at->format('M d, Y \a\t g:i A') : 'Waiting for payment' }}</p>
                                        <small class="{{ $order->payment_status === 'paid' ? 'text-success' : 'text-warning' }}">
                                            {{ $order->payment_status === 'paid' ? 'Payment has been confirmed.' : 'Waiting for payment confirmation.' }}
                                        </small>
                                    </div>
                                </div>

                                <div class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered', 'completed']) ? 'completed' : '' }}">
                                    <div class="timeline-marker">
                                        <i class="fas fa-cogs"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold">Processing</h6>
                                        <p class="text-muted mb-0">{{ in_array($order->status, ['processing', 'shipped', 'delivered', 'completed']) ? $order->updated_at->format('M d, Y \a\t g:i A') : 'Not started' }}</p>
                                        <small class="{{ in_array($order->status, ['processing', 'shipped', 'delivered', 'completed']) ? 'text-success' : 'text-muted' }}">
                                            Your order is being prepared for shipment.
                                        </small>
                                    </div>
                                </div>

                                <div class="timeline-item {{ in_array($order->status, ['shipped', 'delivered', 'completed']) ? 'completed' : '' }}">
                                    <div class="timeline-marker">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold">Shipped</h6>
                                        <p class="text-muted mb-0">{{ in_array($order->status, ['shipped', 'delivered', 'completed']) ? $order->updated_at->format('M d, Y \a\t g:i A') : 'Not shipped' }}</p>
                                        <small class="{{ in_array($order->status, ['shipped', 'delivered', 'completed']) ? 'text-success' : 'text-muted' }}">
                                            Your order has been shipped.
                                        </small>
                                    </div>
                                </div>

                                <div class="timeline-item {{ in_array($order->status, ['delivered', 'completed']) ? 'completed' : '' }}">
                                    <div class="timeline-marker">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold">Delivered</h6>
                                        <p class="text-muted mb-0">{{ in_array($order->status, ['delivered', 'completed']) ? $order->updated_at->format('M d, Y \a\t g:i A') : 'Not delivered' }}</p>
                                        <small class="{{ in_array($order->status, ['delivered', 'completed']) ? 'text-success' : 'text-muted' }}">
                                            Your order has been delivered.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="order-details">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-dark mb-3">
                                        <i class="fas fa-box text-primary me-2"></i>Order Items
                                    </h6>
                                    <div class="order-items">
                                        @foreach($order->items as $item)
                                            <div class="order-item d-flex align-items-center mb-2">
                                                <img src="{{ $item->product->featured_image ?? 'https://via.placeholder.com/40x40/10b981/ffffff?text=Product' }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="img-fluid rounded me-2"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 text-dark">{{ $item->product->name }}</h6>
                                                    <small class="text-muted">Qty: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}</small>
                                                </div>
                                                <div class="text-end">
                                                    <strong>₹{{ number_format($item->total_price, 2) }}</strong>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold text-dark mb-3">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>Shipping Address
                                    </h6>
                                    @php
                                        $shippingAddress = json_decode($order->shipping_address, true);
                                    @endphp
                                    <address class="text-muted">
                                        {{ $shippingAddress['first_name'] }} {{ $shippingAddress['last_name'] }}<br>
                                        {{ $shippingAddress['address_line_1'] }}<br>
                                        @if($shippingAddress['address_line_2'])
                                            {{ $shippingAddress['address_line_2'] }}<br>
                                        @endif
                                        {{ $shippingAddress['city'] }}, {{ $shippingAddress['state'] }} {{ $shippingAddress['postal_code'] }}<br>
                                        {{ $shippingAddress['country'] }}<br>
                                        <strong>Phone:</strong> {{ $shippingAddress['phone'] }}
                                    </address>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons text-center mt-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <a href="{{ route('orders.confirmation', $order->id) }}" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-receipt me-2"></i>View Details
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('orders.invoice', $order->id) }}" class="btn btn-outline-info w-100">
                                        <i class="fas fa-download me-2"></i>Download Invoice
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    @if($order->status === 'pending')
                                        <button class="btn btn-outline-danger w-100" onclick="cancelOrder({{ $order->id }})">
                                            <i class="fas fa-times me-2"></i>Cancel Order
                                        </button>
                                    @else
                                        <a href="{{ route('orders.reorder', $order->id) }}" class="btn btn-outline-success w-100">
                                            <i class="fas fa-redo me-2"></i>Reorder
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($orderNumber)
                    <!-- Order Not Found -->
                    <div class="order-not-found bg-white rounded-4 shadow-sm p-5 text-center">
                        <i class="fas fa-search text-muted mb-3" style="font-size: 3rem;"></i>
                        <h4 class="text-muted mb-3">Order Not Found</h4>
                        <p class="text-muted mb-4">We couldn't find an order with the number "{{ $orderNumber }}". Please check your order number and try again.</p>
                        <a href="{{ route('customer.orders') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>View My Orders
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
function cancelOrder(orderId) {
    if (confirm('Are you sure you want to cancel this order?')) {
        fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error cancelling order');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error cancelling order');
        });
    }
}
</script>
@endsection

@section('styles')
<style>
.tracking-form-card,
.order-tracking-card,
.order-not-found {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.tracking-form-card:hover,
.order-tracking-card:hover,
.order-not-found:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
    padding-left: 2rem;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
    color: white;
}

.timeline-item.completed .timeline-content h6 {
    color: #28a745;
}

.timeline-marker {
    position: absolute;
    left: -1rem;
    top: 0;
    width: 2rem;
    height: 2rem;
    background: #dee2e6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    color: #6c757d;
    z-index: 1;
}

.timeline-content h6 {
    margin-bottom: 0.25rem;
    color: #495057;
}

.timeline-content p {
    margin-bottom: 0.25rem;
}

.timeline-content small {
    font-size: 0.75rem;
}

.order-item {
    padding: 0.75rem;
    border: 1px solid #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.order-item:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6;
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

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
}

.badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}
</style>
@endsection
