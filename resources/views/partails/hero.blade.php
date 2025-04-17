<section class="hero-section relative min-h-screen overflow-hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat hero-bg"
         style="background-image: url('{{ asset('images/hero.webp') }}');"></div>
    
    <div class="absolute inset-0 bg-black/30"></div>
    
    <div class="container mx-auto px-4 relative z-20">
        <div class="max-w-2xl mx-auto text-center text-white">
            <h1 class="text-4xl md:text-6xl font-jakarta font-bold leading-tight mb-6 text-white hero-title animate-fade-in-up">
                Transform Your <br>Living Space
            </h1>
            
            <p class="text-lg md:text-xl font-jost mb-8 text-white/90 hero-description animate-fade-in-up-delay-1">
                Crafting beautiful, functional homes with furniture that reflects your style and fits your life perfectly.
            </p>
            
            <div class="flex flex-wrap gap-4 justify-center animate-fade-in-up-delay-2">
                <a href="#" class="btn-primary px-8 py-4 bg-black text-white font-jost font-medium rounded-md hover:bg-opacity-90 transition-all duration-300 flex items-center group">
                    Shop Collection
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
                
                <a href="#" class="btn-secondary px-8 py-4 border border-white text-white font-jost font-medium rounded-md hover:bg-white hover:text-black transition-all duration-300">
                    Explore Ideas
                </a>
            </div>
        </div>
    </div>
    
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex flex-col items-center space-y-3 z-20 animate-fade-in-up-delay-3">
        <span class="text-sm font-jost text-white/80">Scroll to discover</span>
        <div class="w-6 h-10 border-2 border-white/70 rounded-full flex justify-center pt-2">
            <div class="w-1.5 h-1.5 bg-white rounded-full animate-bounce"></div>
        </div>
    </div>
</section>