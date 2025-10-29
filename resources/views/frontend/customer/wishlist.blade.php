@extends('layouts.frontend')

@section('title', 'My Wishlist - ' . config('app.name'))

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
                <i class="fas fa-heart me-1"></i>My Wishlist
            </li>
        </ol>
    </div>
</nav>

<!-- Wishlist Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="wishlist-header bg-white rounded-4 shadow-sm p-4 mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h2 class="fw-bold text-dark mb-0">
                                <i class="fas fa-heart text-primary me-2"></i>My Wishlist
                            </h2>
                            <p class="text-muted mb-0">Save products you love for later</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-1"></i>Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="wishlist-empty text-center py-5">
                    <div class="empty-icon mb-4">
                        <i class="fas fa-heart text-muted" style="font-size: 5rem;"></i>
                    </div>
                    <h3 class="text-muted mb-3">Your wishlist is empty</h3>
                    <p class="text-muted mb-4">Start adding products to your wishlist to save them for later.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.wishlist-empty {
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
</style>
@endsection
