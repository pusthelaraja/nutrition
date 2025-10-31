@extends('layouts.frontend')

@section('title', $product->name . ' - ' . config('app.name'))

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
                <a href="{{ route('products.index') }}" class="text-white-50">
                    <i class="fas fa-box me-1"></i>Products
                </a>
            </li>
            @if($product->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index') }}?category={{ $product->category->slug }}" class="text-white-50">
                        <i class="fas fa-tag me-1"></i>{{ $product->category->name }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active text-white" aria-current="page">
                <i class="fas fa-cube me-1"></i>{{ $product->name }}
            </li>
        </ol>
    </div>
</nav>

<!-- Enhanced Product Detail Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <!-- Enhanced Product Images -->
            <div class="col-lg-6">
                <div class="product-gallery">
                    <!-- Main Product Image -->
                    <div class="main-image-container position-relative mb-3">
                        <div class="image-wrapper rounded-4 overflow-hidden shadow-lg bg-white p-4">
                            @if($product->featured_image)
                                <img src="{{ $product->featured_image }}"
                                     alt="{{ $product->name }}"
                                     class="img-fluid w-100 main-product-image"
                                     id="mainProductImage"
                                     style="height: 500px; object-fit: contain;">
                            @else
                                <img src="https://via.placeholder.com/600x600/10b981/ffffff?text={{ urlencode($product->name) }}"
                                     alt="{{ $product->name }}"
                                     class="img-fluid w-100 main-product-image"
                                     id="mainProductImage"
                                     style="height: 500px; object-fit: contain;">
                            @endif
                        </div>

                        <!-- Enhanced Product Badges -->
                        <div class="product-badges position-absolute top-0 start-0 p-3">
                            @if($product->is_featured)
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2 mb-2 d-block shadow-sm">
                                    <i class="fas fa-star me-1"></i>Featured
                                </span>
                            @endif
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="badge bg-danger fs-6 px-3 py-2 mb-2 d-block shadow-sm">
                                    <i class="fas fa-fire me-1"></i>Sale
                                </span>
                            @endif
                            @if($product->in_stock)
                                <span class="badge bg-success fs-6 px-3 py-2 mb-2 d-block shadow-sm">
                                    <i class="fas fa-check-circle me-1"></i>In Stock
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6 px-3 py-2 mb-2 d-block shadow-sm">
                                    <i class="fas fa-times-circle me-1"></i>Out of Stock
                                </span>
                            @endif
                        </div>

                        <!-- Image Zoom Button -->
                        <button class="btn btn-light position-absolute top-0 end-0 m-3 rounded-circle shadow-sm"
                                onclick="openImageModal()"
                                style="width: 50px; height: 50px;">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </div>

                    <!-- Thumbnail Gallery -->
                    @if($product->images && count($product->images) > 0)
                        <div class="thumbnail-gallery">
                            <div class="row g-2">
                                @foreach($product->images as $index => $image)
                                    <div class="col-3">
                                        <div class="thumbnail-item rounded-3 overflow-hidden shadow-sm cursor-pointer {{ $index === 0 ? 'active' : '' }}"
                                             onclick="changeMainImage('{{ $image->image_path }}')"
                                             style="height: 80px;">
                                            <img src="{{ $image->image_path }}"
                                                 alt="Thumbnail {{ $index + 1 }}"
                                                 class="img-fluid w-100 h-100"
                                                 style="object-fit: cover;">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Enhanced Product Information -->
            <div class="col-lg-6">
                <div class="product-details bg-white rounded-4 p-4 shadow-sm h-100">
                    <!-- Category -->
                    @if($product->category)
                        <div class="category-badge mb-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                <i class="fas fa-tag me-1"></i>
                                <a href="{{ route('products.index') }}?category={{ $product->category->slug }}"
                                   class="text-decoration-none text-primary">{{ $product->category->name }}</a>
                            </span>
                        </div>
                    @endif

                    <!-- Product Title -->
                    <h1 class="product-title mb-3 fw-bold text-dark">{{ $product->name }}</h1>

                    <!-- Enhanced Rating -->
                    <div class="product-rating mb-4">
                        <div class="d-flex align-items-center">
                            <div class="stars me-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="far fa-star text-warning"></i>
                            </div>
                            <span class="rating-text text-muted">
                                <strong class="text-dark">4.2</strong> •
                                <a href="#reviews" class="text-decoration-none text-primary">128 reviews</a>
                            </span>
                        </div>
                    </div>

                    <!-- Enhanced Price -->
                    <div class="product-price mb-4">
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <div class="d-flex align-items-center flex-wrap">
                                <span class="current-price h2 text-success fw-bold me-3">₹{{ number_format($product->sale_price, 2) }}</span>
                                <span class="original-price text-muted text-decoration-line-through fs-5 me-3">₹{{ number_format($product->price, 2) }}</span>
                                <span class="discount-badge bg-danger text-white px-3 py-2 rounded-pill fs-6 fw-bold">
                                    {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                                </span>
                            </div>
                            <div class="savings-text text-success mt-2">
                                <i class="fas fa-piggy-bank me-1"></i>
                                You save ₹{{ number_format($product->price - $product->sale_price, 2) }}
                            </div>
                        @else
                            <span class="current-price h2 text-primary fw-bold">₹{{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>

                    <!-- Enhanced Product Description -->
                    @if($product->description)
                        <div class="product-description mb-4">
                            <h5 class="fw-bold text-dark mb-3">
                                <i class="fas fa-info-circle text-primary me-2"></i>Description
                            </h5>
                            <div class="description-content bg-light p-3 rounded-3">
                                <p class="text-muted mb-0 lh-lg">{{ $product->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Enhanced Product Features -->
                    @if($product->features)
                        <div class="product-features mb-4">
                            <h5 class="fw-bold text-dark mb-3">
                                <i class="fas fa-star text-warning me-2"></i>Key Features
                            </h5>
                            <div class="features-list bg-light p-3 rounded-3">
                                <ul class="list-unstyled mb-0">
                                    @foreach(explode("\n", $product->features) as $feature)
                                        @if(trim($feature))
                                            <li class="mb-2 d-flex align-items-center">
                                                <i class="fas fa-check-circle text-success me-3"></i>
                                                <span class="text-dark">{{ trim($feature) }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Enhanced Quantity and Add to Cart -->
                    <div class="product-actions bg-light rounded-4 p-4 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-5 mb-3 mb-md-0">
                                <label for="quantity" class="form-label fw-bold text-dark mb-2">
                                    <i class="fas fa-sort-numeric-up me-1"></i>Quantity
                                </label>
                                <div class="quantity-selector">
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary rounded-start-pill"
                                                type="button"
                                                onclick="decreaseQuantity()"
                                                style="width: 50px;">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number"
                                               class="form-control text-center fw-bold border-0 bg-white"
                                               id="quantity"
                                               value="1"
                                               min="1"
                                               max="10"
                                               style="font-size: 1.1rem;">
                                        <button class="btn btn-outline-secondary rounded-end-pill"
                                                type="button"
                                                onclick="increaseQuantity()"
                                                style="width: 50px;">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="action-buttons">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-success btn-lg rounded-pill fw-bold py-3"
                                                onclick="addToCart({{ $product->id }}, document.getElementById('quantity') ? document.getElementById('quantity').value : 1, event)"
                                                style="font-size: 1.1rem;">
                                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                        </button>
                                        {{-- <div class="row g-2">
                                            <div class="col-6">
                                                <button class="btn btn-outline-primary rounded-pill w-100 py-2"
                                                        onclick="addToWishlist({{ $product->id }})">
                                                    <i class="far fa-heart me-1"></i>Wishlist
                                                </button>
                                            </div>
                                            <div class="col-6">
                                                <button class="btn btn-outline-secondary rounded-pill w-100 py-2"
                                                        onclick="shareProduct()">
                                                    <i class="fas fa-share-alt me-1"></i>Share
                                                </button>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Product Info -->
                    <div class="product-info bg-light rounded-4 p-4">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fas fa-info-circle text-primary me-2"></i>Product Information
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-item d-flex align-items-center">
                                    <i class="fas fa-barcode text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">SKU</small>
                                        <strong class="text-dark">{{ $product->sku ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item d-flex align-items-center">
                                    <i class="fas fa-weight text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Weight</small>
                                        <strong class="text-dark">{{ $product->weight ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="info-item d-flex align-items-center">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Added</small>
                                        <strong class="text-dark">{{ $product->created_at->format('M d, Y') }}</strong>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-6">
                                <div class="info-item d-flex align-items-center">
                                    <i class="fas fa-truck text-primary me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Shipping</small>
                                        <strong class="text-dark">Free Delivery</strong>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Enhanced Product Tabs Section -->
{{-- <section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Enhanced Tabs -->
                <ul class="nav nav-pills nav-fill mb-4" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-4 py-3 fw-bold"
                                id="description-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#description"
                                type="button"
                                role="tab">
                            <i class="fas fa-info-circle me-2"></i>Description
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 py-3 fw-bold"
                                id="ingredients-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#ingredients"
                                type="button"
                                role="tab">
                            <i class="fas fa-leaf me-2"></i>Ingredients
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 py-3 fw-bold"
                                id="usage-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#usage"
                                type="button"
                                role="tab">
                            <i class="fas fa-book-open me-2"></i>Usage Instructions
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4 py-3 fw-bold"
                                id="reviews-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#reviews"
                                type="button"
                                role="tab">
                            <i class="fas fa-star me-2"></i>Reviews (128)
                        </button>
                    </li>
                </ul>

                <!-- Enhanced Tab Content -->
                <div class="tab-content bg-light rounded-4 p-4" id="productTabsContent">
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        <div class="p-4">
                            <h5>Product Description</h5>
                            <p>{{ $product->description ?? 'No description available for this product.' }}</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="ingredients" role="tabpanel">
                        <div class="p-4">
                            <h5>Ingredients</h5>
                            <p>{{ $product->ingredients ?? 'Ingredients information not available.' }}</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="usage" role="tabpanel">
                        <div class="p-4">
                            <h5>Usage Instructions</h5>
                            <p>{{ $product->usage_instructions ?? 'Usage instructions not available.' }}</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <div class="p-4">
                            <h5>Customer Reviews</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="h2 text-primary">4.2</div>
                                        <div class="stars mb-2">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="far fa-star text-warning"></i>
                                        </div>
                                        <p class="text-muted">Based on 128 reviews</p>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="review-item mb-3">
                                        <div class="d-flex justify-content-between">
                                            <strong>John Doe</strong>
                                            <div class="stars">
                                                <i class="fas fa-star text-warning"></i>
                                                <i class="fas fa-star text-warning"></i>
                                                <i class="fas fa-star text-warning"></i>
                                                <i class="fas fa-star text-warning"></i>
                                                <i class="fas fa-star text-warning"></i>
                                            </div>
                                        </div>
                                        <p class="text-muted small">Great product! Highly recommended.</p>
                                    </div>
                                    <div class="review-item mb-3">
                                        <div class="d-flex justify-content-between">
                                            <strong>Jane Smith</strong>
                                            <div class="stars">
                                                <i class="fas fa-star text-warning"></i>
                                                <i class="fas fa-star text-warning"></i>
                                                <i class="fas fa-star text-warning"></i>
                                                <i class="fas fa-star text-warning"></i>
                                                <i class="far fa-star text-warning"></i>
                                            </div>
                                        </div>
                                        <p class="text-muted small">Good quality and fast delivery.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}

<!-- Related Products Section -->
{{-- @if($relatedProducts->count() > 0)
<section class="py-5">
    <div class="container">
        <h3 class="mb-4">Related Products</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="product-card">
                        <div class="product-image">
                            @if($relatedProduct->featured_image)
                                <img src="{{ $relatedProduct->featured_image }}" alt="{{ $relatedProduct->name }}" class="img-fluid">
                            @else
                                <img src="https://via.placeholder.com/300x200?text=No+Image" alt="{{ $relatedProduct->name }}" class="img-fluid">
                            @endif

                            @if($relatedProduct->is_featured)
                                <div class="product-badge">Featured</div>
                            @endif

                            @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                <div class="product-badge" style="background: #dc3545;">Sale</div>
                            @endif
                        </div>

                        <div class="product-info">
                            <h6 class="product-title">
                                <a href="{{ route('products.show', $relatedProduct->slug) }}" class="text-decoration-none">
                                    {{ $relatedProduct->name }}
                                </a>
                            </h6>
                            <p class="text-muted small mb-2">{{ $relatedProduct->category->name ?? 'Uncategorized' }}</p>

                            <div class="product-price">
                                @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                    <span class="current-price">₹{{ number_format($relatedProduct->sale_price, 2) }}</span>
                                    <span class="original-price">₹{{ number_format($relatedProduct->price, 2) }}</span>
                                @else
                                    <span class="current-price">₹{{ number_format($relatedProduct->price, 2) }}</span>
                                @endif
                            </div>

                            <button class="add-to-cart-btn" onclick="addToCart({{ $relatedProduct->id }}, 1, event)">
                                <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif --}}

@endsection

@push('scripts')
    </div>
</section>

<!-- Related Products Section -->
{{-- <section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="fw-bold text-center mb-5">
                    <i class="fas fa-heart text-primary me-2"></i>You Might Also Like
                </h3>
                <div class="row g-4">
                    @for($i = 0; $i < 4; $i++)
                        <div class="col-lg-3 col-md-6">
                            <div class="card product-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="position-relative">
                                    <img src="https://via.placeholder.com/300x300/10b981/ffffff?text=Product+{{ $i + 1 }}"
                                         class="card-img-top"
                                         alt="Related Product {{ $i + 1 }}"
                                         style="height: 250px; object-fit: cover;">
                                    <div class="position-absolute top-0 end-0 p-2">
                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <h6 class="card-title fw-bold mb-2">Related Product {{ $i + 1 }}</h6>
                                    <div class="rating mb-2">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="far fa-star text-warning"></i>
                                        <small class="text-muted ms-1">({{ rand(10, 50) }})</small>
                                    </div>
                                    <div class="price mb-3">
                                        <span class="h5 text-primary fw-bold">₹{{ rand(100, 500) }}</span>
                                    </div>
                                    <button class="btn btn-primary w-100 rounded-pill">
                                        <i class="fas fa-shopping-cart me-1"></i>Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</section> --}}

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" alt="Product Image" class="img-fluid rounded-4 shadow-lg" id="modalImage">
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced JavaScript Functions
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue < 10) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

function changeMainImage(imageUrl) {
    const mainImage = document.getElementById('mainProductImage');
    mainImage.src = imageUrl;

    // Update active thumbnail
    document.querySelectorAll('.thumbnail-item').forEach(item => {
        item.classList.remove('active');
    });
    event.target.closest('.thumbnail-item').classList.add('active');
}

function openImageModal() {
    const mainImage = document.getElementById('mainProductImage');
    const modalImage = document.getElementById('modalImage');
    modalImage.src = mainImage.src;

    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}

// addToCart function is now handled globally in the layout

function addToWishlist(productId) {
    const wishlistBtn = document.querySelector('[onclick="addToWishlist({{ $product->id }})"]');
    const icon = wishlistBtn.querySelector('i');

    if (icon.classList.contains('far')) {
        icon.classList.remove('far');
        icon.classList.add('fas');
        wishlistBtn.classList.remove('btn-outline-primary');
        wishlistBtn.classList.add('btn-danger');
        showNotification('Added to wishlist!', 'success');
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
        wishlistBtn.classList.remove('btn-danger');
        wishlistBtn.classList.add('btn-outline-primary');
        showNotification('Removed from wishlist!', 'info');
    }

    console.log('Toggling wishlist:', productId);
}

function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $product->name }}',
            text: 'Check out this amazing product!',
            url: window.location.href
        });
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            showNotification('Product link copied to clipboard!', 'success');
        });
    }
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to product cards
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Add click animation to buttons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;

            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });
    });
});

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    .thumbnail-item {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .thumbnail-item:hover {
        transform: scale(1.05);
    }

    .thumbnail-item.active {
        border: 3px solid #007bff !important;
    }

    .product-card {
        transition: all 0.3s ease;
    }

    .product-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }
`;
document.head.appendChild(style);
</script>
@endpush
