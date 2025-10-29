<style>
    /* Sidebar Styles */
    .sidebar {
        width: 250px;
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        transition: all 0.3s ease;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        padding: 1.5rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    /* Responsive Sidebar Header */
    @media (max-width: 576px) {
        .sidebar-header {
            padding: 1rem 0.75rem;
        }
    }

    .sidebar-brand-icon {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 1.2rem;
    }

    .sidebar-brand-text h4 {
        color: white;
        font-weight: 600;
        margin: 0;
        font-size: 1.1rem;
    }

    .sidebar-brand-text small {
        color: rgba(255,255,255,0.8);
        font-size: 0.8rem;
    }

    /* Responsive Brand Text */
    @media (max-width: 576px) {
        .sidebar-brand-text h4 {
            font-size: 1rem;
        }

        .sidebar-brand-text small {
            font-size: 0.75rem;
        }
    }

    .sidebar-toggle {
        color: white;
        border: none;
        background: none;
        font-size: 1.2rem;
        padding: 0.5rem;
        border-radius: 4px;
        transition: background 0.3s ease;
    }

    .sidebar-toggle:hover {
        background: rgba(255,255,255,0.1);
    }

    .sidebar-body {
        padding: 1rem 0;
        height: calc(100vh - 120px);
        overflow-y: auto;
        overflow-x: hidden;
        flex: 1;
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.3) rgba(255,255,255,0.1);
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    /* Responsive Sidebar Body */
    @media (max-width: 576px) {
        .sidebar-body {
            height: calc(100vh - 100px);
            padding: 0.5rem 0;
        }
    }

    .sidebar .nav-link {
        color: rgba(255,255,255,0.8);
        padding: 0.75rem 1.5rem;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
        position: relative;
        white-space: nowrap;
        flex-shrink: 0;
    }

    /* Responsive Sidebar Links */
    @media (max-width: 576px) {
        .sidebar .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }

    .sidebar .nav-link:hover {
        color: white;
        background: rgba(255,255,255,0.1);
        transform: translateX(5px);
    }

    .sidebar .nav-link.active {
        color: white;
        background: rgba(255,255,255,0.2);
        border-right: 3px solid white;
    }

    .sidebar .nav-link i {
        width: 20px;
        margin-right: 0.75rem;
        text-align: center;
        font-size: 1.1rem;
    }

    .sidebar .nav-link span {
        font-weight: 500;
    }

    .sidebar-footer {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1rem;
        border-top: 1px solid rgba(255,255,255,0.1);
        flex-shrink: 0;
    }

    /* Responsive Sidebar Footer */
    @media (max-width: 576px) {
        .sidebar-footer {
            padding: 0.75rem;
        }
    }

    .sidebar-footer .nav-link {
        color: rgba(255,255,255,0.7);
        padding: 0.5rem 1rem;
    }

    .sidebar-footer .nav-link:hover {
        color: #ff6b6b;
        background: rgba(255,107,107,0.1);
    }

    /* Mobile Responsive */
    @media (max-width: 991.98px) {
        .sidebar {
            transform: translateX(-100%);
            width: 250px;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0 !important;
            width: 100%;
        }
    }

    /* Desktop - Sidebar always visible */
    @media (min-width: 992px) {
        .sidebar {
            transform: translateX(0);
            position: fixed;
        }

        .main-content {
            margin-left: 250px;
        }
    }

    /* Main Content Adjustment */
    .main-content {
        margin-left: 250px;
        transition: margin-left 0.3s ease;
        min-height: 100vh;
        background: #f8f9fa;
        width: calc(100% - 250px);
    }

    @media (max-width: 991.98px) {
        .main-content {
            margin-left: 0;
            width: 100%;
        }
    }

    /* Mobile Overlay */
    .mobile-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 999;
        display: none;
    }

    .mobile-overlay.show {
        display: block;
    }

    /* Top Navbar */
    .top-navbar {
        background: white;
        padding: 1rem 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 100;
        width: 100%;
    }

    /* Responsive Top Navbar */
    @media (max-width: 768px) {
        .top-navbar {
            padding: 0.75rem 1rem;
        }
    }

    @media (max-width: 576px) {
        .top-navbar {
            padding: 0.5rem 0.75rem;
        }
    }

    .navbar-toggle {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #6c757d;
        display: none;
        padding: 0.5rem;
        border-radius: 4px;
        transition: background 0.3s ease;
    }

    .navbar-toggle:hover {
        background: rgba(0,0,0,0.05);
    }

    @media (max-width: 991.98px) {
        .navbar-toggle {
            display: block;
        }
    }

    .navbar-user {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    /* Responsive User Info */
    @media (max-width: 576px) {
        .navbar-user {
            gap: 0.5rem;
        }

        .navbar-user .fw-bold {
            font-size: 0.9rem;
        }

        .navbar-user small {
            font-size: 0.75rem;
        }
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
        flex-shrink: 0;
    }

    /* Responsive User Avatar */
    @media (max-width: 576px) {
        .user-avatar {
            width: 35px;
            height: 35px;
            font-size: 0.9rem;
        }
    }

    /* Content Area */
    .content-area {
        padding: 2rem;
    }

    /* Responsive Content Area */
    @media (max-width: 768px) {
        .content-area {
            padding: 1rem;
        }
    }

    @media (max-width: 576px) {
        .content-area {
            padding: 0.5rem;
        }
    }

    /* Enhanced Scrollbar Styling */
    .sidebar-body::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-body::-webkit-scrollbar-track {
        background: rgba(255,255,255,0.1);
        border-radius: 3px;
    }

    .sidebar-body::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.4);
        border-radius: 3px;
        transition: background 0.3s ease;
    }

    .sidebar-body::-webkit-scrollbar-thumb:hover {
        background: rgba(255,255,255,0.6);
    }

    .sidebar-body::-webkit-scrollbar-thumb:active {
        background: rgba(255,255,255,0.8);
    }

    /* Firefox scrollbar styling */
    .sidebar-body {
        scrollbar-width: thin;
        scrollbar-color: rgba(255,255,255,0.4) rgba(255,255,255,0.1);
    }

    /* Scroll shadow effects */
    .sidebar-body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 20px;
        background: linear-gradient(to bottom, rgba(102, 126, 234, 0.3), transparent);
        pointer-events: none;
        z-index: 1;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .sidebar-body::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 20px;
        background: linear-gradient(to top, rgba(102, 126, 234, 0.3), transparent);
        pointer-events: none;
        z-index: 1;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    /* Show scroll shadows when content overflows */
    .sidebar-body.scroll-top::before {
        opacity: 0;
    }

    .sidebar-body.scroll-bottom::after {
        opacity: 0;
    }

    .sidebar-body.scroll-middle::before,
    .sidebar-body.scroll-middle::after {
        opacity: 1;
    }
</style>
