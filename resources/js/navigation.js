// resources/js/navigation.js

// Mobile menu toggle
const menuToggle = document.getElementById('menuToggle');
const mobileSidebar = document.getElementById('mobileSidebar');
const closeSidebar = document.getElementById('closeSidebar');

// Cart toggle
const cartToggle = document.getElementById('cartToggle');
const cartSidebar = document.getElementById('cartSidebar');
const closeCart = document.getElementById('closeCart');

// Overlay
const overlay = document.getElementById('overlay');

if (menuToggle) {
    menuToggle.addEventListener('click', () => {
        mobileSidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });
}

if (closeSidebar) {
    closeSidebar.addEventListener('click', () => {
        mobileSidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    });
}

if (cartToggle) {
    cartToggle.addEventListener('click', () => {
        cartSidebar.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });
}

if (closeCart) {
    closeCart.addEventListener('click', () => {
        cartSidebar.classList.add('translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    });
}

if (overlay) {
    overlay.addEventListener('click', () => {
        mobileSidebar.classList.add('-translate-x-full');
        cartSidebar.classList.add('translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    });
}