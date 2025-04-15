<header class="bg-white py-4 border-b border-gray-100 sticky top-0 z-50">
    <div class="container mx-auto px-4 md:px-6">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center">
                    <span class="text-2xl font-semibold tracking-tighter">Saji</span>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-sm font-medium hover:text-saji-accent transition-colors duration-200 {{ request()->routeIs('home') ? 'text-saji-accent' : 'text-saji-black' }}">
                    Home
                </a>
                <a href="{{ route('shop') }}" class="text-sm font-medium hover:text-saji-accent transition-colors duration-200 {{ request()->routeIs('shop*') ? 'text-saji-accent' : 'text-saji-black' }}">
                    Shop
                </a>
                <a href="{{ route('about') }}" class="text-sm font-medium hover:text-saji-accent transition-colors duration-200 {{ request()->routeIs('about') ? 'text-saji-accent' : 'text-saji-black' }}">
                    About
                </a>
                <a href="{{ route('contact') }}" class="text-sm font-medium hover:text-saji-accent transition-colors duration-200 {{ request()->routeIs('contact') ? 'text-saji-accent' : 'text-saji-black' }}">
                    Contact
                </a>
            </nav>
            
            <!-- Right Navigation (Cart & User) -->
            <div class="flex items-center space-x-4">
                <!-- Cart -->
                <a href="{{ route('cart') }}" class="text-saji-black hover:text-saji-accent transition-colors duration-200 relative p-1">
                    <i class="ti ti-shopping-cart text-xl"></i>
                    {{-- @if(Cart::count() > 0)
                        <span class="absolute -top-1 -right-1 bg-saji-accent text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center">
                            {{ Cart::count() }}
                        </span>
                    @endif --}}
                </a>
                
                <!-- User Account -->
                <a href="{{ route('account') }}" class="hidden md:block text-saji-black hover:text-saji-accent transition-colors duration-200 p-1">
                    <i class="ti ti-user text-xl"></i>
                </a>
                
                <!-- Mobile Menu Button -->
                <button 
                    type="button" 
                    class="md:hidden text-saji-black hover:text-saji-accent p-1"
                    onclick="toggleMobileMenu()"
                    aria-label="Toggle menu"
                >
                    <i class="ti ti-menu-2 text-2xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden py-4 mt-4 border-t border-gray-100">
            <nav class="flex flex-col space-y-4">
                <a href="{{ route('home') }}" class="text-base font-medium hover:text-saji-accent transition-colors duration-200 {{ request()->routeIs('home') ? 'text-saji-accent' : 'text-saji-black' }}">
                    Home
                </a>
                <a href="{{ route('shop') }}" class="text-base font-medium hover:text-saji-accent transition-colors duration-200 {{ request()->routeIs('shop*') ? 'text-saji-accent' : 'text-saji-black' }}">
                    Shop
                </a>
                <a href="{{ route('about') }}" class="text-base font-medium hover:text-saji-accent transition-colors duration-200 {{ request()->routeIs('about') ? 'text-saji-accent' : 'text-saji-black' }}">
                    About
                </a>
                <a href="{{ route('contact') }}" class="text-base font-medium hover:text-saji-accent transition-colors duration-200 {{ request()->routeIs('contact') ? 'text-saji-accent' : 'text-saji-black' }}">
                    Contact
                </a>
                <a href="{{ route('account') }}" class="text-base font-medium hover:text-saji-accent transition-colors duration-200">
                    My Account
                </a>
            </nav>
        </div>
    </div>
</header>