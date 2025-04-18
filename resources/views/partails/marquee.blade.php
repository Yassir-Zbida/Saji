<!-- Marquee Section -->
<section class="w-full py-24 bg-black overflow-hidden md:px-4">
    <div class="text-center mb-16 px-5">
        <div class="text-sm uppercase tracking-widest font-medium mb-3 text-gray-400 font-jost">Key Capabilities</div>
        <h2 class="text-4xl md:text-5xl font-bold text-white font-jakarta">What Drives Us</h2>
    </div>
    
    <div class="flex overflow-hidden w-full relative">
        <div class="marquee-content flex whitespace-nowrap">
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Innovative</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Minimal</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Elegant</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Crafted</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Modern</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Timeless</div>
        </div>
        <div class="marquee-content flex whitespace-nowrap" aria-hidden="true">
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Innovative</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Minimal</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Elegant</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Crafted</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Modern</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Timeless</div>
        </div>
    </div>
    
    <div class="flex overflow-hidden w-full relative mt-10">
        <div class="marquee-content-reverse flex whitespace-nowrap">
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Functional</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Simple</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Premium</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Thoughtful</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Sustainable</div>
        </div>
        <div class="marquee-content-reverse flex whitespace-nowrap" aria-hidden="true">
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Functional</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Simple</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Premium</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Thoughtful</div>
            <div class="text-7xl md:text-8xl font-bold px-8 opacity-90 flex items-center text-white">Sustainable</div>
        </div>
    </div>
</section>

<style>
@keyframes marqueeScroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-100%); }
}

@keyframes marqueeScrollReverse {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(0); }
}

.marquee-content {
    animation: marqueeScroll 30s linear infinite;
    will-change: transform;
}

.marquee-content-reverse {
    animation: marqueeScrollReverse 30s linear infinite;
    will-change: transform;
}

.marquee-content:hover,
.marquee-content-reverse:hover {
    animation-play-state: paused;
}

.marquee-content > div:not(:last-child)::after,
.marquee-content-reverse > div:not(:last-child)::after {
    content: "•";
    margin-left: 30px;
    font-size: 24px;
    opacity: 0.4;
    color: white;
}
</style>
