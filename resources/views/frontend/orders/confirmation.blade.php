@extends('layouts.frontend')

@section('title', 'Order Confirmation - ' . config('app.name'))

@section('content')
<!-- Enhanced Breadcrumb -->
<nav aria-label="breadcrumb" class="py-3" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-white-50">
                    <i class="fas fa-home me-1"></i>Home
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('cart.index') }}" class="text-white-50">
                    <i class="fas fa-shopping-cart me-1"></i>Cart
                </a>
            </li>
            <li class="breadcrumb-item active text-white" aria-current="page">
                <i class="fas fa-check-circle me-1"></i>Order Confirmation
            </li>
        </ol>
    </div>
</nav>

<!-- Order Confirmation Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Success Header -->
                <div class="text-center mb-5">
                    <div class="success-icon mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h1 class="fw-bold text-success mb-3">Order Confirmed!</h1>
                    <p class="text-muted fs-5">Thank you for your purchase. Your order has been successfully placed.</p>
                </div>

                <!-- Order Summary Card -->
                <div class="order-summary-card bg-white rounded-4 shadow-sm p-5 mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="fw-bold text-dark mb-3">
                                <i class="fas fa-receipt text-primary me-2"></i>Order Details
                            </h4>
                            <div class="order-info">
                                <div class="info-item mb-2">
                                    <strong>Order Number:</strong>
                                    <span class="text-primary fw-bold">#{{ $order->order_number }}</span>
                                </div>
                                <div class="info-item mb-2">
                                    <strong>Order Date:</strong>
                                    {{ $order->created_at->format('M d, Y \a\t g:i A') }}
                                </div>
                                <div class="info-item mb-2">
                                    <strong>Status:</strong>
                                    <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : 'success' }} fs-6 px-3 py-2">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                <div class="info-item mb-2">
                                    <strong>Payment Method:</strong>
                                    {{ ucfirst($order->payment_method) }}
                                </div>
                                <div class="info-item mb-2">
                                    <strong>Payment Status:</strong>
                                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }} fs-6 px-3 py-2">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="fw-bold text-dark mb-3">
                                <i class="fas fa-user text-primary me-2"></i>Customer Information
                            </h4>
                            <div class="customer-info">
                                <div class="info-item mb-2">
                                    <strong>Name:</strong> {{ $order->customer->first_name }} {{ $order->customer->last_name }}
                                </div>
                                <div class="info-item mb-2">
                                    <strong>Email:</strong> {{ $order->customer->email }}
                                </div>
                                <div class="info-item mb-2">
                                    <strong>Phone:</strong> {{ $order->customer->phone }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="order-items-card bg-white rounded-4 shadow-sm p-5 mb-4">
                    <h4 class="fw-bold text-dark mb-4">
                        <i class="fas fa-box text-primary me-2"></i>Order Items
                    </h4>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
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
                                                    <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                </div>
                                            </div>
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

                <!-- Order Summary -->
                <div class="order-totals-card bg-white rounded-4 shadow-sm p-5 mb-4">
                    <h4 class="fw-bold text-dark mb-4">
                        <i class="fas fa-calculator text-primary me-2"></i>Order Summary
                    </h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="totals-breakdown">
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
                        <div class="col-md-6">
                            <div class="shipping-address">
                                <h6 class="fw-bold text-dark mb-3">Shipping Address</h6>
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
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons text-center">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('customer.orders') }}" class="btn btn-outline-primary btn-lg w-100">
                                <i class="fas fa-list me-2"></i>View All Orders
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('orders.tracking', ['order_number' => $order->order_number]) }}" class="btn btn-outline-info btn-lg w-100">
                                <i class="fas fa-truck me-2"></i>Track Order
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="additional-info bg-white rounded-4 shadow-sm p-4 mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="fas fa-envelope text-primary me-2"></i>What's Next?
                            </h6>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>You'll receive an order confirmation email shortly</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>We'll process your order within 1-2 business days</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>You'll receive tracking information once shipped</li>
                                <li class="mb-0"><i class="fas fa-check text-success me-2"></i>Expected delivery: 3-5 business days</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="fas fa-question-circle text-primary me-2"></i>Need Help?
                            </h6>
                            <ul class="list-unstyled text-muted">
                                <li class="mb-2"><i class="fas fa-phone text-primary me-2"></i>Call us: +91 9876543210</li>
                                <li class="mb-2"><i class="fas fa-envelope text-primary me-2"></i>Email: support@nutritionstore.com</li>
                                <li class="mb-2"><i class="fas fa-comments text-primary me-2"></i>Live chat available 24/7</li>
                                <li class="mb-0"><i class="fas fa-clock text-primary me-2"></i>Customer service: 9 AM - 6 PM</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.success-icon {
    animation: bounceIn 1s ease-out;
}

@keyframes bounceIn {
    0% {
        transform: scale(0.3);
        opacity: 0;
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.order-summary-card,
.order-items-card,
.order-totals-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.order-summary-card:hover,
.order-items-card:hover,
.order-totals-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.info-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.info-item:last-child {
    border-bottom: none;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.btn {
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
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
