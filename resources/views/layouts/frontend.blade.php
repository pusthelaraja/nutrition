<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Nutrition Store - Healthy Living Made Easy')</title>
    <meta name="description" content="@yield('description', 'Premium nutrition products for a healthy lifestyle. Quality supplements, vitamins, and wellness products.')">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">

    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="header">
        <!-- Top Bar -->
        <div class="header-top">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-phone"></i>
                            <span>+91 9876543210</span>
                            <i class="fas fa-envelope"></i>
                            <span>info@nutritionstore.com</span>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="d-flex align-items-center justify-content-end gap-3">
                            <span>Free Shipping on orders above ₹999</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="header-main">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <a href="{{ route('home') }}" class="logo">
                            <i class="fas fa-leaf me-2"></i>Nutrition Store
                        </a>
                    </div>
                    <div class="col-md-6">
                        <div class="search-bar">
                            <input type="text" class="search-input" placeholder="Search for products, brands, and more...">
                            <button class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="header-actions">
                            <a href="#" class="header-icon">
                                <i class="fas fa-heart"></i>
                                <span>Wishlist</span>
                            </a>
                            <a href="{{ route('cart.index') }}" class="header-icon">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Cart</span>
                                <span class="cart-count" id="cartCount">{{ $cartCount ?? 0 }}</span>
                            </a>
                            @auth('customer')
                                <div class="dropdown">
                                    <a href="#" class="header-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user"></i>
                                        <span>{{ Auth::guard('customer')->user()->first_name }}</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                        <li><a class="dropdown-item" href="{{ route('customer.profile') }}"><i class="fas fa-user me-2"></i>My Profile</a></li>
                                        <li><a class="dropdown-item" href="{{ route('customer.orders') }}"><i class="fas fa-shopping-bag me-2"></i>My Orders</a></li>
                                        <li><a class="dropdown-item" href="{{ route('customer.addresses') }}"><i class="fas fa-map-marker-alt me-2"></i>Addresses</a></li>
                                        <li><a class="dropdown-item" href="{{ route('customer.wishlist') }}"><i class="fas fa-heart me-2"></i>Wishlist</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <div class="dropdown">
                                    <a href="#" class="header-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user"></i>
                                        <span>Account</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-2"></i>Login</a></li>
                                        <li><a class="dropdown-item" href="{{ route('register') }}"><i class="fas fa-user-plus me-2"></i>Register</a></li>
                                    </ul>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="main-nav">
            <div class="container">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link">
                            <i class="fas fa-home me-2"></i>Home
                        </a>
                    </li>
                    <li class="nav-item dropdown mega-dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="exploreProductsDropdown" role="button">
                            <i class="fas fa-th-large me-2"></i>Explore Products
                        </a>
                        <div class="dropdown-menu mega-menu" aria-labelledby="exploreProductsDropdown">
                            <div class="container">
                                <div class="row">
                                    <!-- Dynamic Categories -->
                                    @if(isset($categories) && $categories->count() > 0)
                                        @foreach($categories->take(3) as $category)
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mega-column">
                                                    <h6 class="mega-title">
                                                        <i class="fas fa-tag me-2"></i>{{ $category->name }}
                                                    </h6>
                                                    <div class="mega-products-list">
                                                        @if($category->products->count() > 0)
                                                            @foreach($category->products->take(5) as $product)
                                                                <a href="{{ route('products.show', $product->slug) }}" class="mega-product-link">
                                                                    {{ $product->name }}
                                                                </a>
                                                            @endforeach
                                                        @else
                                                            <!-- Debug: Show what products are available for this category -->
                                                            @php
                                                                $personalCareProducts = collect();
                                                                if($category->name == 'Personal Care' || $category->name == 'personal care' || $category->name == 'Personal care') {
                                                                    $personalCareProducts = \App\Models\Product::where('is_active', true)
                                                                        ->where(function($query) {
                                                                            $query->where('name', 'like', '%Herbal Nourish Shampoo%')
                                                                                  ->orWhere('name', 'like', '%Herbal Bath Powder%')
                                                                                  ->orWhere('name', 'like', '%Organic Loofa%');
                                                                        })
                                                                        ->get();
                                                                }
                                                            @endphp

                                                            @if($personalCareProducts->count() > 0)
                                                                @foreach($personalCareProducts as $product)
                                                                    <a href="{{ route('products.show', $product->slug) }}" class="mega-product-link">
                                                                        {{ $product->name }}
                                                                    </a>
                                                                @endforeach
                                                            @else
                                                                <a href="{{ route('products.index') }}?category={{ $category->slug }}" class="mega-product-link">
                                                                    View All {{ $category->name }} Products
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Fallback: Static Categories -->
                                        <div class="col-lg-3 col-md-6">
                                            <div class="mega-column">
                                                <h6 class="mega-title">
                                                    <i class="fas fa-utensils me-2"></i>Nutritional Foods
                                                </h6>
                                                <div class="mega-products-list">
                                                    <a href="{{ route('products.index') }}?search=Nutrimix powder" class="mega-product-link">
                                                        Nutrimix Powder
                                                    </a>
                                                    <a href="{{ route('products.index') }}?search=Biotin Laddu" class="mega-product-link">
                                                        Biotin Laddu
                                                    </a>
                                                    <a href="{{ route('products.index') }}?search=Dry Fruit Laddu" class="mega-product-link">
                                                        Dry Fruit Laddu
                                                    </a>
                                                    <a href="{{ route('products.index') }}?search=Nuvvula Laddu" class="mega-product-link">
                                                        Nuvvula Laddu
                                                    </a>
                                                    <a href="{{ route('products.index') }}?search=Raggi Laddu" class="mega-product-link">
                                                        Raggi Laddu
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <div class="mega-column">
                                                <h6 class="mega-title">
                                                    <i class="fas fa-spa me-2"></i>Personal Care
                                                </h6>
                                                <div class="mega-products-list">
                                                    <a href="{{ route('products.index') }}?search=Herbal Nourish Shampoo" class="mega-product-link">
                                                        Herbal Nourish Shampoo
                                                    </a>
                                                    <a href="{{ route('products.index') }}?search=Herbal Bath Powder" class="mega-product-link">
                                                        Herbal Bath Powder
                                                    </a>
                                                    <a href="{{ route('products.index') }}?search=Organic Loofa" class="mega-product-link">
                                                        Organic Loofa
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                    {{-- <!-- Shop by Brand Column - COMMENTED OUT -->
                                    <div class="col-lg-3 col-md-6">
                                        <div class="mega-column">
                                            <h6 class="mega-title">
                                                <i class="fas fa-tags me-2"></i>Shop by Price
                                            </h6>
                                            <ul class="mega-list">
                                                <li><a href="{{ route('products.index') }}?price_range=0-500" class="mega-link">
                                                    <i class="fas fa-rupee-sign me-2"></i>Under ₹500
                                                    <span class="badge bg-success ms-2">Budget</span>
                                                </a></li>
                                                <li><a href="{{ route('products.index') }}?price_range=500-1000" class="mega-link">
                                                    <i class="fas fa-tag me-2"></i>₹500 - ₹1000
                                                    <span class="badge bg-primary ms-2">Mid Range</span>
                                                </a></li>
                                                <li><a href="{{ route('products.index') }}?price_range=1000-2000" class="mega-link">
                                                    <i class="fas fa-crown me-2"></i>₹1000 - ₹2000
                                                    <span class="badge bg-warning text-dark ms-2">Premium</span>
                                                </a></li>
                                                <li><a href="{{ route('products.index') }}?price_range=2000+" class="mega-link">
                                                    <i class="fas fa-gem me-2"></i>Above ₹2000
                                                    <span class="badge bg-danger ms-2">Luxury</span>
                                                </a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Quick Links Column - COMMENTED OUT -->
                                    <div class="col-lg-3 col-md-6">
                                        <div class="mega-column">
                                            <h6 class="mega-title">
                                                <i class="fas fa-bolt me-2"></i>Quick Links
                                            </h6>
                                            <ul class="mega-list">
                                                <li><a href="{{ route('products.index') }}?sort=newest" class="mega-link">
                                                    <i class="fas fa-sparkles me-2"></i>New Arrivals
                                                    <span class="badge bg-primary ms-2">New</span>
                                                </a></li>
                                                <li><a href="{{ route('products.index') }}?featured=true" class="mega-link">
                                                    <i class="fas fa-star me-2"></i>Featured Products
                                                    <span class="badge bg-warning text-dark ms-2">⭐</span>
                                                </a></li>
                                                <li><a href="{{ route('products.index') }}?sale=true" class="mega-link">
                                                    <i class="fas fa-percent me-2"></i>On Sale
                                                    <span class="badge bg-danger ms-2">Sale</span>
                                                </a></li>
                                                <li><a href="{{ route('products.index') }}?in_stock=true" class="mega-link">
                                                    <i class="fas fa-check-circle me-2"></i>In Stock
                                                    <span class="badge bg-success ms-2">Available</span>
                                                </a></li>
                                            </ul>

                                            <!-- Special Offers -->
                                            <div class="mega-offer mt-3">
                                                <div class="offer-card">
                                                    <div class="offer-icon">
                                                        <i class="fas fa-gift"></i>
                                                    </div>
                                                    <div class="offer-content">
                                                        <h6 class="offer-title">Special Offers</h6>
                                                        <p class="offer-text">Get 20% off on your first order</p>
                                                        <a href="{{ route('products.index') }}?offer=first-order" class="btn btn-sm btn-warning">
                                                            Claim Now
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('products.index') }}" class="nav-link">
                            <i class="fas fa-box me-2"></i>All Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-percent me-2"></i>Offers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('contact') }}" class="nav-link">
                            <i class="fas fa-phone me-2"></i>Contact
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="footer-section">
                        <h5>About Us</h5>
                        <a href="#">Our Story</a>
                        <a href="#">Careers</a>
                        <a href="#">Press</a>
                        <a href="#">Blog</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="footer-section">
                        <h5>Customer Service</h5>
                        <a href="#">Contact Us</a>
                        <a href="#">FAQ</a>
                        <a href="#">Shipping Info</a>
                        <a href="#">Returns</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="footer-section">
                        <h5>Quick Links</h5>
                        <a href="#">Track Order</a>
                        <a href="#">Size Guide</a>
                        <a href="#">Gift Cards</a>
                        <a href="#">Store Locator</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="footer-section">
                        <h5>Connect With Us</h5>
                        <div class="d-flex gap-2 mb-3">
                            <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                        </div>
                        <p class="mb-2">Download our app</p>
                        <div class="d-flex gap-2">
                            <img src="https://via.placeholder.com/120x40/000/fff?text=App+Store" alt="App Store" style="height: 40px;">
                            <img src="https://via.placeholder.com/120x40/000/fff?text=Google+Play" alt="Google Play" style="height: 40px;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Nutrition Store. All rights reserved. | Privacy Policy | Terms of Service</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Search functionality
        document.querySelector('.search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value;
                if (query.trim()) {
                    window.location.href = `{{ route('search') }}?q=${encodeURIComponent(query)}`;
                }
            }
        });

        // Cart functionality
        function updateCartCount(count) {
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
            }
        }

        // Global add to cart functionality
        function addToCart(productId, quantity = 1) {
            console.log('addToCart called with:', { productId, quantity });

            // Find the actual button element (handle clicks on child elements like icons)
            let addToCartBtn = event.target;
            while (addToCartBtn && !addToCartBtn.classList.contains('btn')) {
                addToCartBtn = addToCartBtn.parentElement;
            }

            if (!addToCartBtn) {
                console.error('Could not find button element');
                return;
            }

            const originalText = addToCartBtn.innerHTML;

            // Prevent multiple clicks
            if (addToCartBtn.disabled) {
                console.log('Button already disabled, ignoring click');
                return;
            }

            // Get quantity from input field if it exists (for product detail pages)
            const quantityInput = document.getElementById('quantity');
            if (quantityInput) {
                quantity = parseInt(quantityInput.value) || 1;
                console.log('Quantity from input:', quantity);
            }

            // Show loading state
            addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';
            addToCartBtn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            console.log('CSRF Token:', csrfToken);

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity,
                    request_id: Date.now() + '_' + Math.random().toString(36).substr(2, 9)
                })
            })
            .then(response => {
                console.log('Response received:', response.status, response.statusText);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text().then(text => {
                    console.log('Response text:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Response is not JSON:', text);
                        throw new Error('Server returned invalid JSON response');
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    // Update cart count
                    updateCartCount(data.cart_count);

                    // Show success message
                    showNotification(data.message, 'success');

                    // Update button text temporarily - show "View Cart" for product pages
                    if (document.getElementById('quantity')) {
                        // This is a product detail page
                        addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart me-1"></i>View Cart';
                        addToCartBtn.onclick = () => window.location.href = '/cart';

                        setTimeout(() => {
                            addToCartBtn.innerHTML = originalText;
                            addToCartBtn.onclick = () => addToCart(productId);
                            addToCartBtn.disabled = false;
                        }, 3000);
                    } else {
                        // This is a product listing page
                        addToCartBtn.innerHTML = '<i class="fas fa-check me-1"></i>Added!';
                        setTimeout(() => {
                            addToCartBtn.innerHTML = originalText;
                            addToCartBtn.disabled = false;
                        }, 2000);
                    }
                } else {
                    showNotification(data.message || 'Error adding to cart', 'error');
                    addToCartBtn.innerHTML = originalText;
                    addToCartBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error adding to cart', 'error');
                addToCartBtn.innerHTML = originalText;
                addToCartBtn.disabled = false;
            });
        }

        // Load cart count on page load
        function loadCartCount() {
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    if (data.count !== undefined) {
                        updateCartCount(data.count);
                    }
                })
                .catch(error => {
                    console.error('Error loading cart count:', error);
                });
        }

        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }

        // Dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Load cart count on page load
            loadCartCount();

            // Enhanced mega menu functionality
            const exploreProductsDropdown = document.getElementById('exploreProductsDropdown');
            const megaDropdown = document.querySelector('.mega-dropdown');
            const megaMenu = document.querySelector('.mega-dropdown .dropdown-menu');

            if (exploreProductsDropdown && megaDropdown && megaMenu) {
                let hoverTimeout;

                // Show dropdown on hover
                exploreProductsDropdown.addEventListener('mouseenter', function() {
                    clearTimeout(hoverTimeout);
                    this.setAttribute('aria-expanded', 'true');
                    megaDropdown.classList.add('show');
                    megaMenu.style.display = 'block';
                    megaMenu.style.opacity = '1';
                    megaMenu.style.visibility = 'visible';
                });

                // Keep dropdown open when hovering over the menu
                megaMenu.addEventListener('mouseenter', function() {
                    clearTimeout(hoverTimeout);
                });

                // Hide dropdown when mouse leaves the entire dropdown area
                megaDropdown.addEventListener('mouseleave', function() {
                    hoverTimeout = setTimeout(function() {
                        exploreProductsDropdown.setAttribute('aria-expanded', 'false');
                        megaDropdown.classList.remove('show');
                        megaMenu.style.display = 'none';
                        megaMenu.style.opacity = '0';
                        megaMenu.style.visibility = 'hidden';
                    }, 150); // Small delay to prevent flickering
                });

                // Click to toggle on mobile/touch devices
                exploreProductsDropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';

                    if (isExpanded) {
                        this.setAttribute('aria-expanded', 'false');
                        megaDropdown.classList.remove('show');
                        megaMenu.style.display = 'none';
                        megaMenu.style.opacity = '0';
                        megaMenu.style.visibility = 'hidden';
                    } else {
                        this.setAttribute('aria-expanded', 'true');
                        megaDropdown.classList.add('show');
                        megaMenu.style.display = 'block';
                        megaMenu.style.opacity = '1';
                        megaMenu.style.visibility = 'visible';
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!megaDropdown.contains(e.target)) {
                        exploreProductsDropdown.setAttribute('aria-expanded', 'false');
                        megaDropdown.classList.remove('show');
                        megaMenu.style.display = 'none';
                        megaMenu.style.opacity = '0';
                        megaMenu.style.visibility = 'hidden';
                    }
                });
            }

            // Mobile menu toggle
            const navbarToggle = document.getElementById('navbarToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');

            if (navbarToggle && sidebar && overlay) {
                navbarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });

                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
