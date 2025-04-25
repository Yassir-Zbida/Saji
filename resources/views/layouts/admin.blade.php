<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Italiana&family=Jost:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap"
        rel="stylesheet">

    @vite(['resources/css/admin.css', 'resources/js/admin.js'])

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#111111',
                        'primary-dark': '#000000',
                        'primary-light': '#333333',
                        secondary: '#F5F5F5',
                        accent: '#E8E8E8',
                        'sidebar': '#f3f3f3',
                        'dashboard-bg': '#F9FAFB',
                    },
                    boxShadow: {
                        'soft': '0 2px 10px rgba(0, 0, 0, 0.05)',
                        'medium': '0 4px 20px rgba(0, 0, 0, 0.08)',
                    },
                    fontFamily: {
                        'jost': ['Jost', 'sans-serif'],
                        'jakarta': ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Hide default scrollbars */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: rgba(0, 0, 0, 0.2);
        }

        /* Transition styles for sidebar */
        .sidebar-transition {
            transition: width 0.3s ease, transform 0.3s ease;
        }

        /* Hide text when sidebar is collapsed */
        .sidebar-collapsed .nav-text {
            display: none;
        }

        /* Center icons when collapsed */
        .sidebar-collapsed .nav-item {
            justify-content: center;
        }

        /* Tooltip styles */
        .tooltip {
            position: relative;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            background-color: #111111;
            color: #fff;
            text-align: center;
            border-radius: 8px;
            padding: 6px 12px;
            position: absolute;
            z-index: 100;
            left: 100%;
            margin-left: 10px;
            opacity: 0;
            transition: opacity 0.3s;
            white-space: nowrap;
            font-size: 12px;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-collapsed .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* Active nav item indicator */
        .nav-item.active {
            background-color: #F5F5F5;
            color: #111111;
            font-weight: 500;
            border-left: 3px solid #111111;
        }

        .nav-item.active i {
            color: #111111;
        }

        /* Improve scrollable areas */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.1) transparent;
        }

        /* Badge styles */
        .nav-badge {
            font-size: 0.65rem;
            padding: 0.125rem 0.375rem;
            border-radius: 9999px;
            font-weight: 500;
        }

        /* Prevent layout shifts on sidebar toggle */
        .content-wrapper {
            transition: margin-left 0.3s ease;
        }

        /* Toggle button styling */
        .sidebar-toggle {
            transition: all 0.3s ease;
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-dashboard-bg font-jakarta">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarCollapsed: false }">
        <!-- Sidebar -->
        <div id="sidebar"
            class="fixed inset-y-0 left-0 bg-white border-r border-gray-200 transform sidebar-transition z-20 lg:translate-x-0 shadow-soft rounded-tr-xl rounded-br-xl overflow-hidden"
            :class="sidebarCollapsed ? 'w-16 sidebar-collapsed' : 'w-64'" x-data="{ sidebarOpen: true }">
            <div class="flex flex-col h-full">
                <!-- Logo with Toggle Button -->
                <div class="flex items-center justify-between h-16 px-4 border-b border-gray-100">
                    <a href="/" class="flex items-center">
                        <span class="text-xl font-semibold text-primary">Saji Home</span>
                    </a>
                    <button @click="sidebarCollapsed = !sidebarCollapsed"
                        class="sidebar-toggle focus:outline-none flex items-center justify-center p-1 hover:bg-gray-100 rounded-lg">
                        <i class="ri-sidebar-fold-line text-gray-600 text-lg" x-show="!sidebarCollapsed"></i>
                        <i class="ri-sidebar-unfold-line text-gray-600 text-lg" x-show="sidebarCollapsed"></i>
                    </button>


                </div>

                <!-- Navigation -->
                <nav class="flex-1 pt-4 overflow-y-auto overflow-x-hidden custom-scrollbar">
                    <ul class="space-y-1 px-3">
                        <li>
                            <a href="/"
                                class="tooltip flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 nav-item transition-colors active">
                                <i class="ri-dashboard-line text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                                <span class="nav-text">Dashboard</span>
                                <span class="tooltip-text" x-show="sidebarCollapsed">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="admin/orders"
                                class="tooltip flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 nav-item transition-colors {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                                <i class="ri-file-list-line text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                                <span class="nav-text">Orders</span>
                                <span class="ml-auto bg-gray-100 text-gray-700 nav-badge nav-text">30</span>
                                <span class="tooltip-text" x-show="sidebarCollapsed">Orders (30)</span>
                            </a>
                        </li>
                        <li>
                            <a href="/"
                                class="tooltip flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 nav-item transition-colors {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                                <i class="ri-shopping-bag-line text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                                <span class="nav-text">Products</span>
                                <span class="tooltip-text" x-show="sidebarCollapsed">Products</span>
                            </a>
                        </li>
                        <li x-data="{ open: false }">
                            <div class="tooltip px-4 py-2.5 flex items-center justify-between cursor-pointer text-gray-700 hover:bg-gray-100 rounded-lg nav-item transition-colors"
                                @click="open = !open">
                                <div class="flex items-center">
                                    <i class="ri-folder-line text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                                    <span class="nav-text">Collections</span>
                                </div>
                                <i class="ri-arrow-down-s-line transition-transform nav-text"
                                    :class="open ? 'transform rotate-180' : ''"></i>
                                <span class="tooltip-text" x-show="sidebarCollapsed">Collections</span>
                            </div>
                            <div x-show="open && !sidebarCollapsed" class="pl-10 pr-4 mt-1 space-y-1"
                                style="display: none;">
                                <a href="#"
                                    class="block py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-100 text-sm transition-colors">Categories</a>
                                <a href="#"
                                    class="block py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-100 text-sm transition-colors">Product
                                    Tags</a>
                            </div>
                        </li>
                        <li x-data="{ open: false }">
                            <div class="tooltip px-4 py-2.5 flex items-center justify-between cursor-pointer text-gray-700 hover:bg-gray-100 rounded-lg nav-item transition-colors"
                                @click="open = !open">
                                <div class="flex items-center">
                                    <i class="ri-stack-line text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                                    <span class="nav-text">Inventory</span>
                                </div>
                                <i class="ri-arrow-down-s-line transition-transform nav-text"
                                    :class="open ? 'transform rotate-180' : ''"></i>
                                <span class="tooltip-text" x-show="sidebarCollapsed">Inventory</span>
                            </div>
                            <div x-show="open && !sidebarCollapsed" class="pl-10 pr-4 mt-1 space-y-1"
                                style="display: none;">
                                <a href="#"
                                    class="block py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-100 text-sm transition-colors">Stock</a>
                                <a href="#"
                                    class="block py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-100 text-sm transition-colors">Suppliers</a>
                            </div>
                        </li>
                        <li>
                            <a href=""
                                class="tooltip flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 nav-item transition-colors {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                                <i class="ri-user-line text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                                <span class="nav-text">Customers</span>
                                <span class="tooltip-text" x-show="sidebarCollapsed">Customers</span>
                            </a>
                        </li>
                        <li>
                            <a href=""
                                class="tooltip flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 nav-item transition-colors {{ request()->routeIs('admin.content*') ? 'active' : '' }}">
                                <i class="ri-file-text-line text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                                <span class="nav-text">Content</span>
                                <span class="tooltip-text" x-show="sidebarCollapsed">Content</span>
                            </a>
                        </li>
                        <li>
                            <a href=""
                                class="tooltip flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 nav-item transition-colors {{ request()->routeIs('admin.analytics*') ? 'active' : '' }}">
                                <i class="ri-bar-chart-line text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                                <span class="nav-text">Analytics</span>
                                <span class="tooltip-text" x-show="sidebarCollapsed">Analytics</span>
                            </a>
                        </li>
                        <li>
                            <a href=""
                                class="tooltip flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 nav-item transition-colors {{ request()->routeIs('admin.marketing*') ? 'active' : '' }}">
                                <i class="ri-megaphone-line text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                                <span class="nav-text">Marketing</span>
                                <span class="tooltip-text" x-show="sidebarCollapsed">Marketing</span>
                            </a>
                        </li>
                        <li>
                            <a href=""
                                class="tooltip flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 nav-item transition-colors {{ request()->routeIs('admin.discounts*') ? 'active' : '' }}">
                                <i class="ri-percent-line text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                                <span class="nav-text">Discounts</span>
                                <span class="tooltip-text" x-show="sidebarCollapsed">Discounts</span>
                            </a>
                        </li>
                        <li x-data="{ open: false }">
                            <div class="tooltip px-4 py-2.5 flex items-center justify-between cursor-pointer text-gray-700 hover:bg-gray-100 rounded-lg nav-item transition-colors"
                                @click="open = !open">
                                <div class="flex items-center">
                                    <i class="ri-store-line text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                                    <span class="nav-text">Sales channels</span>
                                </div>
                                <i class="ri-arrow-down-s-line transition-transform nav-text"
                                    :class="open ? 'transform rotate-180' : ''"></i>
                                <span class="tooltip-text" x-show="sidebarCollapsed">Sales channels</span>
                            </div>
                            <div x-show="open && !sidebarCollapsed" class="pl-10 pr-4 mt-1 space-y-1"
                                style="display: none;">
                                <a href="#"
                                    class="block py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-100 text-sm transition-colors">Online
                                    Store</a>
                                <a href="#"
                                    class="block py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-100 text-sm transition-colors">Marketplaces</a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <!-- Admin Profile & Settings at bottom -->
                <div class="mt-auto border-t border-gray-100 py-3 px-3">
                    <a href=""
                        class="tooltip flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 nav-item transition-colors {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                        <i class="ri-settings-line text-lg" :class="sidebarCollapsed ? '' : 'mr-3'"></i>
                        <span class="nav-text">Settings</span>
                        <span class="tooltip-text" x-show="sidebarCollapsed">Settings</span>
                    </a>

                    <div class="tooltip flex items-center px-4 py-3 mt-2 rounded-lg bg-gray-50 nav-item">
                        <div
                            class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center flex-shrink-0">
                            <span>A</span>
                        </div>
                        <div class="ml-3 nav-text">
                            <p class="text-sm font-medium text-gray-800">Admin</p>
                            <p class="text-xs text-gray-500">admin@saji.com</p>
                        </div>
                        <span class="tooltip-text" x-show="sidebarCollapsed">Admin Profile</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden content-wrapper transition-all duration-300"
            :class="sidebarCollapsed ? 'lg:ml-16' : 'lg:ml-64'">

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-dashboard-bg p-6 custom-scrollbar">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileMenuButton = document.getElementById('mobile-menu-button');

            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                    sidebar.classList.toggle('translate-x-0');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnMenuButton = mobileMenuButton ? mobileMenuButton.contains(event.target) :
                    false;

                if (!isClickInsideSidebar && !isClickOnMenuButton && window.innerWidth < 1024) {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                }
            });
        });
    </script>
</body>

</html>
