@extends('layouts.frontend')

@section('title', 'Search Results - Nutrition Store')
@section('description', 'Find the best nutrition products with our advanced search and filtering options.')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Search Results</li>
        </ol>
    </div>
</nav>

<!-- Search Section -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Search Results</h2>
                <p class="text-muted mb-4">Showing results for: <strong>"{{ request('q', 'nutrition products') }}"</strong></p>
            </div>
        </div>

        <div class="row">
            <!-- Search Filters -->
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Refine Search</h5>
                    </div>
                    <div class="card-body">
                        <!-- Search Box -->
                        <div class="mb-4">
                            <label class="form-label">Search</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search products..." value="{{ request('q') }}" id="search-input">
                                <button class="btn btn-primary" onclick="performSearch()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Category</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat-all" checked>
                                <label class="form-check-label" for="cat-all">All Categories</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat-protein">
                                <label class="form-check-label" for="cat-protein">Protein Powders</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat-vitamins">
                                <label class="form-check-label" for="cat-vitamins">Vitamins</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat-supplements">
                                <label class="form-check-label" for="cat-supplements">Supplements</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat-weight">
                                <label class="form-check-label" for="cat-weight">Weight Management</label>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Price Range</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="price-0-500">
                                <label class="form-check-label" for="price-0-500">Under ₹500</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="price-500-1000">
                                <label class="form-check-label" for="price-500-1000">₹500 - ₹1,000</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="price-1000-2000">
                                <label class="form-check-label" for="price-1000-2000">₹1,000 - ₹2,000</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="price-2000-plus">
                                <label class="form-check-label" for="price-2000-plus">Above ₹2,000</label>
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Brand</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="brand-all" checked>
                                <label class="form-check-label" for="brand-all">All Brands</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="brand-premium">
                                <label class="form-check-label" for="brand-premium">Premium Brands</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="brand-budget">
                                <label class="form-check-label" for="brand-budget">Budget Friendly</label>
                            </div>
                        </div>

                        <!-- Rating Filter -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Customer Rating</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rating-5">
                                <label class="form-check-label" for="rating-5">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    & Up
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rating-4">
                                <label class="form-check-label" for="rating-4">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                    & Up
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rating-3">
                                <label class="form-check-label" for="rating-3">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                    & Up
                                </label>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100" onclick="applyFilters()">
                            <i class="fas fa-filter me-2"></i>Apply Filters
                        </button>
                        <button class="btn btn-outline-secondary w-100 mt-2" onclick="clearFilters()">
                            <i class="fas fa-times me-2"></i>Clear All
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search Results -->
            <div class="col-lg-9">
                <!-- Sort and View Options -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-0">Search Results</h4>
                        <p class="text-muted mb-0">Found 24 products matching your search</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <label class="form-label mb-0">Sort by:</label>
                            <select class="form-select" style="width: auto;" onchange="sortResults(this.value)">
                                <option value="relevance">Relevance</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                                <option value="rating">Customer Rating</option>
                                <option value="newest">Newest First</option>
                            </select>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary active" onclick="setView('grid')">
                                <i class="fas fa-th"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="setView('list')">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Search Results Grid -->
                <div class="row" id="search-results">
                    <!-- Result 1 -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://via.placeholder.com/300x200/10b981/ffffff?text=Whey+Protein" alt="Premium Whey Protein">
                                <div class="product-badge">Best Seller</div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">Premium Whey Protein</h3>
                                <div class="product-rating">★★★★☆ (4.2)</div>
                                <div class="product-price">
                                    <span class="current-price">₹2,499</span>
                                    <span class="original-price">₹3,299</span>
                                </div>
                                <button class="add-to-cart-btn" onclick="addToCart(1)">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Result 2 -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://via.placeholder.com/300x200/f59e0b/ffffff?text=Multivitamin" alt="Daily Multivitamin">
                                <div class="product-badge">New</div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">Daily Multivitamin</h3>
                                <div class="product-rating">★★★★★ (4.8)</div>
                                <div class="product-price">
                                    <span class="current-price">₹899</span>
                                    <span class="original-price">₹1,199</span>
                                </div>
                                <button class="add-to-cart-btn" onclick="addToCart(2)">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Result 3 -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://via.placeholder.com/300x200/ef4444/ffffff?text=Omega+3" alt="Omega-3 Fish Oil">
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">Omega-3 Fish Oil</h3>
                                <div class="product-rating">★★★★☆ (4.5)</div>
                                <div class="product-price">
                                    <span class="current-price">₹1,299</span>
                                    <span class="original-price">₹1,599</span>
                                </div>
                                <button class="add-to-cart-btn" onclick="addToCart(3)">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Result 4 -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://via.placeholder.com/300x200/8b5cf6/ffffff?text=Vitamin+D" alt="Vitamin D3">
                                <div class="product-badge">Sale</div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">Vitamin D3</h3>
                                <div class="product-rating">★★★★☆ (4.3)</div>
                                <div class="product-price">
                                    <span class="current-price">₹599</span>
                                    <span class="original-price">₹799</span>
                                </div>
                                <button class="add-to-cart-btn" onclick="addToCart(4)">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Result 5 -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://via.placeholder.com/300x200/06b6d4/ffffff?text=Creatine" alt="Creatine Monohydrate">
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">Creatine Monohydrate</h3>
                                <div class="product-rating">★★★★☆ (4.6)</div>
                                <div class="product-price">
                                    <span class="current-price">₹1,899</span>
                                    <span class="original-price">₹2,199</span>
                                </div>
                                <button class="add-to-cart-btn" onclick="addToCart(5)">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Result 6 -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://via.placeholder.com/300x200/84cc16/ffffff?text=BCAA" alt="BCAA Powder">
                                <div class="product-badge">Popular</div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">BCAA Powder</h3>
                                <div class="product-rating">★★★★★ (4.7)</div>
                                <div class="product-price">
                                    <span class="current-price">₹1,499</span>
                                    <span class="original-price">₹1,799</span>
                                </div>
                                <button class="add-to-cart-btn" onclick="addToCart(6)">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <nav aria-label="Search results pagination" class="mt-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- No Results Section (Hidden by default) -->
<section class="section d-none" id="no-results">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h3>No products found</h3>
                    <p class="text-muted mb-4">We couldn't find any products matching your search criteria. Try adjusting your filters or search terms.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button class="btn btn-outline-primary" onclick="clearFilters()">
                            <i class="fas fa-filter me-2"></i>Clear Filters
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-th-large me-2"></i>Browse All Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Perform search
    function performSearch() {
        const query = document.getElementById('search-input').value.trim();
        if (query) {
            window.location.href = `{{ route('search') }}?q=${encodeURIComponent(query)}`;
        }
    }

    // Apply filters
    function applyFilters() {
        console.log('Applying filters...');
        // This would typically make an AJAX request to filter results
        // For now, just show a loading state
        document.getElementById('search-results').innerHTML = '<div class="col-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';

        // Simulate API call
        setTimeout(() => {
            location.reload();
        }, 1000);
    }

    // Clear filters
    function clearFilters() {
        // Clear all checkboxes
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        // Check "All" options
        document.getElementById('cat-all').checked = true;
        document.getElementById('brand-all').checked = true;

        // Clear search input
        document.getElementById('search-input').value = '';

        // Reload results
        location.reload();
    }

    // Sort results
    function sortResults(sortBy) {
        console.log('Sorting by:', sortBy);
        // This would typically make an AJAX request to sort results
        location.reload();
    }

    // Set view (grid/list)
    function setView(viewType) {
        const gridBtn = document.querySelector('.btn-group .btn:first-child');
        const listBtn = document.querySelector('.btn-group .btn:last-child');

        if (viewType === 'grid') {
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
            document.getElementById('search-results').className = 'row';
        } else {
            listBtn.classList.add('active');
            gridBtn.classList.remove('active');
            document.getElementById('search-results').className = 'row list-view';
        }
    }

    // addToCart function is now handled globally in the layout

    // Search on Enter key
    document.getElementById('search-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
</script>

<style>
    .list-view .col-lg-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .list-view .product-card {
        display: flex;
        flex-direction: row;
        height: auto;
    }

    .list-view .product-image {
        width: 200px;
        height: 150px;
        flex-shrink: 0;
    }

    .list-view .product-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
</style>
@endpush
