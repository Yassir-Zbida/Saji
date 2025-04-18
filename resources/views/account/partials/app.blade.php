<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Home Decor and Furniture</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Italiana&family=Jost:wght@400;500&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/navigation.js'])
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#111111',
                        secondary: '#F5F5F5',
                        accent: '#E8E8E8',
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
</head>
<body class="bg-white font-jakarta">
    <div class="min-h-screen flex flex-col">
        @include('partails.header')
        
        <div class="account-wrapper flex-grow flex flex-col md:flex-row py-16">
            <div class="container mx-auto px-4">
                <div class="section-header text-center mb-12 md:hidden">
                    <span class="subtitle font-jost uppercase tracking-widest text-sm font-medium block mb-3">MY ACCOUNT</span>
                    <h1 class="title font-jakarta text-3xl font-bold mb-4">Your Personal Dashboard</h1>
                </div>
                
                <div class="flex flex-col md:flex-row gap-8">
                    <div class="md:hidden mb-6">
                        <button id="mobile-sidebar-toggle" class="w-full flex items-center justify-between bg-white p-4 rounded-xl shadow-soft border border-accent/20">
                            <span class="flex items-center">
                                <i class="ri-menu-line mr-3"></i>
                                <span class="font-medium">Menu</span>
                            </span>
                            <i class="ri-arrow-down-s-line transition-transform" id="toggle-icon"></i>
                        </button>
                        
                    </div>
                    
                    <div class="flex-1">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        
        @include('partails.footer')
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.getElementById('mobile-sidebar-toggle');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const toggleIcon = document.getElementById('toggle-icon');
            
            if (mobileToggle && mobileSidebar && toggleIcon) {
                mobileToggle.addEventListener('click', function() {
                    mobileSidebar.classList.toggle('hidden');
                    toggleIcon.classList.toggle('rotate-180');
                });
            }
            
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    easing: 'ease-out-cubic',
                    once: true
                });
            }
        });
    </script>
</body>
</html>