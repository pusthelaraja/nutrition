@extends('layouts.frontend')

@section('title', 'Order Details - ' . $order->order_number)

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
                <i class="fas fa-receipt me-1"></i>Order Details
            </li>
        </ol>
    </div>
</nav>

<!-- Order Details Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Order Header -->
                <div class="order-header-card bg-white rounded-4 shadow-sm p-4 mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h2 class="fw-bold text-dark mb-1">Order #{{ $order->order_number }}</h2>
                            <p class="text-muted mb-0">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="order-status-badges">
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'info')) }} fs-6 px-4 py-2 me-2">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }} fs-6 px-4 py-2">
                                    Payment {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <!-- Order Items -->
                    <div class="col-lg-8">
                        <div class="order-items-card bg-white rounded-4 shadow-sm p-4 mb-4">
                            <h4 class="fw-bold text-dark mb-4">
                                <i class="fas fa-box text-primary me-2"></i>Order Items
                            </h4>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>SKU</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $item->product->featured_image ?? 'https://via.placeholder.com/60x60/10b981/ffffff?text=Product' }}"
                                                             alt="{{ $item->product->name }}"
                                                             class="img-fluid rounded me-3"
                                                             style="width: 60px; height: 60px; object-fit: cover;">
                                                        <div>
                                                            <h6 class="mb-0 text-dark">{{ $item->product->name }}</h6>
                                                            <small class="text-muted">{{ Str::limit($item->product->short_description, 50) }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted">{{ $item->product->sku }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary fs-6 px-3 py-2">{{ $item->quantity }}</span>
                                                </td>
                                                <td>₹{{ number_format($item->price, 2) }}</td>
                                                <td class="fw-bold">₹{{ number_format($item->total_price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Order Timeline -->
                        <div class="order-timeline-card bg-white rounded-4 shadow-sm p-4">
                            <h4 class="fw-bold text-dark mb-4">
                                <i class="fas fa-route text-primary me-2"></i>Order Timeline
                            </h4>
                            <div class="timeline">
                                <div class="timeline-item completed">
                                    <div class="timeline-marker">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold text-success">Order Placed</h6>
                                        <p class="text-muted mb-0">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                                        <small class="text-success">Your order has been placed successfully.</small>
                                    </div>
                                </div>

                                <div class="timeline-item {{ $order->payment_status === 'paid' ? 'completed' : '' }}">
                                    <div class="timeline-marker">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold {{ $order->payment_status === 'paid' ? 'text-success' : 'text-warning' }}">
                                            Payment {{ $order->payment_status === 'paid' ? 'Confirmed' : 'Pending' }}
                                        </h6>
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
                                        <h6 class="fw-bold {{ in_array($order->status, ['processing', 'shipped', 'delivered', 'completed']) ? 'text-success' : 'text-muted' }}">
                                            Processing
                                        </h6>
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
                                        <h6 class="fw-bold {{ in_array($order->status, ['shipped', 'delivered', 'completed']) ? 'text-success' : 'text-muted' }}">
                                            Shipped
                                        </h6>
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
                                        <h6 class="fw-bold {{ in_array($order->status, ['delivered', 'completed']) ? 'text-success' : 'text-muted' }}">
                                            Delivered
                                        </h6>
                                        <p class="text-muted mb-0">{{ in_array($order->status, ['delivered', 'completed']) ? $order->updated_at->format('M d, Y \a\t g:i A') : 'Not delivered' }}</p>
                                        <small class="{{ in_array($order->status, ['delivered', 'completed']) ? 'text-success' : 'text-muted' }}">
                                            Your order has been delivered.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4">
                        <!-- Order Summary -->
                        <div class="order-summary-card bg-white rounded-4 shadow-sm p-4 mb-4">
                            <h4 class="fw-bold text-dark mb-4">
                                <i class="fas fa-calculator text-primary me-2"></i>Order Summary
                            </h4>
                            <div class="summary-breakdown">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>₹{{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                @if($order->discount_amount > 0)
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>Discount ({{ $order->coupon_code }}):</span>
                                        <span>- ₹{{ number_format($order->discount_amount, 2) }}</span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span>₹{{ number_format($order->shipping_amount, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax (18% GST):</span>
                                    <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-2 fw-bold fs-5">
                                    <span>Total:</span>
                                    <span class="text-primary">₹{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="shipping-address-card bg-white rounded-4 shadow-sm p-4 mb-4">
                            <h4 class="fw-bold text-dark mb-4">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>Shipping Address
                            </h4>
                            @php
                                $shippingAddress = json_decode($order->shipping_address, true);
                            @endphp
                            <address class="text-muted">
                                <strong>{{ $shippingAddress['first_name'] }} {{ $shippingAddress['last_name'] }}</strong><br>
                                {{ $shippingAddress['address_line_1'] }}<br>
                                @if($shippingAddress['address_line_2'])
                                    {{ $shippingAddress['address_line_2'] }}<br>
                                @endif
                                {{ $shippingAddress['city'] }}, {{ $shippingAddress['state'] }} {{ $shippingAddress['postal_code'] }}<br>
                                {{ $shippingAddress['country'] }}<br>
                                <strong>Phone:</strong> {{ $shippingAddress['phone'] }}
                            </address>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons-card bg-white rounded-4 shadow-sm p-4">
                            <h4 class="fw-bold text-dark mb-4">
                                <i class="fas fa-cogs text-primary me-2"></i>Actions
                            </h4>
                            <div class="d-grid gap-2">
                                <a href="{{ route('orders.tracking', ['order_number' => $order->order_number]) }}" class="btn btn-outline-info">
                                    <i class="fas fa-truck me-2"></i>Track Order
                                </a>
                                <a href="{{ route('orders.invoice', $order->id) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-download me-2"></i>Download Invoice
                                </a>
                                @if($order->status === 'pending')
                                    <button class="btn btn-outline-danger" onclick="cancelOrder({{ $order->id }})">
                                        <i class="fas fa-times me-2"></i>Cancel Order
                                    </button>
                                @else
                                    <a href="{{ route('orders.reorder', $order->id) }}" class="btn btn-outline-success">
                                        <i class="fas fa-redo me-2"></i>Reorder Items
                                    </a>
                                @endif
                                <a href="{{ route('customer.orders') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list me-2"></i>Back to Orders
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
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
.order-header-card,
.order-items-card,
.order-timeline-card,
.order-summary-card,
.shipping-address-card,
.action-buttons-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.order-header-card:hover,
.order-items-card:hover,
.order-timeline-card:hover,
.order-summary-card:hover,
.shipping-address-card:hover,
.action-buttons-card:hover {
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

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
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

.badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}
</style>
@endsection
