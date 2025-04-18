<!-- Eyes Section -->
<section class="eyes-section relative py-20 bg-white overflow-hidden lg:px-4">
    <div class="absolute top-0 left-0 w-full h-full opacity-5 z-0 pattern-bg"></div>
    
    <div class="container mx-auto relative z-10 px-4">
      <div class="section-header text-center mb-16">
        <span class="subtitle font-jost uppercase tracking-widest text-sm font-medium block mb-3">EXPERIENCE</span>
        <h2 class="title font-jakarta text-3xl md:text-4xl font-bold mb-4">Explore Our Design Vision</h2>
        <p class="description font-jost text-gray-600 max-w-2xl mx-auto">
          Our furniture is crafted with meticulous attention to aesthetics and functionality.
        </p>
      </div>
      
      <div class="eyes-container relative mx-auto">
        <div class="eyes-wrapper flex justify-center items-center h-64 md:h-80 relative">
          <div class="eye eye-left relative mx-4 md:mx-12" data-eye="left">
            <div class="eye-white"></div>
            <div class="eye-pupil"></div>
          </div>
          
          <div class="eye eye-right relative mx-4 md:mx-12" data-eye="right">
            <div class="eye-white"></div>
            <div class="eye-pupil"></div>
          </div>
        </div>
        
        <div class="tagline text-center mt-12 max-w-lg mx-auto">
          <h3 class="font-jakarta font-medium text-xl mb-4">Design that follows your movement</h3>
          <p class="font-jost text-gray-600">
            Like our eyes that follow your cursor, our furniture is designed to adapt to your lifestyle and space.
          </p>
        </div>
      </div>
      
      <div class="design-philosophy mt-20 grid md:grid-cols-3 gap-8">
        <div class="philosophy-card p-6 border border-gray-200 rounded-md transition-all duration-300 hover:border-black hover:shadow-soft">
          <div class="card-icon mb-4">
            <i class="ri-lightbulb-line text-4xl"></i>
          </div>
          <h4 class="font-jakarta font-semibold text-lg mb-2">Innovative Design</h4>
          <p class="font-jost text-gray-600">
            We blend aesthetics with functionality to create furniture that's both beautiful and practical.
          </p>
        </div>
        
        <div class="philosophy-card p-6 border border-gray-200 rounded-md transition-all duration-300 hover:border-black hover:shadow-soft">
          <div class="card-icon mb-4">
            <i class="ri-medal-line text-4xl"></i>
          </div>
          <h4 class="font-jakarta font-semibold text-lg mb-2">Quality Materials</h4>
          <p class="font-jost text-gray-600">
            We source only the finest materials to ensure durability and timeless elegance in every piece.
          </p>
        </div>
        
        <div class="philosophy-card p-6 border border-gray-200 rounded-md transition-all duration-300 hover:border-black hover:shadow-soft">
          <div class="card-icon mb-4">
            <i class="ri-leaf-line text-4xl"></i>
          </div>
          <h4 class="font-jakarta font-semibold text-lg mb-2">Sustainable Craftsmanship</h4>
          <p class="font-jost text-gray-600">
            Our commitment to the environment means creating responsibly sourced and crafted furniture.
          </p>
        </div>
      </div>
      
      
    </div>
  </section>
  
  <style>
  .eyes-section {
    width: 100%;
    position: relative;
  }
  
  .pattern-bg {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23000000' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  }
  
  .eyes-container {
    width: 100%;
    max-width: 700px;
    margin: 0 auto;
  }
  
  .eye {
    width: 140px;
    height: 140px;
    position: relative;
    display: inline-block;
    transition: transform 0.3s ease;
  }
  
  .eye:hover {
    transform: scale(1.05);
  }
  
  @media (min-width: 768px) {
    .eye {
      width: 180px;
      height: 180px;
    }
  }
  
  @media (min-width: 1024px) {
    .eye {
      width: 220px;
      height: 220px;
    }
  }
  
  .eye-white {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: white;
    border-radius: 50%;
    border: 2px solid #000;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  }
  
  .eye-pupil {
    position: absolute;
    width: 35%;
    height: 35%;
    background-color: #000;
    border-radius: 50%;
    top: 25%;
    left: 32%;
    transition: transform 0.1s ease-out;
  }
  
  .philosophy-card {
    border-radius: 8px;
    transition: all 0.3s ease;
  }
  
  .philosophy-card:hover {
    transform: translateY(-5px);
  }
  
  .shadow-soft {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  }
  
  .btn-primary {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    border-radius: 8px;
  }
  
  .btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
  }
  
  .btn-primary:active {
    transform: translateY(-1px);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
  }
  
  @keyframes eyeEntrance {
    0% {
      transform: scale(0.5);
      opacity: 0;
    }
    100% {
      transform: scale(1);
      opacity: 1;
    }
  }
  
  .eye {
    animation: eyeEntrance 0.6s ease-out forwards;
  }
  
  .eye-left {
    animation-delay: 0.1s;
  }
  
  .eye-right {
    animation-delay: 0.3s;
  }
  
  @keyframes blink {
    0% { transform: scaleY(1); }
    45% { transform: scaleY(1); }
    50% { transform: scaleY(0.1); }
    55% { transform: scaleY(1); }
    100% { transform: scaleY(1); }
  }
  
  .eye-white {
    animation: blink 6s infinite;
    transform-origin: center;
  }
  
  .eye-right .eye-white {
    animation-delay: 0.2s;
  }
  </style>
  
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const eyesContainer = document.querySelector('.eyes-wrapper');
    const pupils = document.querySelectorAll('.eye-pupil');
    const eyes = document.querySelectorAll('.eye');
    const philosophyCards = document.querySelectorAll('.philosophy-card');
    
    function getEyePositions() {
      const positions = [];
      
      eyes.forEach(eye => {
        const rect = eye.getBoundingClientRect();
        positions.push({
          x: rect.left + rect.width / 2,
          y: rect.top + rect.height / 2
        });
      });
      
      return positions;
    }
    
    let eyePositions = getEyePositions();
    
    function updateEyePositions() {
      eyePositions = getEyePositions();
    }
    
    window.addEventListener('resize', updateEyePositions);
    window.addEventListener('scroll', updateEyePositions);
    
    document.addEventListener('mousemove', function(e) {
      const mouseX = e.clientX;
      const mouseY = e.clientY;
      
      pupils.forEach((pupil, index) => {
        const eye = eyePositions[index];
        
        const deltaX = mouseX - eye.x;
        const deltaY = mouseY - eye.y;
        
        const angle = Math.atan2(deltaY, deltaX);
        
        const distance = Math.min(Math.sqrt(deltaX * deltaX + deltaY * deltaY) / 200, 1);
        
        const maxMovement = 30;
        
        const moveX = Math.cos(angle) * distance * maxMovement;
        const moveY = Math.sin(angle) * distance * maxMovement;
        
        pupil.style.transition = 'transform 0.2s ease-out';
        pupil.style.transform = `translate(${moveX}%, ${moveY}%)`;
      });
    });
    
    let inactivityTimer;
    let isActive = false;
    
    function startRandomMovement() {
      if (isActive) return;
      isActive = true;
      
      function moveRandomly() {
        if (!isActive) return;
        
        pupils.forEach(pupil => {
          const randX = (Math.random() * 40) - 20; 
          const randY = (Math.random() * 40) - 20; 
          
          pupil.style.transition = 'transform 1.5s ease-out';
          pupil.style.transform = `translate(${randX}%, ${randY}%)`;
        });
        
        setTimeout(moveRandomly, 2000 + Math.random() * 1000);
      }
      
      moveRandomly();
    }
    
    function stopRandomMovement() {
      isActive = false;
    }
    
    startRandomMovement();
    
    document.addEventListener('mousemove', function() {
      stopRandomMovement();
      
      clearTimeout(inactivityTimer);
      inactivityTimer = setTimeout(startRandomMovement, 3000);
    });
    
    function addBlink() {
      const blinkInterval = 4000 + Math.random() * 2000; 
      
      setTimeout(() => {
        const eyeWhites = document.querySelectorAll('.eye-white');
        eyeWhites.forEach((eyeWhite, index) => {
          const originalAnimation = eyeWhite.style.animation;
          
          eyeWhite.style.animation = 'blink 0.2s';
          
          setTimeout(() => {
            eyeWhite.style.animation = originalAnimation;
          }, 200);
        });
        
        addBlink();
      }, blinkInterval);
    }
    
    setTimeout(addBlink, 1000);
    
    function animateOnScroll() {
      const options = {
        threshold: 0.2,
        rootMargin: '0px 0px -10% 0px'
      };
      
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const card = entry.target;
            
            setTimeout(() => {
              card.style.opacity = '1';
              card.style.transform = 'translateY(0)';
            }, 100);
            
            observer.unobserve(card);
          }
        });
      }, options);
      
      philosophyCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        card.style.transitionDelay = `${index * 0.1}s`;
        
        observer.observe(card);
      });
    }
    
    if ('IntersectionObserver' in window) {
      animateOnScroll();
    } else {
      philosophyCards.forEach(card => {
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
      });
    }
  });
  </script>