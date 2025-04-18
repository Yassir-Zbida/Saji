@extends('layouts.auth')

@section('title', '403 - Forbidden Access')

@section('content')
<div class="min-h-screen w-full relative grid-bg flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 flex items-center justify-center opacity-10" id="backgroundText">
        <h1 class="text-[25rem] font-jakarta font-medium text-primary shimmer">403</h1>
    </div>
    
    <div class="absolute inset-0 bg-glow"></div>
    
    <div class="relative z-10 flex flex-col items-center justify-center px-4 max-w-md mx-auto text-center">
        <h2 class="text-4xl md:text-5xl font-jakarta font-semibold mb-6 animate-fade-in">Access Forbidden</h2>
        
        <div class="mb-10">
            <p class="text-gray-700 mb-4 animate-fade-in delay-1">You do not have permission to access this page.</p>
            <p class="text-gray-500 animate-fade-in delay-2">Please check your credentials or contact an administrator</p>
        </div>
        
        <a href="/" class="bg-primary text-white px-8 py-3 rounded-8px hover:bg-black transition duration-300 flex items-center gap-2 animate-fade-in delay-3 animate-pulse-slow">
            <i class="ri-lock-unlock-line"></i>
            Back to safety
        </a>
    </div>
    
</div>

<style>
    @keyframes fadeIn {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
        100% { transform: translateY(0px); }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    @keyframes shimmer {
        0% { background-position: -100% 0; }
        100% { background-position: 200% 0; }
    }
    
    .animate-fade-in {
        animation: fadeIn 1s ease-out forwards;
    }
    
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    
    .animate-pulse-slow {
        animation: pulse 3s ease-in-out infinite;
    }
    
    .grid-bg {
        background-color: white;
        background-size: 50px 50px;
        background-image:
            linear-gradient(to right, rgba(17, 17, 17, 0.05) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(17, 17, 17, 0.05) 1px, transparent 1px);
    }
    
    .bg-glow {
        background: radial-gradient(circle at center, rgba(17, 17, 17, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
    }
    
    .shimmer {
        background: linear-gradient(90deg, 
            rgba(17, 17, 17, 0.03), 
            rgba(17, 17, 17, 0.1), 
            rgba(17, 17, 17, 0.03));
        background-size: 200% 100%;
        animation: shimmer 3s infinite;
    }
    
    .delay-1 {
        animation-delay: 0.2s;
    }
    
    .delay-2 {
        animation-delay: 0.4s;
    }
    
    .delay-3 {
        animation-delay: 0.6s;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('.grid-bg');
        const content = document.getElementById('backgroundText');
        
        if (container && content) {
            container.addEventListener('mousemove', function(e) {
                const rect = container.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const xPercent = x / rect.width;
                const yPercent = y / rect.height;
                
                const moveX = (xPercent - 0.5) * 20;
                const moveY = (yPercent - 0.5) * 20;
                
                content.style.transform = `translate(${moveX}px, ${moveY}px)`;
                
                const shadowX = (xPercent - 0.5) * 30;
                const shadowY = (yPercent - 0.5) * 30;
                container.style.boxShadow = `${shadowX}px ${shadowY}px 40px rgba(0, 0, 0, 0.1)`;
            });
            
            container.addEventListener('mouseleave', function() {
                content.style.transform = 'translate(0px, 0px)';
                container.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.08)';
            });
            
            const icons = [
                'ri-lock-line',
                'ri-shield-line',
                'ri-forbid-line',
                'ri-key-line'
            ];
            
            for (let i = 0; i < 6; i++) {
                const icon = document.createElement('div');
                const randomIcon = icons[Math.floor(Math.random() * icons.length)];
                icon.innerHTML = `<i class="${randomIcon}"></i>`;
                icon.className = 'text-gray-300 text-2xl absolute animate-float';
                icon.style.opacity = '0.3';
                icon.style.top = `${Math.random() * 80 + 10}%`;
                icon.style.left = `${Math.random() * 80 + 10}%`;
                icon.style.animationDelay = `${Math.random() * 5}s`;
                icon.style.animationDuration = `${Math.random() * 4 + 4}s`;
                container.appendChild(icon);
            }
        }
    });
</script>
@endsection