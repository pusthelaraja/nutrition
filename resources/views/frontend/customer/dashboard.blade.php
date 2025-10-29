@extends('layouts.frontend')

@section('title', 'My Dashboard - ' . config('app.name'))

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
                <i class="fas fa-tachometer-alt me-1"></i>My Dashboard
            </li>
        </ol>
    </div>
</nav>

<!-- Dashboard Section -->
<section class="py-5 bg-light">
    <div class="container">
        <!-- Welcome Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="welcome-card bg-white rounded-4 shadow-sm p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold text-dark mb-2">
                                <i class="fas fa-user-circle text-primary me-2"></i>
                                Welcome back, {{ $customer->first_name }}!
                            </h2>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Last login: {{ $customer->updated_at->format('M d, Y \a\t g:i A') }}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="quick-actions">
                                <a href="{{ route('products.index') }}" class="btn btn-primary me-2">
                                    <i class="fas fa-shopping-bag me-1"></i>Shop Now
                                </a>
                                <a href="{{ route('customer.profile') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-user-edit me-1"></i>Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card bg-white rounded-4 shadow-sm p-4 text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-shopping-bag text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">{{ $orderStats['total_orders'] }}</h3>
                    <p class="text-muted mb-0">Total Orders</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card bg-white rounded-4 shadow-sm p-4 text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-clock text-warning" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">{{ $orderStats['pending_orders'] }}</h3>
                    <p class="text-muted mb-0">Pending Orders</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card bg-white rounded-4 shadow-sm p-4 text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">{{ $orderStats['completed_orders'] }}</h3>
                    <p class="text-muted mb-0">Completed Orders</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card bg-white rounded-4 shadow-sm p-4 text-center">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-rupee-sign text-info" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">₹{{ number_format($orderStats['total_spent'], 2) }}</h3>
                    <p class="text-muted mb-0">Total Spent</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Recent Orders -->
            <div class="col-lg-8">
                <div class="dashboard-card bg-white rounded-4 shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold text-dark mb-0">
                            <i class="fas fa-history text-primary me-2"></i>Recent Orders
                        </h4>
                        <a href="{{ route('customer.orders') }}" class="btn btn-outline-primary btn-sm">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>

                    @if($recentOrders->count() > 0)
                        <div class="orders-list">
                            @foreach($recentOrders as $order)
                                <div class="order-item border-bottom pb-3 mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <div class="order-info">
                                                <h6 class="fw-bold text-dark mb-1">#{{ $order->order_number }}</h6>
                                                <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="order-status">
                                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="order-total">
                                                <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-end">
                                            <a href="{{ route('customer.order-details', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-bag text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mb-2">No orders yet</h5>
                            <p class="text-muted mb-3">You haven't placed any orders yet.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-1"></i>Start Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions & Cart -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                <div class="dashboard-card bg-white rounded-4 shadow-sm p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-4">
                        <i class="fas fa-bolt text-primary me-2"></i>Quick Actions
                    </h5>
                    <div class="quick-actions-list">
                        <a href="{{ route('customer.profile') }}" class="quick-action-item d-flex align-items-center p-3 rounded-3 mb-2 text-decoration-none">
                            <div class="action-icon me-3">
                                <i class="fas fa-user-edit text-primary"></i>
                            </div>
                            <div class="action-content">
                                <h6 class="mb-0 text-dark">Edit Profile</h6>
                                <small class="text-muted">Update your personal information</small>
                            </div>
                        </a>
                        <a href="{{ route('customer.addresses') }}" class="quick-action-item d-flex align-items-center p-3 rounded-3 mb-2 text-decoration-none">
                            <div class="action-icon me-3">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </div>
                            <div class="action-content">
                                <h6 class="mb-0 text-dark">Manage Addresses</h6>
                                <small class="text-muted">Add or edit shipping addresses</small>
                            </div>
                        </a>
                        <a href="{{ route('customer.orders') }}" class="quick-action-item d-flex align-items-center p-3 rounded-3 mb-2 text-decoration-none">
                            <div class="action-icon me-3">
                                <i class="fas fa-shopping-bag text-primary"></i>
                            </div>
                            <div class="action-content">
                                <h6 class="mb-0 text-dark">Order History</h6>
                                <small class="text-muted">View all your orders</small>
                            </div>
                        </a>
                        <a href="{{ route('customer.wishlist') }}" class="quick-action-item d-flex align-items-center p-3 rounded-3 mb-2 text-decoration-none">
                            <div class="action-icon me-3">
                                <i class="fas fa-heart text-primary"></i>
                            </div>
                            <div class="action-content">
                                <h6 class="mb-0 text-dark">Wishlist</h6>
                                <small class="text-muted">Your saved products</small>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Current Cart -->
                @if($cart && !$cart->isEmpty())
                    <div class="dashboard-card bg-white rounded-4 shadow-sm p-4">
                        <h5 class="fw-bold text-dark mb-4">
                            <i class="fas fa-shopping-cart text-primary me-2"></i>Current Cart
                        </h5>
                        <div class="cart-summary">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">{{ $cart->total_items }} items</span>
                                <strong class="text-dark">₹{{ number_format($cart->final_amount, 2) }}</strong>
                            </div>
                            <a href="{{ route('cart.index') }}" class="btn btn-primary w-100">
                                <i class="fas fa-shopping-cart me-1"></i>View Cart
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.welcome-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.stat-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

.dashboard-card {
    transition: box-shadow 0.3s ease;
}

.dashboard-card:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
}

.quick-action-item {
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.quick-action-item:hover {
    background-color: #f8f9fa !important;
    border-color: #dee2e6;
    transform: translateX(5px);
}

.order-item {
    transition: background-color 0.3s ease;
}

.order-item:hover {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin: 0 -1rem 1rem -1rem;
}

.action-icon {
    width: 40px;
    height: 40px;
    background-color: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon {
    width: 60px;
    height: 60px;
    background-color: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

@media (max-width: 768px) {
    .quick-actions {
        text-align: center;
        margin-top: 1rem;
    }

    .quick-actions .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection
