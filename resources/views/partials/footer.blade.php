<footer class="bg-gray-50 border-t border-gray-100 pt-12 pb-8">
    <div class="container mx-auto px-4 md:px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- About Column -->
            <div>
                <h3 class="text-lg font-semibold mb-4">About Saji</h3>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                    Saji offers thoughtfully designed, high-quality furniture and home decor that combines modern aesthetics with timeless craftsmanship. Our pieces are designed to inspire and elevate your living spaces.
                </p>
                <div class="flex space-x-4 mt-4">
                    <a href="#" aria-label="Instagram" class="text-gray-500 hover:text-saji-accent transition-colors duration-200">
                        <i class="ti ti-brand-instagram text-xl"></i>
                    </a>
                    <a href="#" aria-label="Pinterest" class="text-gray-500 hover:text-saji-accent transition-colors duration-200">
                        <i class="ti ti-brand-pinterest text-xl"></i>
                    </a>
                    <a href="#" aria-label="Facebook" class="text-gray-500 hover:text-saji-accent transition-colors duration-200">
                        <i class="ti ti-brand-facebook text-xl"></i>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links Column -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('shop') }}" class="text-gray-600 hover:text-saji-accent text-sm transition-colors duration-200">
                            Shop All
                        </a>
                    </li>
                    <li>
                        {{-- <a href="{{ route('shop.category', 'furniture') }}" class="text-gray-600 hover:text-saji-accent text-sm transition-colors duration-200">
                            Furniture
                        </a> --}}
                    </li>
                    <li>
                        {{-- <a href="{{ route('shop.category', 'decor') }}" class="text-gray-600 hover:text-saji-accent text-sm transition-colors duration-200">
                            Decor
                        </a> --}}
                    </li>
                    <li>
                        {{-- <a href="{{ route('shop.category', 'lighting') }}" class="text-gray-600 hover:text-saji-accent text-sm transition-colors duration-200">
                            Lighting
                        </a> --}}
                    </li>
                    <li>
                        {{-- <a href="{{ route('about') }}" class="text-gray-600 hover:text-saji-accent text-sm transition-colors duration-200">
                            Our Story
                        </a> --}}
                    </li>
                    <li>
                        {{-- <a href="{{ route('contact') }}" class="text-gray-600 hover:text-saji-accent text-sm transition-colors duration-200">
                            Contact Us
                        </a> --}}
                    </li>
                </ul>
            </div>
            
            <!-- Newsletter Column -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Stay Connected</h3>
                <p class="text-gray-600 text-sm mb-4">
                    Subscribe to our newsletter for exclusive offers, design tips, and new product announcements.
                </p>
                {{-- action="{{ route('newsletter.subscribe') }}" --}}
                <form method="POST" class="mt-2">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-2">
                        <input 
                            type="email" 
                            name="email" 
                            placeholder="Your email address" 
                            required
                            class="flex-grow px-4 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-saji-accent focus:border-transparent"
                        >
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-saji-black text-white text-sm font-medium rounded-md hover:bg-saji-accent transition-colors duration-200 whitespace-nowrap"
                        >
                            Subscribe
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Copyright Section -->
        <div class="mt-12 pt-6 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-xs text-gray-500">
                    &copy; {{ date('Y') }} Saji. All rights reserved.
                </p>
                <div class="mt-4 md:mt-0">
                    <ul class="flex space-x-6">
                        <li>
                            <a href="{{ route('privacy') }}" class="text-xs text-gray-500 hover:text-saji-accent transition-colors duration-200">
                                Privacy Policy
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('terms') }}" class="text-xs text-gray-500 hover:text-saji-accent transition-colors duration-200">
                                Terms of Service
                            </a>
                        </li>
                        <li>
                            {{-- <a href="{{ route('shipping') }}" class="text-xs text-gray-500 hover:text-saji-accent transition-colors duration-200">
                                Shipping Info
                            </a> --}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>