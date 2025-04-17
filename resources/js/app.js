import './bootstrap';

// marquee section
document.addEventListener('DOMContentLoaded', function() {
    const marqueeItems = document.querySelectorAll('.marquee-content > div, .marquee-content-reverse > div');
    
    marqueeItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        item.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        item.style.transitionDelay = `${index * 0.1}s`;
        
        setTimeout(() => {
            item.style.opacity = '0.9';
            item.style.transform = 'translateY(0)';
        }, 100);
    });
});

// testimonial section
document.addEventListener('DOMContentLoaded', function() {
  function setupInfiniteScroll() {
    const leftRow = document.querySelector('.row-left');
    const rightRow = document.querySelector('.row-right');
    
    const leftCards = leftRow.querySelectorAll('.testimonial-card');
    const rightCards = rightRow.querySelectorAll('.testimonial-card');
    
    leftCards.forEach(card => {
      const clone = card.cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      leftRow.appendChild(clone);
    });
    
    rightCards.forEach(card => {
      const clone = card.cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      rightRow.appendChild(clone);
    });
  }
  
  setupInfiniteScroll();
  
  const cards = document.querySelectorAll('.testimonial-card');
  cards.forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    card.style.transitionDelay = `${index * 0.1}s`;
    
    setTimeout(() => {
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    }, 100);
  });
});

document.addEventListener('DOMContentLoaded', function() {
  function setupInfiniteScroll() {
    const leftRow = document.querySelector('.row-left');
    const rightRow = document.querySelector('.row-right');
    
    const leftCards = leftRow.querySelectorAll('.testimonial-card');
    const rightCards = rightRow.querySelectorAll('.testimonial-card');
    
    leftCards.forEach(card => {
      const clone = card.cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      leftRow.appendChild(clone);
    });
    
    rightCards.forEach(card => {
      const clone = card.cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      rightRow.appendChild(clone);
    });
  }
  
  setupInfiniteScroll();
  
  const cards = document.querySelectorAll('.testimonial-card');
  cards.forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    card.style.transitionDelay = `${index * 0.1}s`;
    
    setTimeout(() => {
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    }, 100);
  });
});
