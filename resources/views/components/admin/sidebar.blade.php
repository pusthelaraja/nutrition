<!-- Admin Sidebar Styles -->
<x-admin.sidebar-styles />

<!-- Admin Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="d-flex align-items-center">
            <div class="sidebar-brand-icon">
                <i class="fas fa-store"></i>
            </div>
            <div class="sidebar-brand-text">
                <h4 class="mb-0">Nutrition</h4>
                <small class="text-muted">Admin Panel</small>
            </div>
        </div>
        <button class="btn btn-link sidebar-toggle d-lg-none" type="button" id="sidebarToggle">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="sidebar-body">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            @can('view-dashboard')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            @endcan

            <!-- Products -->
            @canany(['view-products', 'create-products', 'edit-products', 'delete-products'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
            </li>
            @endcanany

            <!-- Categories -->
            @canany(['view-categories', 'create-categories', 'edit-categories', 'delete-categories'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-tags"></i>
                    <span>Categories</span>
                </a>
            </li>
            @endcanany

            <!-- Orders -->
            @canany(['view-orders', 'create-orders', 'edit-orders', 'delete-orders'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
            </li>
            @endcanany

            <!-- Shipping -->
            @canany(['view-shipping', 'create-shipping', 'edit-shipping', 'delete-shipping'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.shipping.*') ? 'active' : '' }}" href="{{ route('admin.shipping.index') }}">
                    <i class="fas fa-shipping-fast"></i>
                    <span>Shipping</span>
                </a>
            </li>
            @endcanany

            <!-- Coupons -->
            @canany(['view-coupons', 'create-coupons', 'edit-coupons', 'delete-coupons'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
                    <i class="fas fa-ticket-alt"></i>
                    <span>Coupons</span>
                </a>
            </li>
            @endcanany

            <!-- Customers -->
            @canany(['view-customers', 'create-customers', 'edit-customers', 'delete-customers'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="#">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
            </li>
            @endcanany

            <!-- User Management -->
            @canany(['view-users', 'create-users', 'edit-users', 'delete-users'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-user-cog"></i>
                    <span>User Management</span>
                </a>
            </li>
            @endcanany

            <!-- Roles -->
            @canany(['view-roles', 'create-roles', 'edit-roles', 'delete-roles'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                    <i class="fas fa-user-shield"></i>
                    <span>Roles</span>
                </a>
            </li>
            @endcanany

            <!-- Permissions -->
            @canany(['view-permissions', 'create-permissions', 'edit-permissions', 'delete-permissions'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}" href="{{ route('admin.permissions.index') }}">
                    <i class="fas fa-key"></i>
                    <span>Permissions</span>
                </a>
            </li>
            @endcanany

            <!-- File Manager -->
            @can('view-file-manager')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.file-manager') ? 'active' : '' }}" href="{{ route('admin.file-manager') }}">
                    <i class="fas fa-folder-open"></i>
                    <span>File Manager</span>
                </a>
            </li>
            @endcan

            <!-- Inventory Management -->
            @canany(['view-inventory', 'manage-inventory'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}" href="{{ route('admin.inventory.index') }}">
                    <i class="fas fa-boxes"></i>
                    <span>Inventory Management</span>
                </a>
            </li>
            @endcanany

            <!-- Activity Logs -->
            @can('view-activity-logs')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}" href="{{ route('admin.activity-logs.index') }}">
                    <i class="fas fa-history"></i>
                    <span>Activity Logs</span>
                </a>
            </li>
            @endcan
        </ul>

        <!-- Logout -->
        <div class="sidebar-footer">
            <ul class="nav flex-column">
                <li class="nav-item mt-3">
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
    @csrf
</form>
