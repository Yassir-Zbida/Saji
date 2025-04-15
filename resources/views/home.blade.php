<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('title', 'Home')

@section('styles')
<style>
    .hero {
        position: relative;
        height: 80vh;
        display: flex;
        align-items: center;
        background-color: var(--gray-100);
        overflow: hidden;
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 600px;
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        letter-spacing: -1px;
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        color: var(--gray-600);
    }
    
    .hero-image {
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
        width: 50%;
        object-fit: cover;
    }
    
    .categories {
        padding: 5rem 0;
    }
    
    .section-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }
    
    @media (max-width: 768px) {
        .categories-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 480px) {
        .categories-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .category-card {
        position: relative;
        height: 300px;
        overflow: hidden;
    }
    
    .category-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .category-card:hover .category-image {
        transform: scale(1.05);
    }
    
    .category-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .category-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .featured-products {
        padding: 5rem 0;
        background-color: var(--gray-100);
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
    }
    
    @media (max-width: 992px) {
        .products-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 480px) {
        .products-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .product-card {
        background: var(--white);
    }
    
    .product-image-container {
        position: relative;
        padding-top: 100%; /* 1:1 Aspect Ratio */
        overflow: hidden;
    }
    
    .product-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    
    .product-actions {
        position: absolute;
        top: 1rem;
        right: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .product-action {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        background: var(--white);
        border-radius: 50%;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .product-action:hover {
        background: var(--black);
        color: var(--white);
    }
    
    .product-info {
        padding: 1.5rem;
    }
    
    .product-title {
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .product-price {
        font-size: 1.125rem;
        font-weight: 600;
    }
    
    .banner {
        padding: 5rem 0;
        background-color: var(--gray-900);
        color: var(--white);
        text-align: center;
    }
    
    .banner-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .banner-text {
        font-size: 1.125rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 2rem;
        color: var(--gray-300);
    }
    
    .btn-white {
        background: var(--white);
        color: var(--black);
        border: none;
    }
    
    .btn-white:hover {
        background: var(--gray-200);
        color: var(--black);
    }
    
    .instagram {
        padding: 5rem 0;
    }
    
    .instagram-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.5rem;
    }
    
    @media (max-width: 992px) {
        .instagram-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .instagram-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 480px) {
        .instagram-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    .instagram-item {
        position: relative;
        padding-top: 100%; /* 1:1 Aspect Ratio */
        overflow: hidden;
    }
    
    .instagram-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .instagram-item:hover .instagram-image {
        transform: scale(1.05);
    }
    
    .instagram-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .instagram-item:hover .instagram-overlay {
        opacity: 1;
    }
    
    .instagram-icon {
        color: var(--white);
        font-size: 1.5rem;
    }
</style>
@endsection

@section('content')
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Minimal Design for Modern Living</h1>
            <p class="hero-subtitle">Curated furniture and home decor that brings simplicity and elegance to your space.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary">Shop Now</a>
        </div>
    </div>
    <img src="{{ asset('images/hero.jpg') }}" alt="Modern living room with minimal furniture" class="hero-image">
</section>

<section class="categories">
    <div class="container">
        <h2 class="section-title">Shop by Category</h2>
        
        <div class="categories-grid">
            <a href="#" class="category-card">
                <img src="{{ asset('images/category-living.jpg') }}" alt="Living Room" class="category-image">
                <div class="category-overlay">
                    <h3 class="category-title">Living Room</h3>
                    <span>Shop Now →</span>
                </div>
            </a>
            
            <a href="#" class="category-card">
                <img src="{{ asset('images/category-bedroom.jpg') }}" alt="Bedroom" class="category-image">
                <div class="category-overlay">
                    <h3 class="category-title">Bedroom</h3>
                    <span>Shop Now →</span>
                </div>
            </a>
            
            <a href="#" class="category-card">
                <img src="{{ asset('images/category-dining.jpg') }}" alt="Dining" class="category-image">
                <div class="category-overlay">
                    <h3 class="category-title">Dining</h3>
                    <span>Shop Now →</span>
                </div>
            </a>
        </div>
    </div>
</section>

<section class="featured-products">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        
        <div class="products-grid">
            @if(isset($featuredProducts) && count($featuredProducts) > 0)
                @foreach($featuredProducts as $product)
                <div class="product-card">
                    <div class="product-image-container">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="product-image">
                        <div class="product-actions">
                            <button class="product-action" aria-label="Add to wishlist">
                                <i class="ri-heart-line"></i>
                            </button>
                            <button class="product-action" aria-label="Quick view">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <p class="product-price">${{ number_format($product->price, 2) }}</p>
                        <button class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Add to Cart</button>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <p>No featured products available at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<section class="banner">
    <div class="container">
        <h2 class="banner-title">Craftsmanship Meets Modern Design</h2>
        <p class="banner-text">Our furniture is crafted with attention to detail, using sustainable materials and timeless design principles.</p>
        <a href="{{ route('about') }}" class="btn btn-white">Learn More</a>
    </div>
</section>

<section class="instagram">
    <div class="container">
        <h2 class="section-title">Follow Us on Instagram</h2>
        
        <div class="instagram-grid">
            @for($i = 1; $i <= 5; $i++)
            <a href="#" class="instagram-item">
                <img src="{{ asset('images/instagram-' . $i . '.jpg') }}" alt="Instagram post" class="instagram-image">
                <div class="instagram-overlay">
                    <i class="ri-instagram-line instagram-icon"></i>
                </div>
            </a>
            @endfor
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Product quick view functionality
    const quickViewButtons = document.querySelectorAll('.product-action .ri-eye-line');
    
    quickViewButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            // Implement quick view modal
            console.log('Quick view clicked');
        });
    });
    
    // Add to wishlist functionality
    const wishlistButtons = document.querySelectorAll('.product-action .ri-heart-line');
    
    wishlistButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            // Implement add to wishlist
            console.log('Add to wishlist clicked');
        });
    });
</script>
@endsection