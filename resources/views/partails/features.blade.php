<!-- Philosophy Section -->
<section class="design-philosophy py-14 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-left mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-3">Our Design Philosophy</h2>
            <div class="w-10 h-[2px] bg-black mb-6"></div>
            <p class="text-gray-700 max-w-2xl">
                Crafting spaces that embody minimalism, functionality, and timeless elegance.
            </p>
        </div>

        <div class="grid grid-cols-12 gap-6 mb-20">
            <div class="col-span-12 md:col-span-7 feature-panel">
                <div class="h-full flex flex-col">
                    <div class="overflow-hidden mb-4 rounded-2xl">
                        <img src="{{ asset('images/main-img-1.jpg') }}" alt="Minimalist Living" class="w-full h-[400px] md:h-[500px] object-cover hover:scale-105 transition duration-700 ease-out">
                    </div>
                    <div class="flex items-start">
                        <i class="ri-contrast-2-line text-xl mr-4 mt-1"></i>
                        <div>
                            <h3 class="text-xl font-semibold mb-2">Minimal Elegance</h3>
                            <p class="text-gray-600">Clean lines and thoughtful simplicity create spaces that feel both luxurious and livable.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 md:col-span-5 feature-panel">
                <div class="h-full border flex flex-col justify-between border-black/10 p-8 rounded-2xl">
                    <h3 class="text-xl font-semibold mb-3">Considered Details</h3>
                    <p class="text-gray-600 mb-4">Every element is carefully chosen to create spaces that not only look beautiful but also function effortlessly for everyday living. We believe that a well-designed space should serve as a backdrop to your life — intuitive, inspiring, and tailored to your unique rhythm. That’s why our process begins with understanding how you live, what brings you comfort, and how your environment can support both the quiet moments and the lively ones.
                        From the architectural flow of a room to the smallest accent piece, each decision is rooted in purpose. We thoughtfully layer materials, colors, and textures to evoke warmth, depth, and sophistication, while ensuring that every piece earns its place — not just in form, but in function. Whether it’s a cozy reading nook bathed in natural light, a kitchen designed to bring people together, or smart storage solutions hidden behind beautiful cabinetry, our spaces are made to enhance real life.
                        We embrace both the art and science of design — combining timeless aesthetics with practical innovation. This means using high-quality, durable materials that stand the test of time.
                    </p>
                    <a href="#" class="inline-flex items-center text-black font-medium hover:opacity-70 transition duration-300">
                        <span>Explore details</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </a>
                </div>
            </div>

            <div class="col-span-12 md:col-span-6 feature-panel">
                <div class="h-full flex flex-col">
                    <div class="overflow-hidden mb-4 rounded-2xl">
                        <img src="{{ asset('images/rev-img-3.jpg') }}" alt="Functional Design" class="w-full h-[350px] object-cover hover:scale-105 transition duration-700 ease-out">
                    </div>
                    <div class="flex items-start">
                        <i class="ri-drop-line text-xl mr-4 mt-1"></i>
                        <div>
                            <h3 class="text-xl font-semibold mb-2">Functional Beauty</h3>
                            <p class="text-gray-600">Pieces that balance form and function, designed to enhance your lifestyle with purpose.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 md:col-span-6 feature-panel">
                <div class="h-full flex flex-col">
                    <div class="overflow-hidden mb-4 rounded-2xl">
                        <img src="{{ asset('images/h1-img-002.jpg') }}" alt="Natural Materials" class="w-full h-[350px] object-cover hover:scale-105 transition duration-700 ease-out">
                    </div>
                    <div class="flex items-start">
                        <i class="ri-leaf-line text-xl mr-4 mt-1"></i>
                        <div>
                            <h3 class="text-xl font-semibold mb-2">Natural Materials</h3>
                            <p class="text-gray-600">We prioritize organic textures and sustainable materials that bring warmth and character.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-20 relative">
            <div class="rounded-3xl bg-black text-white p-8 md:p-12">
                <div class="max-w-2xl">
                    <h3 class="text-2xl font-bold mb-4">Our Design System</h3>
                    <p class="text-gray-100 mb-6">
                        We've developed a comprehensive design system that ensures consistency, quality, and attention to detail across all our projects. From material selection to final styling, every decision is guided by our core principles.
                    </p>
                    <a href="#" class="inline-flex items-center text-white font-medium hover:opacity-70 transition duration-300">
                        <span>Learn more about our system</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<style>
.feature-panel {
    transition: opacity 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.feature-panel, .process-step, .content-block {
    opacity: 0;
    animation: fadeIn 0.8s ease-out forwards;
}

.feature-panel:nth-child(1) {
    animation-delay: 0.1s;
}

.feature-panel:nth-child(2) {
    animation-delay: 0.25s;
}

.feature-panel:nth-child(3) {
    animation-delay: 0.4s;
}

.feature-panel:nth-child(4) {
    animation-delay: 0.55s;
}

.process-step:nth-child(1) {
    animation-delay: 0.2s;
}

.process-step:nth-child(2) {
    animation-delay: 0.35s;
}

.process-step:nth-child(3) {
    animation-delay: 0.5s;
}

.content-block:nth-child(1) {
    animation-delay: 0.3s;
}

.content-block:nth-child(2) {
    animation-delay: 0.45s;
}

.feature-panel:hover img {
    transform: scale(1.05);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const animatedElements = document.querySelectorAll('.feature-panel, .process-step, .content-block');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.2
    });
    
    animatedElements.forEach(el => {
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });
});
</script>