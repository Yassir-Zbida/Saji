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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    
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
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-sidebar border-r border-gray-200 transform transition-transform duration-300 z-20 lg:translate-x-0" x-data="{ sidebarOpen: true }">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center h-16 px-4 border-b border-gray-200">
                    <a href="/" class="flex items-center">
                        <i class="ri-shopping-bag-3-line text-2xl text-primary mr-2"></i>
                        <span class="text-xl font-semibold text-primary">My Store</span>
                    </a>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 py-4 overflow-y-auto">
                    <ul class="space-y-1 px-3">
                        <li>
                            <a href="/" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-200 {{ request()->routeIs('dashboard') ? 'bg-gray-200 text-primary font-medium' : '' }}">
                                <i class="ri-home-line text-lg mr-3"></i>
                                <span>Home</span>
                            </a>
                        </li>
                        <li>
                            <a href="/" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-200 {{ request()->routeIs('admin.orders*') ? 'bg-gray-200 text-primary font-medium' : '' }}">
                                <i class="ri-file-list-line text-lg mr-3"></i>
                                <span>Orders</span>
                                <span class="ml-auto bg-gray-200 text-gray-700 text-xs py-0.5 px-2 rounded-full">30</span>
                            </a>
                        </li>
                        <li>
                            <a href="/" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-200 {{ request()->routeIs('admin.products*') ? 'bg-gray-200 text-primary font-medium' : '' }}">
                                <i class="ri-shopping-bag-line text-lg mr-3"></i>
                                <span>Products</span>
                            </a>
                        </li>
                        <li>
                            <div class="px-4 py-2.5 flex items-center justify-between cursor-pointer text-gray-700 hover:bg-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="ri-folder-line text-lg mr-3"></i>
                                    <span>Collections</span>
                                </div>
                                <i class="ri-arrow-right-s-line"></i>
                            </div>
                        </li>
                        <li>
                            <div class="px-4 py-2.5 flex items-center justify-between cursor-pointer text-gray-700 hover:bg-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="ri-stack-line text-lg mr-3"></i>
                                    <span>Inventory</span>
                                </div>
                                <i class="ri-arrow-right-s-line"></i>
                            </div>
                        </li>
                        <li>
                            <a href="" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-200 {{ request()->routeIs('admin.customers*') ? 'bg-gray-200 text-primary font-medium' : '' }}">
                                <i class="ri-user-line text-lg mr-3"></i>
                                <span>Customers</span>
                            </a>
                        </li>
                        <li>
                            <a href="" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-200 {{ request()->routeIs('admin.content*') ? 'bg-gray-200 text-primary font-medium' : '' }}">
                                <i class="ri-file-text-line text-lg mr-3"></i>
                                <span>Content</span>
                            </a>
                        </li>
                        <li>
                            <a href="" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-200 {{ request()->routeIs('admin.analytics*') ? 'bg-gray-200 text-primary font-medium' : '' }}">
                                <i class="ri-bar-chart-line text-lg mr-3"></i>
                                <span>Analytics</span>
                            </a>
                        </li>
                        <li>
                            <a href="" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-200 {{ request()->routeIs('admin.marketing*') ? 'bg-gray-200 text-primary font-medium' : '' }}">
                                <i class="ri-megaphone-line text-lg mr-3"></i>
                                <span>Marketing</span>
                            </a>
                        </li>
                        <li>
                            <a href="" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-200 {{ request()->routeIs('admin.discounts*') ? 'bg-gray-200 text-primary font-medium' : '' }}">
                                <i class="ri-percent-line text-lg mr-3"></i>
                                <span>Discounts</span>
                            </a>
                        </li>
                        <li>
                            <div class="px-4 py-2.5 flex items-center justify-between cursor-pointer text-gray-700 hover:bg-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="ri-store-line text-lg mr-3"></i>
                                    <span>Sales channels</span>
                                </div>
                                <i class="ri-arrow-right-s-line"></i>
                            </div>
                        </li>
                    </ul>
                    
                    <div class="px-6 mt-6">
                        <div class="border-t border-gray-200 pt-4">
                            <ul class="space-y-1">
                                <li>
                                    <a href="" class="flex items-center px-4 py-2.5 rounded-lg text-gray-700 hover:bg-gray-200 {{ request()->routeIs('admin.settings*') ? 'bg-gray-200 text-primary font-medium' : '' }}">
                                        <i class="ri-settings-line text-lg mr-3"></i>
                                        <span>Settings</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto lg:ml-64">
            <!-- Top Navbar -->
            <header class="bg-white border-b border-gray-200 sticky top-0 z-10">
                <div class="flex items-center justify-between px-6 h-16">
                    <!-- Left: Mobile Menu Toggle & Search -->
                    <div class="flex items-center">
                        <button id="mobile-menu-button" class="text-gray-600 focus:outline-none lg:hidden mr-4">
                            <i class="ri-menu-line text-xl"></i>
                        </button>
                        
                        <div class="relative hidden md:block">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="ri-search-line text-gray-400"></i>
                            </div>
                            <input type="text" class="h-10 pl-10 pr-4 bg-gray-100 border-0 rounded-lg w-64 focus:bg-white focus:ring-1 focus:ring-gray-300 focus:outline-none" placeholder="Search">
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex items-center text-xs text-gray-500">
                                <span class="mr-1">CTRL</span>
                                <span class="border border-gray-300 rounded px-1">K</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right: User Menu -->
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-600 hover:text-gray-800 focus:outline-none">
                            <i class="ri-notification-line text-xl"></i>
                        </button>
                        <div class="relative">
                            <button class="flex items-center focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                    <span>MS</span>
                                </div>
                                <span class="ml-2 text-sm hidden md:block">My Store</span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content Area -->
            <main class="p-6">
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="border-t border-gray-200 py-4 px-6">
                <div class="text-center text-gray-500 text-sm">
                    &copy; {{ date('Y') }} My Store. All rights reserved.
                </div>
            </footer>
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
                const isClickOnMenuButton = mobileMenuButton.contains(event.target);
                
                if (!isClickInsideSidebar && !isClickOnMenuButton && window.innerWidth < 1024) {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
