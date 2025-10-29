@forelse($products as $product)
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4 product-item">
        <div class="product-card-modern">
            <div class="product-image-container">
                @if($product->featured_image)
                    <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" class="product-image">
                @else
                    <img src="https://via.placeholder.com/300x300?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="product-image">
                @endif

                <!-- Product Badges -->
                <div class="product-badges">
                    @if($product->is_featured)
                        <span class="badge badge-featured">Featured</span>
                    @endif
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="badge badge-sale">Sale</span>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="product-actions-overlay">
                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>Quick View
                    </a>
                    <button class="btn btn-outline-light btn-sm" onclick="addToWishlist({{ $product->id }})">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
            </div>

            <div class="product-info">
                <div class="product-category">{{ $product->category->name ?? 'Uncategorized' }}</div>
                <h6 class="product-title">
                    <a href="{{ route('products.show', $product->slug) }}" class="product-link">
                        {{ $product->name }}
                    </a>
                </h6>

                <div class="product-rating mb-2">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <span class="rating-text">(4.2)</span>
                </div>

                <div class="product-price">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="current-price">₹{{ number_format($product->sale_price, 2) }}</span>
                        <span class="original-price">₹{{ number_format($product->price, 2) }}</span>
                        <span class="discount-badge">
                            {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                        </span>
                    @else
                        <span class="current-price">₹{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <div class="product-actions">
                    <button class="btn btn-primary w-100" onclick="addToCart({{ $product->id }})">
                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-box-open"></i>
            </div>
            <h4>No Products Found</h4>
            <p>Try adjusting your filters or browse all products.</p>
            <button class="btn btn-primary" onclick="clearFilters()">
                <i class="fas fa-refresh me-2"></i>Clear Filters
            </button>
        </div>
    </div>
@endforelse
