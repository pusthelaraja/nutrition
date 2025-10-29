@extends('layouts.frontend')

@section('title', 'Nutrition Store - Premium Health & Wellness Products')
@section('description', 'Discover premium nutrition products, supplements, and wellness solutions for a healthier lifestyle. Free shipping on orders above ₹999.')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">Transform Your Health Journey</h1>
                <p class="hero-subtitle">Premium nutrition products designed to fuel your wellness goals. Quality supplements, vitamins, and health solutions for every lifestyle.</p>
                <div class="d-flex gap-3 justify-content-start">
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Shop Now
                    </a>
                    <a href="#featured-products" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-eye me-2"></i>Explore Products
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-carousel-container">
                    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="hover">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="4" aria-label="Slide 5"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="5" aria-label="Slide 6"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="https://www.foodiaq.com/wp-content/uploads/2024/10/Biotin-ladoo.jpg" alt="Biotin Laddu" class="img-fluid rounded-3 shadow-lg">
                                <div class="carousel-caption">
                                    <h5>Biotin Laddu</h5>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://www.mygingergarlickitchen.com/wp-content/uploads/2021/12/dry-fruits-laddu-1.jpg" alt="Dry Fruit Laddu" class="img-fluid rounded-3 shadow-lg">
                                <div class="carousel-caption">
                                    <h5>Dry Fruit Laddu</h5>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://www.telanganapindivantalu.in/wp-content/uploads/2019/12/Nuvvula-laddu.jpg" alt="Nuvvula Laddu" class="img-fluid rounded-3 shadow-lg">
                                <div class="carousel-caption">
                                    <h5>Nuvvula Laddu</h5>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://villkart.com/cdn/shop/files/Ragi-ladoo.jpg?v=1743229463" alt="Raggi Laddu" class="img-fluid rounded-3 shadow-lg">
                                <div class="carousel-caption">
                                    <h5>Raggi Laddu</h5>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://lakshmikrishnanaturals.com/admin_panel/storage/app/public/images/blogs/83591712596484.jpg" alt="Herbal Bath Powder" class="img-fluid rounded-3 shadow-lg">
                                <div class="carousel-caption">
                                    <h5>Herbal Bath Powder</h5>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://m.media-amazon.com/images/I/716G3zyjUTL.jpg" alt="Organic Loofa" class="img-fluid rounded-3 shadow-lg">
                                <div class="carousel-caption">
                                    <h5>Organic Loofa</h5>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Products Section -->
{{-- <section class="section products-showcase">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <div class="showcase-header">
                    <span class="showcase-badge">Premium Quality</span>
                    <h2 class="showcase-title">Our Premium Products</h2>
                    <p class="showcase-description">Discover our carefully curated selection of health and wellness products</p>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-grid">
            <!-- Nutritional Foods Section -->
            <div class="category-section">
                <div class="category-header-modern">
                    <div class="category-icon-wrapper">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">Nutritional Foods</h3>
                        <p class="category-desc">Premium health supplements</p>
                    </div>
                </div>

                <div class="products-row">
                    <div class="product-card">
                        <div class="product-icon-modern">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <h4 class="product-title">Nutrimix Powder</h4>
                        <span class="product-tag premium">Premium</span>
                    </div>

                    <div class="product-card">
                        <div class="product-icon-modern">
                            <i class="fas fa-cookie-bite"></i>
                        </div>
                        <h4 class="product-title">Biotin Laddu</h4>
                        <span class="product-tag popular">Popular</span>
                    </div>

                    <div class="product-card">
                        <div class="product-icon-modern">
                            <i class="fas fa-cookie-bite"></i>
                        </div>
                        <h4 class="product-title">Dry Fruit Laddu</h4>
                        <span class="product-tag healthy">Healthy</span>
                    </div>

                    <div class="product-card">
                        <div class="product-icon-modern">
                            <i class="fas fa-cookie-bite"></i>
                        </div>
                        <h4 class="product-title">Nuvvula Laddu</h4>
                        <span class="product-tag traditional">Traditional</span>
                    </div>

                    <div class="product-card">
                        <div class="product-icon-modern">
                            <i class="fas fa-cookie-bite"></i>
                        </div>
                        <h4 class="product-title">Raggi Laddu</h4>
                        <span class="product-tag organic">Organic</span>
                    </div>
                </div>
            </div>

            <!-- Personal Care Section -->
            <div class="category-section">
                <div class="category-header-modern">
                    <div class="category-icon-wrapper">
                        <i class="fas fa-spa"></i>
                    </div>
                    <div class="category-info">
                        <h3 class="category-name">Personal Care</h3>
                        <p class="category-desc">Natural wellness products</p>
                    </div>
                </div>

                <div class="products-row">
                    <div class="product-card">
                        <div class="product-icon-modern">
                            <i class="fas fa-shampoo"></i>
                        </div>
                        <h4 class="product-title">Herbal Nourish Shampoo</h4>
                        <span class="product-tag natural">Natural</span>
                    </div>

                    <div class="product-card">
                        <div class="product-icon-modern">
                            <i class="fas fa-bath"></i>
                        </div>
                        <h4 class="product-title">Herbal Bath Powder</h4>
                        <span class="product-tag therapeutic">Therapeutic</span>
                    </div>

                    <div class="product-card">
                        <div class="product-icon-modern">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h4 class="product-title">Organic Loofa</h4>
                        <span class="product-tag eco">Eco-Friendly</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-modern">
            <div class="cta-content">
                <h3 class="cta-title">Ready to Transform Your Health?</h3>
                <p class="cta-subtitle">Explore our complete range of premium health products</p>
                <a href="{{ route('products.index') }}" class="cta-button">
                    <span class="button-text">View All Products</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section> --}}

<!-- Features Section -->
<section class="section bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-3 text-center mb-4">
                        <div class="p-4">
                            <i class="fas fa-shipping-fast fa-3x text-warning mb-3"></i>
                            <h5>Free Shipping</h5>
                            <p class="text-muted">Free delivery on orders above ₹999</p>
                        </div>
            </div>
            <div class="col-md-3 text-center mb-4">
                <div class="p-4">
                    <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                    <h5>Quality Assured</h5>
                    <p class="text-muted">100% authentic products with quality guarantee</p>
                </div>
            </div>
            <div class="col-md-3 text-center mb-4">
                <div class="p-4">
                    <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                    <h5>Expert Support</h5>
                    <p class="text-muted">24/7 customer support from nutrition experts</p>
                </div>
            </div>
            <div class="col-md-3 text-center mb-4">
                <div class="p-4">
                    <i class="fas fa-undo fa-3x text-info mb-3"></i>
                    <h5>Easy Returns</h5>
                    <p class="text-muted">30-day hassle-free return policy</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section id="featured-products" class="section">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <div class="row">
            @forelse($featuredProducts as $product)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="product-card">
                        <div class="product-image">
                            @if($product->featured_image)
                                <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" class="img-fluid">
                            @else
                                <img src="https://via.placeholder.com/300x200/10b981/ffffff?text={{ urlencode($product->name) }}" alt="{{ $product->name }}" class="img-fluid">
                            @endif

                            @if($product->is_featured)
                                <div class="product-badge">Featured</div>
                            @endif

                            @if($product->sale_price && $product->sale_price < $product->price)
                                <div class="product-badge" style="background: #dc3545;">Sale</div>
                            @endif
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">{{ $product->name }}</h3>
                            <p class="product-category text-muted small">{{ $product->category->name ?? 'Uncategorized' }}</p>
                            <div class="product-rating">★★★★☆ (4.2)</div>
                            <div class="product-price">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="current-price">₹{{ number_format($product->sale_price, 2) }}</span>
                                    <span class="original-price">₹{{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="current-price">₹{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-primary btn-sm me-2">
                                <i class="fas fa-eye me-1"></i>View Details
                            </a>
                            <button class="add-to-cart-btn" onclick="addToCart({{ $product->id }})">
                                <i class="fas fa-cart-plus me-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h4>No Featured Products</h4>
                        <p class="text-muted">Check back soon for our featured products!</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">View All Products</a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($featuredProducts->count() > 0)
            <div class="text-center mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-th-large me-2"></i>View All Products
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Categories Section -->
<section class="section bg-light">
    <div class="container">
        <h2 class="section-title">Shop by Category</h2>
        <div class="row">
            @forelse($categories->take(4) as $category)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-tag fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="card-text text-muted">{{ $category->description ?? 'Explore our ' . $category->name . ' products' }}</p>
                            <span class="badge bg-secondary mb-2">{{ $category->products_count ?? 0 }} Products</span>
                            <br>
                            <a href="{{ route('products.index') }}?category={{ $category->slug }}" class="btn btn-outline-primary">Shop Now</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                        <h4>No Categories Available</h4>
                        <p class="text-muted">Categories will appear here once they are added.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($categories->count() > 4)
            <div class="text-center mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-th-large me-2"></i>View All Categories
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="mb-4">Why Choose Nutrition Store?</h2>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-warning fa-2x me-3"></i>
                            <div>
                                <h5>Quality Assured</h5>
                                <p class="text-muted">All products are tested and verified for quality and authenticity.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-shipping-fast text-warning fa-2x me-3"></i>
                            <div>
                                <h5>Fast Delivery</h5>
                                <p class="text-muted">Quick and reliable delivery across India.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-user-md text-info fa-2x me-3"></i>
                            <div>
                                <h5>Expert Guidance</h5>
                                <p class="text-muted">Get advice from certified nutritionists and health experts.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-shield-alt text-primary fa-2x me-3"></i>
                            <div>
                                <h5>Secure Shopping</h5>
                                <p class="text-muted">Safe and secure payment processing with SSL encryption.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/500x400/fbbf24/ffffff?text=Quality+Assurance" alt="Quality Assurance" class="img-fluid rounded-3">
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="section bg-warning text-dark">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h3 class="mb-3">Stay Updated with Health Tips & Offers</h3>
                <p class="mb-0">Subscribe to our newsletter and get the latest health tips, product updates, and exclusive offers delivered to your inbox.</p>
            </div>
            <div class="col-lg-6">
                <div class="d-flex gap-2">
                    <input type="email" class="form-control" placeholder="Enter your email address">
                    <button class="btn btn-dark">
                        <i class="fas fa-paper-plane me-2"></i>Subscribe
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Newsletter subscription
    document.querySelector('.btn-light').addEventListener('click', function() {
        const email = document.querySelector('input[type="email"]').value;
        if (email) {
            alert('Thank you for subscribing! You will receive our latest updates soon.');
            document.querySelector('input[type="email"]').value = '';
        }
    });
</script>
@endpush
