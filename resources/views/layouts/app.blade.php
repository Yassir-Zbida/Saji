<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - MINIMAL HOME</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>0
    <!-- Styles -->
    <style>
        :root {
            --black: #000000;
            --white: #ffffff;
            --gray-100: #f7f7f7;
            --gray-200: #e9e9e9;
            --gray-300: #d9d9d9;
            --gray-400: #a9a9a9;
            --gray-500: #808080;
            --gray-600: #666666;
            --gray-700: #444444;
            --gray-800: #333333;
            --gray-900: #1a1a1a;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--gray-800);
            background-color: var(--white);
            line-height: 1.6;
        }
        
        a {
            color: var(--gray-800);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        a:hover {
            color: var(--black);
        }
        
        .container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .header {
            padding: 1.5rem 0;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        .nav {
            display: flex;
            gap: 2rem;
        }
        
        .nav-link {
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .header-actions {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: 1px solid var(--gray-800);
            background: transparent;
            color: var(--gray-800);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background: var(--gray-800);
            color: var(--white);
        }
        
        .btn-primary {
            background: var(--gray-800);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background: var(--black);
        }
        
        .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            cursor: pointer;
            color: var(--gray-700);
            transition: color 0.3s ease;
        }
        
        .icon-btn:hover {
            color: var(--black);
        }
        
        .footer {
            padding: 4rem 0;
            background: var(--gray-100);
            margin-top: 4rem;
        }
        
        .footer-inner {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }
        
        @media (max-width: 768px) {
            .footer-inner {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .footer-inner {
                grid-template-columns: 1fr;
            }
        }
        
        .footer-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-link {
            margin-bottom: 0.5rem;
        }
        
        .footer-link a {
            font-size: 0.875rem;
            color: var(--gray-600);
        }
        
        .footer-link a:hover {
            color: var(--gray-800);
        }
        
        .footer-bottom {
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--gray-300);
            text-align: center;
            font-size: 0.875rem;
            color: var(--gray-500);
        }
        
        .mobile-menu-btn {
            display: none;
        }
        
        @media (max-width: 768px) {
            .nav {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .mobile-menu {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: var(--white);
                z-index: 100;
                padding: 2rem;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .mobile-menu.active {
                transform: translateX(0);
            }
            
            .mobile-menu-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 2rem;
            }
            
            .mobile-menu-links {
                list-style: none;
            }
            
            .mobile-menu-link {
                margin-bottom: 1rem;
            }
            
            .mobile-menu-link a {
                font-size: 1.25rem;
                font-weight: 500;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-inner">
                <a href="{{ route('home') }}" class="logo">MINIMAL HOME</a>
                
                <nav class="nav">
                    <a href="{{ route('home') }}" class="nav-link">Home</a>
                    <a href="{{ route('shop') }}" class="nav-link">Shop</a>
                    <a href="{{ route('categories') }}" class="nav-link">Categories</a>
                    <a href="{{ route('about') }}" class="nav-link">About</a>
                    <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                </nav>
                
                <div class="header-actions">
                    <button class="icon-btn" aria-label="Search">
                        <i class="ri-search-line ri-lg"></i>
                    </button>
                    
                    <a href="{{ route('account') }}" class="icon-btn" aria-label="Account">
                        <i class="ri-user-line ri-lg"></i>
                    </a>
                    
                    <a href="{{ route('wishlist') }}" class="icon-btn" aria-label="Wishlist">
                        <i class="ri-heart-line ri-lg"></i>
                    </a>
                    
                    <a href="{{ route('cart') }}" class="icon-btn" aria-label="Cart">
                        <i class="ri-shopping-bag-line ri-lg"></i>
                        @if(isset($cartCount) && $cartCount > 0)
                            <span class="cart-count">{{ $cartCount }}</span>
                        @endif
                    </a>
                    
                    <button class="mobile-menu-btn icon-btn" aria-label="Menu">
                        <i class="ri-menu-line ri-lg"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>
    
    <div class="mobile-menu">
        <div class="mobile-menu-header">
            <a href="{{ route('home') }}" class="logo">MINIMAL HOME</a>
            <button class="mobile-menu-close icon-btn" aria-label="Close menu">
                <i class="ri-close-line ri-lg"></i>
            </button>
        </div>
        
        <ul class="mobile-menu-links">
            <li class="mobile-menu-link"><a href="{{ route('home') }}">Home</a></li>
            <li class="mobile-menu-link"><a href="{{ route('shop') }}">Shop</a></li>
            <li class="mobile-menu-link"><a href="{{ route('categories') }}">Categories</a></li>
            <li class="mobile-menu-link"><a href="{{ route('about') }}">About</a></li>
            <li class="mobile-menu-link"><a href="{{ route('contact') }}">Contact</a></li>
            <li class="mobile-menu-link"><a href="{{ route('account') }}">Account</a></li>
            <li class="mobile-menu-link"><a href="{{ route('wishlist') }}">Wishlist</a></li>
            <li class="mobile-menu-link"><a href="{{ route('cart') }}">Cart</a></li>
        </ul>
    </div>
    
    <main>
        @yield('content')
    </main>
    
    <footer class="footer">
        <div class="container">
            <div class="footer-inner">
                <div class="footer-column">
                    <h3 class="footer-title">MINIMAL HOME</h3>
                    <p>Curated furniture and home decor for the modern minimalist.</p>
                    <div class="footer-social" style="margin-top: 1rem;">
                        <a href="#" class="icon-btn" aria-label="Instagram">
                            <i class="ri-instagram-line ri-lg"></i>
                        </a>
                        <a href="#" class="icon-btn" aria-label="Pinterest">
                            <i class="ri-pinterest-line ri-lg"></i>
                        </a>
                        <a href="#" class="icon-btn" aria-label="Facebook">
                            <i class="ri-facebook-line ri-lg"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-column">
                    <h3 class="footer-title">Shop</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="#">New Arrivals</a></li>
                        <li class="footer-link"><a href="#">Bestsellers</a></li>
                        <li class="footer-link"><a href="#">Living Room</a></li>
                        <li class="footer-link"><a href="#">Bedroom</a></li>
                        <li class="footer-link"><a href="#">Dining</a></li>
                        <li class="footer-link"><a href="#">Office</a></li>
                        <li class="footer-link"><a href="#">Decor</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3 class="footer-title">Help</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="#">Customer Service</a></li>
                        <li class="footer-link"><a href="#">Track Order</a></li>
                        <li class="footer-link"><a href="#">Returns & Exchanges</a></li>
                        <li class="footer-link"><a href="#">Shipping Information</a></li>
                        <li class="footer-link"><a href="#">FAQ</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3 class="footer-title">About</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="#">Our Story</a></li>
                        <li class="footer-link"><a href="#">Sustainability</a></li>
                        <li class="footer-link"><a href="#">Careers</a></li>
                        <li class="footer-link"><a href="#">Press</a></li>
                        <li class="footer-link"><a href="#">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} MINIMAL HOME. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const mobileMenuClose = document.querySelector('.mobile-menu-close');
        const mobileMenu = document.querySelector('.mobile-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        mobileMenuClose.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
            document.body.style.overflow = '';
        });
        
        // Search functionality
        const searchBtn = document.querySelector('.header-actions .ri-search-line').parentElement;
        
        searchBtn.addEventListener('click', () => {
            // Implement search modal or redirect to search page
            console.log('Search clicked');
        });
    </script>
    
    @yield('scripts')
</body>
</html>