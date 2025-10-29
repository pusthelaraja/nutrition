@extends('layouts.frontend')

@section('title', 'Products')

@section('content')
<div class="products-page">
    <div class="container py-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-header">
                    <h1 class="page-title">Our Products</h1>
                    <p class="page-subtitle">Discover our wide range of premium nutrition products</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Sidebar - Filters -->
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="filters-sidebar">
                    <div class="filters-header">
                        <h5 class="filters-title">
                            <i class="fas fa-filter me-2"></i>Filters
                        </h5>
                        <button class="btn btn-sm btn-outline-secondary d-lg-none" onclick="toggleFilters()">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>

                    <div class="filters-content" id="filtersContent">
                        <form method="GET" id="filterForm" action="{{ route('products.index') }}">
                            <!-- Categories Filter -->
                            <div class="filter-section">
                                <h6 class="filter-section-title">
                                    <i class="fas fa-tags me-2"></i>Categories
                                </h6>
                                <div class="filter-options">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" value="" id="category-all" {{ !request('category') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="category-all">
                                            All Categories
                                        </label>
                                    </div>
                                    @foreach($categories as $category)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="category" value="{{ $category->slug }}" id="category-{{ $category->id }}" {{ request('category') == $category->slug ? 'checked' : '' }}>
                                            <label class="form-check-label" for="category-{{ $category->id }}">
                                                {{ $category->name }}
                                                <span class="badge bg-secondary ms-2">{{ $category->products_count ?? 0 }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Price Range Filter -->
                            <div class="filter-section">
                                <h6 class="filter-section-title">
                                    <i class="fas fa-rupee-sign me-2"></i>Price Range
                                </h6>
                                <div class="filter-options">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="price_range" value="" id="price-all" {{ !request('price_range') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="price-all">All Prices</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="price_range" value="0-500" id="price-0-500" {{ request('price_range') == '0-500' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="price-0-500">Under ₹500</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="price_range" value="500-1000" id="price-500-1000" {{ request('price_range') == '500-1000' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="price-500-1000">₹500 - ₹1000</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="price_range" value="1000-2000" id="price-1000-2000" {{ request('price_range') == '1000-2000' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="price-1000-2000">₹1000 - ₹2000</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="price_range" value="2000+" id="price-2000+" {{ request('price_range') == '2000+' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="price-2000+">Above ₹2000</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Features Filter -->
                            <div class="filter-section">
                                <h6 class="filter-section-title">
                                    <i class="fas fa-star me-2"></i>Product Features
                                </h6>
                                <div class="filter-options">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="featured" value="1" id="featured" {{ request('featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="featured">
                                            <i class="fas fa-star text-warning me-1"></i>Featured Products
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sale" value="1" id="sale" {{ request('sale') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sale">
                                            <i class="fas fa-tag text-danger me-1"></i>On Sale
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="in_stock" {{ request('in_stock') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="in_stock">
                                            <i class="fas fa-check-circle text-success me-1"></i>In Stock
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Sort Options -->
                            <div class="filter-section">
                                <h6 class="filter-section-title">
                                    <i class="fas fa-sort me-2"></i>Sort By
                                </h6>
                                <div class="filter-options">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sort" value="" id="sort-default" {{ !request('sort') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sort-default">Default</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sort" value="newest" id="sort-newest" {{ request('sort') == 'newest' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sort-newest">Newest First</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sort" value="price_low" id="sort-price-low" {{ request('sort') == 'price_low' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sort-price-low">Price: Low to High</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sort" value="price_high" id="sort-price-high" {{ request('sort') == 'price_high' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sort-price-high">Price: High to Low</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sort" value="name" id="sort-name" {{ request('sort') == 'name' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sort-name">Name A-Z</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Actions -->
                            <div class="filter-actions">
                                <button type="button" class="btn btn-primary w-100 mb-2" onclick="applyFilters()">
                                    <i class="fas fa-search me-2"></i>Apply Filters
                                </button>
                                <button type="submit" class="btn btn-warning w-100 mb-2">
                                    <i class="fas fa-hand-paper me-2"></i>Manual Submit
                                </button>
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                    <i class="fas fa-times me-2"></i>Clear All
                                </button>
                                <button type="button" class="btn btn-info w-100 mt-2" onclick="testFilters()">
                                    <i class="fas fa-bug me-2"></i>Test Filters
                                </button>
                                <button type="button" class="btn btn-success w-100 mt-2" onclick="simpleFilterTest()">
                                    <i class="fas fa-check me-2"></i>Simple Test
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Content - Products -->
            <div class="col-lg-9 col-md-8">
                <!-- Products Header -->
                <div class="products-header mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="results-info">
                                <h6 class="mb-0">
                                    Showing {{ $products->count() }} of {{ $products->total() }} products
                                </h6>
                                @if(request()->hasAny(['category', 'price_range', 'featured', 'sale', 'in_stock', 'sort']))
                                    <small class="text-muted">Filtered results</small>
                                @endif

                                <!-- Debug: Show active filters -->
                                @if(config('app.debug'))
                                    <div class="mt-2">
                                        <small class="text-info">
                                            <strong>Active Filters:</strong>
                                            @if(request('category'))
                                                Category: {{ request('category') }} |
                                            @endif
                                            @if(request('price_range'))
                                                Price: {{ request('price_range') }} |
                                            @endif
                                            @if(request('featured'))
                                                Featured |
                                            @endif
                                            @if(request('sale'))
                                                Sale |
                                            @endif
                                            @if(request('in_stock'))
                                                In Stock |
                                            @endif
                                            @if(request('sort'))
                                                Sort: {{ request('sort') }}
                                            @endif
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="view-options d-flex justify-content-end gap-2">
                                <button class="btn btn-outline-secondary btn-sm active" onclick="setViewMode('grid')" id="gridView">
                                    <i class="fas fa-th"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="setViewMode('list')" id="listView">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="products-container" id="productsContainer">
                    <div class="row" id="productsGrid">
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
                                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                                        <i class="fas fa-refresh me-2"></i>View All Products
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="pagination-wrapper">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Global functions that need to be accessible from HTML onclick attributes
function setViewMode(mode) {
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    const productsGrid = document.getElementById('productsGrid');

    // Update button states
    if (gridView) gridView.classList.toggle('active', mode === 'grid');
    if (listView) listView.classList.toggle('active', mode === 'list');

    // Update grid classes
    if (productsGrid) {
        if (mode === 'list') {
            productsGrid.className = 'row list-view';
            productsGrid.querySelectorAll('.product-item').forEach(item => {
                item.className = 'col-12 mb-3 product-item list-item';
            });
        } else {
            productsGrid.className = 'row grid-view';
            productsGrid.querySelectorAll('.product-item').forEach(item => {
                item.className = 'col-xl-3 col-lg-4 col-md-6 mb-4 product-item grid-item';
            });
        }
    }
}

// addToCart function is now handled globally in the layout

function addToWishlist(productId) {
    console.log('Adding to wishlist:', productId);
    // Implement wishlist functionality
    // You can add AJAX call here
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Products page loaded');

    // Initialize filters
    initializeFilters();

    // Initialize view mode
    initializeViewMode();

    // Debug: Check if form exists
    const form = document.getElementById('filterForm');
    if (form) {
        console.log('Filter form found:', form);
    } else {
        console.error('Filter form not found!');
    }

    // Debug: Check filter inputs
    const filterInputs = document.querySelectorAll('#filterForm input');
    console.log('Found filter inputs:', filterInputs.length);
});

function initializeFilters() {
    console.log('Initializing filters...');

    // Wait a bit to ensure DOM is fully loaded
    setTimeout(() => {
        const form = document.getElementById('filterForm');
        if (!form) {
            console.error('Filter form not found during initialization!');
            return;
        }

        // Auto-submit form when filters change
        const filterInputs = form.querySelectorAll('input');
        console.log('Setting up listeners for', filterInputs.length, 'inputs');

        filterInputs.forEach((input, index) => {
            console.log(`Setting up listener for input ${index}:`, input.name, input.type);
            input.addEventListener('change', function() {
                console.log('Filter changed:', this.name, this.value, this.checked);
                // Apply filters immediately when any input changes
                applyFilters();
            });
        });
    }, 100);
}

function applyFilters() {
    console.log('applyFilters() called - using AJAX');

    const form = document.getElementById('filterForm');
    if (!form) {
        console.error('Filter form not found in applyFilters!');
        return;
    }

    // Show loading state
    showFilterLoading();

    // Build filter parameters
    const params = new URLSearchParams();
    const checkedInputs = form.querySelectorAll('input:checked');

    console.log('Found checked inputs:', checkedInputs.length);

    checkedInputs.forEach(input => {
        if (input.value && input.value !== '') {
            console.log('Adding parameter:', input.name, '=', input.value);
            params.append(input.name, input.value);
        }
    });

    // Make AJAX request
    const queryString = params.toString();
    const url = queryString ? `{{ route('products.index') }}?${queryString}` : '{{ route('products.index') }}';

    console.log('Making AJAX request to:', url);

    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update products grid
        const currentProductsGrid = document.getElementById('productsGrid');
        if (currentProductsGrid && data.products_html) {
            currentProductsGrid.innerHTML = data.products_html;
        }

        // Update results info
        const currentResultsInfo = document.querySelector('.results-info');
        if (currentResultsInfo && data.results_info_html) {
            currentResultsInfo.innerHTML = data.results_info_html;
        }

        // Hide loading state
        hideFilterLoading();

        console.log('Filters applied successfully');
    })
    .catch(error => {
        console.error('Error applying filters:', error);
        hideFilterLoading();
        alert('Error applying filters. Please try again.');
    });
}

function clearFilters() {
    console.log('Clearing all filters - using AJAX');

    // Uncheck all filter inputs
    const form = document.getElementById('filterForm');
    if (form) {
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => {
            input.checked = false;
        });
    }

    // Apply filters with no parameters (clear state)
    applyFilters();
}

function showFilterLoading() {
    // Show loading state on apply button
    const applyButton = document.querySelector('.filter-actions .btn-primary');
    if (applyButton) {
        applyButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
        applyButton.disabled = true;
    }

    // Show loading overlay on products grid
    const productsGrid = document.getElementById('productsGrid');
    if (productsGrid) {
        productsGrid.style.opacity = '0.6';
        productsGrid.style.pointerEvents = 'none';
    }
}

function hideFilterLoading() {
    // Hide loading state on apply button
    const applyButton = document.querySelector('.filter-actions .btn-primary');
    if (applyButton) {
        applyButton.innerHTML = '<i class="fas fa-search me-2"></i>Apply Filters';
        applyButton.disabled = false;
    }

    // Hide loading overlay on products grid
    const productsGrid = document.getElementById('productsGrid');
    if (productsGrid) {
        productsGrid.style.opacity = '1';
        productsGrid.style.pointerEvents = 'auto';
    }
}

function testFilters() {
    console.log('Testing filters...');

    // Check if form exists
    const form = document.getElementById('filterForm');
    if (!form) {
        console.error('Filter form not found!');
        return;
    }

    // Check all inputs
    const inputs = form.querySelectorAll('input');
    console.log('Total inputs found:', inputs.length);

    inputs.forEach((input, index) => {
        console.log(`Input ${index}:`, {
            name: input.name,
            type: input.type,
            value: input.value,
            checked: input.checked
        });
    });

    // Test applyFilters function
    console.log('Testing applyFilters function...');
    applyFilters();
}

function simpleFilterTest() {
    console.log('Simple filter test - testing AJAX with category filter');

    // Show loading state
    showFilterLoading();

    // Make AJAX request with category filter
    const url = '{{ route('products.index') }}?category=nutritional-powders';

    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update products grid
        const currentProductsGrid = document.getElementById('productsGrid');
        if (currentProductsGrid && data.products_html) {
            currentProductsGrid.innerHTML = data.products_html;
        }

        // Update results info
        const currentResultsInfo = document.querySelector('.results-info');
        if (currentResultsInfo && data.results_info_html) {
            currentResultsInfo.innerHTML = data.results_info_html;
        }

        // Hide loading state
        hideFilterLoading();

        console.log('Simple test completed successfully');
    })
    .catch(error => {
        console.error('Error in simple test:', error);
        hideFilterLoading();
        alert('Error in simple test. Please try again.');
    });
}

function toggleFilters() {
    const filtersContent = document.getElementById('filtersContent');
    filtersContent.classList.toggle('show');
}

function initializeViewMode() {
    // Set default view mode
    setViewMode('grid');
}
</script>
@endsection
