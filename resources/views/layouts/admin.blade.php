<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Nutrition Admin</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Laravel File Manager CSS -->
    <link href="/vendor/laravel-filemanager/css/lfm.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        /* Image Preview Responsive Styles */
        .image-preview-container {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .image-preview-container:hover {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }

        .thumbnail-wrapper:hover .thumbnail-overlay {
            opacity: 1 !important;
            transition: opacity 0.3s ease;
        }

        .thumbnail-overlay {
            transition: opacity 0.3s ease;
        }

        .action-buttons .btn {
            min-width: 120px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .image-preview-container {
                padding: 0.75rem;
            }

            .action-buttons .btn {
                min-width: 100px;
                font-size: 0.875rem;
            }

            .thumbnail-wrapper img {
                height: 150px !important;
            }
        }

        @media (max-width: 576px) {
            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .thumbnail-wrapper img {
                height: 120px !important;
            }
        }
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h4 {
            color: white;
            margin: 0;
            font-weight: 600;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }

        .main-content {
            flex: 1;
            margin-left: 250px;
            min-height: 100vh;
            background: #f8f9fa;
        }

        .top-navbar {
            background: white;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-area {
            padding: 2rem;
        }

        .navbar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6c757d;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .navbar-toggle {
                display: block;
            }

            .mobile-overlay.show {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobile-overlay"></div>

    <div class="admin-layout">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h4><i class="fas fa-store me-2"></i>Nutrition Admin</h4>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                        <i class="fas fa-box"></i>
                        <span>Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                        <i class="fas fa-tags"></i>
                        <span>Categories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Orders</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.shipping.*') ? 'active' : '' }}" href="{{ route('admin.shipping.index') }}">
                        <i class="fas fa-shipping-fast"></i>
                        <span>Shipping</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Coupons</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-user-cog"></i>
                        <span>User Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                        <i class="fas fa-user-shield"></i>
                        <span>Roles</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}" href="{{ route('admin.permissions.index') }}">
                        <i class="fas fa-key"></i>
                        <span>Permissions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.file-manager') ? 'active' : '' }}" href="{{ route('admin.file-manager') }}">
                        <i class="fas fa-folder-open"></i>
                        <span>File Manager</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}" href="{{ route('admin.inventory.index') }}">
                        <i class="fas fa-boxes"></i>
                        <span>Inventory Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.contact-leads.*') ? 'active' : '' }}" href="{{ route('admin.contact-leads.index') }}">
                        <i class="fas fa-address-book"></i>
                        <span>Contact Leads</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'active' : '' }}" href="{{ route('admin.activity-logs.index') }}">
                        <i class="fas fa-history"></i>
                        <span>Activity Logs</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <div class="top-navbar">
                <button class="navbar-toggle d-lg-none" type="button" id="navbarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="navbar-user">
                    <div class="user-avatar">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <div class="fw-bold">{{ auth()->user()->name ?? 'Admin' }}</div>
                        <small class="text-muted">Administrator</small>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (required for Laravel File Manager) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Laravel File Manager Variables -->
    <script>
        // Set the correct base URL for Laravel File Manager
        var lfm_route = '/laravel-filemanager';

        var lang = {
            'nav-upload': 'Upload',
            'nav-new': 'New Folder',
            'nav-back': 'Back',
            'nav-edit': 'Edit',
            'nav-delete': 'Delete',
            'nav-copy': 'Copy',
            'nav-move': 'Move',
            'nav-download': 'Download',
            'nav-view': 'View',
            'nav-select': 'Select',
            'nav-confirm': 'Confirm',
            'nav-cancel': 'Cancel'
        };

        var actions = [
            {name: 'upload', multiple: false, icon: 'upload', label: 'Upload'},
            {name: 'new-folder', multiple: false, icon: 'folder-plus', label: 'New Folder'},
            {name: 'delete', multiple: true, icon: 'trash', label: 'Delete'},
            {name: 'copy', multiple: true, icon: 'copy', label: 'Copy'},
            {name: 'move', multiple: true, icon: 'cut', label: 'Move'},
            {name: 'download', multiple: true, icon: 'download', label: 'Download'},
            {name: 'view', multiple: false, icon: 'eye', label: 'View'},
            {name: 'select', multiple: false, icon: 'check', label: 'Select'}
        ];

        var sortings = [
            {by: 'alphabetic', icon: 'sort-alpha-down', label: 'Alphabetic'},
            {by: 'time', icon: 'sort-numeric-down', label: 'Time'},
            {by: 'size', icon: 'sort-amount-down', label: 'Size'}
        ];
    </script>

    <!-- Laravel File Manager Dependencies -->
    <script src="/vendor/laravel-filemanager/js/dropzone.min.js"></script>
    <script src="/vendor/laravel-filemanager/js/cropper.min.js"></script>
    <!-- Removed stand-alone-button.js to prevent popup window -->
    <script src="/vendor/laravel-filemanager/js/script.js"></script>

    <!-- Override the base URL after script loads -->
    <script>
        // Override the lfm_route that was set by script.js
        lfm_route = '/laravel-filemanager';
        console.log('LFM route set to:', lfm_route);

        // Define helper functions for image actions
        window.viewFullImage = function(url) {
            console.log('Opening full image:', url);

            // Try multiple methods to open the image
            try {
                // Method 1: Try window.open
                const newWindow = window.open(url, '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');

                // Check if popup was blocked
                if (!newWindow || newWindow.closed || typeof newWindow.closed == 'undefined') {
                    console.log('Popup blocked, trying alternative method');

                    // Method 2: Create a temporary link and click it
                    const link = document.createElement('a');
                    link.href = url;
                    link.target = '_blank';
                    link.rel = 'noopener noreferrer';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    console.log('Opened image via temporary link');
                } else {
                    console.log('Opened image in popup window');
                }
            } catch (error) {
                console.error('Error opening image:', error);

                // Method 3: Fallback - show URL in alert
                alert('Image URL: ' + url + '\n\nCopy this URL and paste it in your browser to view the image.');
            }
        };

        window.copyImageUrl = function(url) {
            console.log('Copying image URL:', url);
            navigator.clipboard.writeText(url).then(() => {
                alert('Image URL copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy URL:', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('Image URL copied to clipboard!');
            });
        };

        window.clearImagePreview = function(inputId, previewId) {
            console.log('Clearing image preview for:', inputId);
            document.getElementById(inputId).value = '';
            document.getElementById(previewId).innerHTML = '';
        };

        // Define the SetUrl function that Laravel File Manager expects
        window.SetUrl = function(items) {
            console.log('SetUrl called with items:', items);
            if (items && items.length > 0) {
                const file_path = items.map(function(item) {
                    return item.url;
                }).join(',');

                // Find the target input and preview
                const target_input = document.querySelector('input[name="featured_image"]');
                const target_preview = document.querySelector('#image-preview-' + target_input.id.replace('image-picker-', ''));

                if (target_input) {
                    target_input.value = file_path;
                    console.log('Set input value to:', file_path);
                }

                if (target_preview) {
                    // Update preview
                    target_preview.innerHTML = `
                        <div class="image-preview-container d-flex align-items-start gap-3 p-3 border rounded bg-light">
                            <div class="thumbnail-wrapper position-relative">
                                <img src="${file_path}"
                                     alt="Preview"
                                     class="img-thumbnail shadow-sm"
                                     style="width: 120px; height: 120px; object-fit: cover;">
                            </div>
                            <div class="image-info flex-grow-1">
                                <h6 class="mb-2 text-dark">
                                    <i class="fas fa-image me-1 text-primary"></i>
                                    ${file_path.split('/').pop()}
                                </h6>
                            </div>
                        </div>
                    `;
                    console.log('Updated preview');
                }
            }
        };

        // Prevent auto-initialization of the file manager
        $(document).ready(function() {
            // Add working_dir input if it doesn't exist
            if ($('#working_dir').length === 0) {
                $('body').append('<input type="hidden" id="working_dir" value="/1/products">');
            }

            // Add other missing elements but hide them
            if ($('#tree').length === 0) {
                $('body').append('<div id="tree" style="display: none;"></div>');
            }
            if ($('#nav-buttons').length === 0) {
                $('body').append('<div id="nav-buttons" style="display: none;"><ul></ul></div>');
            }
            if ($('#fab').length === 0) {
                $('body').append('<div id="fab" style="display: none;"></div>');
            }

            // Define the filemanager function to use modal instead of popup
            console.log('Defining filemanager function for modal...');

            // Define the filemanager function
            $.fn.filemanager = function(type, options) {
                    type = type || 'file';

                    this.on('click', function(e) {
                        e.preventDefault();
                        const $btn = $(this);
                        const route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';
                        const target_input = $('#' + $btn.data('input'));
                        const target_preview = $('#' + $btn.data('preview'));

                        console.log('Opening file manager modal for type:', type);
                        console.log('Target input:', target_input.attr('id'));
                        console.log('Target preview:', target_preview.attr('id'));

                        // Create modal if it doesn't exist
                        if ($('#fileManagerModal').length === 0) {
                            $('body').append(`
                                <div class="modal fade" id="fileManagerModal" tabindex="-1" aria-labelledby="fileManagerModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="fileManagerModalLabel">Choose File</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-0">
                                                <iframe src="${route_prefix}?type=${type}&working_dir=/1/products&CKEditor=image-picker&CKEditorFuncNum=1&langCode=en"
                                                        width="100%"
                                                        height="600"
                                                        frameborder="0"
                                                        id="fileManagerIframe">
                                                </iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                        }

                        // Update the SetUrl function for this specific button
                        window.SetUrl = function(items) {
                            console.log('SetUrl called with items:', items);
                            if (items && items.length > 0) {
                                const file_path = items.map(function(item) {
                                    return item.url;
                                }).join(',');

                                console.log('Setting input value to:', file_path);
                                target_input.val(file_path).trigger('change');

                                // Update preview
                                target_preview.html('');
                                items.forEach(function(item) {
                                    target_preview.append(
                                        $('<img>').css('height', '5rem').attr('src', item.thumb_url || item.url)
                                    );
                                });

                                // Close modal
                                $('#fileManagerModal').modal('hide');
                            }
                        };

                        // Check if this is a multiple image picker
                        const isMultiplePicker = $btn.hasClass('lfm-multiple-btn');

                        if (isMultiplePicker) {
                            // Special handling for multiple image picker
                            window.CKEDITOR = window.CKEDITOR || {};
                            window.CKEDITOR.tools = window.CKEDITOR.tools || {};
                            window.CKEDITOR.tools.callFunction = function(funcNum, url) {
                                console.log('=== Multiple Image Picker Callback ===');
                                console.log('Function Number:', funcNum);
                                console.log('Original URL:', url);

                                if (url) {
                                    // Store only the relative path in the database, not the full URL
                                    let relativePath = url;
                                    console.log('Using relative path from LFM for multiple picker:', relativePath);

                                    // Convert to relative path if it's a full URL
                                    if (relativePath.startsWith('http://') || relativePath.startsWith('https://')) {
                                        // Extract the path after the domain
                                        const urlObj = new URL(relativePath);
                                        relativePath = urlObj.pathname;
                                        console.log('Converted full URL to relative path for multiple picker:', relativePath);
                                    }

                                    console.log('Final relative path for multiple picker:', relativePath);

                                    // Get current images
                                    const currentImages = target_input.val() ? target_input.val().split(',').map(img => img.trim()).filter(img => img) : [];

                                    // Add new image if not already exists
                                    if (!currentImages.includes(relativePath)) {
                                        currentImages.push(relativePath);
                                        target_input.val(currentImages.join(','));
                                        console.log('Added relative path to multiple selection:', relativePath);

                                        // Re-render preview
                                        renderMultipleImagePreview(target_preview.attr('id'), currentImages);
                                    } else {
                                        console.log('Image already exists in selection');
                                    }

                                    // Close modal
                                    $('#fileManagerModal').modal('hide');
                                }
                                console.log('=== END Multiple Image Picker Callback ===');
                            };
                        } else {
                            // Regular single image picker callback
                            window.CKEDITOR = window.CKEDITOR || {};
                            window.CKEDITOR.tools = window.CKEDITOR.tools || {};
                            window.CKEDITOR.tools.callFunction = function(funcNum, url) {
                            console.log('=== CKEditor callFunction DEBUG ===');
                            console.log('Function Number:', funcNum);
                            console.log('Original URL:', url);
                            console.log('URL Type:', typeof url);
                            console.log('URL Length:', url ? url.length : 'null');

                                if (url) {
                                    // Store only the relative path in the database, not the full URL
                                    let relativePath = url;
                                    console.log('Using relative path from LFM:', relativePath);

                                    // Convert to relative path if it's a full URL
                                    if (relativePath.startsWith('http://') || relativePath.startsWith('https://')) {
                                        // Extract the path after the domain
                                        const urlObj = new URL(relativePath);
                                        relativePath = urlObj.pathname;
                                        console.log('Converted full URL to relative path:', relativePath);
                                    }

                                    console.log('Final relative path to store:', relativePath);
                                    console.log('Target input ID:', target_input.attr('id'));
                                    console.log('Target preview ID:', target_preview.attr('id'));

                                    // Store relative path in input field
                                    target_input.val(relativePath).trigger('change');

                                    // For display, convert to full URL
                                    const displayUrl = window.location.origin + relativePath;
                                    console.log('Display URL for preview:', displayUrl);

                                // Update preview with responsive design and proper image display
                                target_preview.html(`
                                    <div class="image-preview-container">
                                        <div class="row g-3">
                                            <div class="col-md-4 col-sm-12">
                                                <div class="thumbnail-wrapper position-relative">
                                                    <img src="${displayUrl}"
                                                         alt="Preview"
                                                         class="img-thumbnail shadow-sm w-100"
                                                         style="height: 200px; object-fit: cover;"
                                                         onload="console.log('Image loaded successfully:', this.src)"
                                                         onerror="console.log('Image failed to load:', this.src); this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjVGNUY1Ii8+CjxwYXRoIGQ9Ik02MCA2MEgxNDBWMTAwSDYwVjYwWiIgZmlsbD0iI0Q5RDlEOSIvPgo8cGF0aCBkPSJNNzAgNzBMMTAwIDEwMEwxMzAgNzBMMTQwIDgwVjEyMEg2MFY4MEw3MCA3MFoiIGZpbGw9IiNCOUI5QjkiLz4KPHRleHQgeD0iMTAwIiB5PSIxNTAiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiM5OTkiIGZvbnQtc2l6ZT0iMTQiPkltYWdlIEVycm9yPC90ZXh0Pgo8L3N2Zz4=';">
                                                    <div class="thumbnail-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center opacity-0 bg-dark bg-opacity-50 rounded">
                                                        <button type="button" class="btn btn-light btn-sm" onclick="viewFullImage('${displayUrl}')">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-sm-12">
                                                <div class="image-info">
                                                    <h6 class="mb-3 text-dark">
                                                        <i class="fas fa-image me-2 text-primary"></i>
                                                        ${displayUrl.split('/').pop()}
                                                    </h6>
                                                    <div class="mb-3">
                                                        <small class="text-muted d-block mb-1">
                                                            <i class="fas fa-link me-2"></i>
                                                            <strong>Stored Path:</strong> ${relativePath}
                                                        </small>
                                                        <small class="text-muted d-block mb-1">
                                                            <i class="fas fa-globe me-2"></i>
                                                            <strong>Display URL:</strong> ${displayUrl}
                                                        </small>
                                                    </div>
                                                    <div class="action-buttons d-flex flex-wrap gap-2">
                                                        <button type="button"
                                                                class="btn btn-outline-primary btn-sm"
                                                                onclick="viewFullImage('${displayUrl}')">
                                                            <i class="fas fa-eye me-1"></i>
                                                            View Full Size
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-outline-secondary btn-sm"
                                                                onclick="copyImageUrl('${displayUrl}')">
                                                            <i class="fas fa-copy me-1"></i>
                                                            Copy URL
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-outline-danger btn-sm"
                                                                onclick="clearImagePreview('${target_input.attr('id')}', '${target_preview.attr('id')}')">
                                                            <i class="fas fa-trash me-1"></i>
                                                            Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `);

                                console.log('Preview updated with display URL:', displayUrl);

                                // Close modal
                                $('#fileManagerModal').modal('hide');
                                console.log('Modal closed');
                            } else {
                                console.log('No URL provided to callback');
                            }
                            console.log('=== END CKEditor callFunction DEBUG ===');
                        };
                        }

                        // Show modal
                        $('#fileManagerModal').modal('show');
                        return false;
                    });
                };

                // Initialize LFM buttons
                $('.lfm-btn').each(function() {
                    const $btn = $(this);
                    const type = $btn.data('type');
                    console.log('Initializing LFM button for type:', type);
                    $btn.filemanager(type, {
                        prefix: '/laravel-filemanager'
                    });
                });

                // Initialize LFM multiple buttons
                $('.lfm-multiple-btn').each(function() {
                    const $btn = $(this);
                    const type = $btn.data('type');
                    console.log('Initializing LFM multiple button for type:', type);
                    $btn.filemanager(type, {
                        prefix: '/laravel-filemanager'
                    });
                });

                console.log('LFM buttons initialized successfully with modal');
        });
    </script>

    <script>
        // Mobile sidebar toggle
        document.getElementById('navbarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');

            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });

        // Close sidebar when clicking overlay
        document.getElementById('mobile-overlay')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');

            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    </script>
</body>
</html>
