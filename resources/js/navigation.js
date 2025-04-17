// navigation.js
document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menuToggle");
    const mobileSidebar = document.getElementById("mobileSidebar");
    const closeSidebar = document.getElementById("closeSidebar");
    const cartToggle = document.getElementById("cartToggle");
    const cartSidebar = document.getElementById("cartSidebar");
    const closeCart = document.getElementById("closeCart");
    const overlay = document.getElementById("overlay");
    const header = document.querySelector("header");

    // Fix image display issues
    const fixImages = () => {
        document.querySelectorAll('img').forEach(img => {
            img.onerror = function() {
                // If image fails to load, replace with a placeholder or add a class
                this.classList.add('image-error');
                // Optional: Replace with a placeholder
                // this.src = '/path/to/placeholder.svg';
            };
        });
    };

    // Call the function to fix images
    fixImages();

    // Add scroll effect for header
    window.addEventListener("scroll", function() {
        if (window.scrollY > 50) {
            header.classList.add("py-2", "shadow-md");
            header.classList.remove("py-4", "shadow-sm");
        } else {
            header.classList.add("py-4", "shadow-sm");
            header.classList.remove("py-2", "shadow-md");
        }
    });

    // Note: We don't need to set display: none in JS as it's already in the HTML with Tailwind's 'hidden' class

    if (menuToggle) {
        menuToggle.addEventListener("click", () => {
            // First show the element
            mobileSidebar.classList.remove("hidden");
            overlay.classList.remove("hidden");
            
            // Allow DOM to recognize the element is now in the layout before animation
            setTimeout(() => {
                mobileSidebar.classList.add("open");
                overlay.classList.add("active");
                document.body.style.overflow = "hidden";
            }, 10);
        });
    }

    if (closeSidebar) {
        closeSidebar.addEventListener("click", () => {
            closeMobileSidebar();
        });
    }

    if (cartToggle) {
        cartToggle.addEventListener("click", () => {
            // First show the element
            cartSidebar.classList.remove("hidden");
            cartSidebar.classList.add("flex"); // Ensure flex display
            overlay.classList.remove("hidden");
            
            // Allow DOM to recognize the element is now in the layout before animation
            setTimeout(() => {
                cartSidebar.classList.add("open");
                overlay.classList.add("active");
                document.body.style.overflow = "hidden";
            }, 10);
        });
    }

    if (closeCart) {
        closeCart.addEventListener("click", () => {
            closeCartSidebar();
        });
    }
    
    if (overlay) {
        overlay.addEventListener("click", () => {
            closeAllSidebars();
        });
    }

    // Add escape key functionality
    document.addEventListener("keydown", function(e) {
        if (e.key === "Escape") {
            closeAllSidebars();
        }
    });

    // Utility functions for cleaner code
    function closeMobileSidebar() {
        mobileSidebar.classList.remove("open");
        
        if (!cartSidebar.classList.contains("open")) {
            overlay.classList.remove("active");
            document.body.style.overflow = "";
            
            // Add hidden class after transition completes
            setTimeout(() => {
                if (!mobileSidebar.classList.contains("open")) {
                    mobileSidebar.classList.add("hidden");
                }
                if (!cartSidebar.classList.contains("open")) {
                    overlay.classList.add("hidden");
                }
            }, 300);
        }
    }

    function closeCartSidebar() {
        cartSidebar.classList.remove("open");
        
        if (!mobileSidebar.classList.contains("open")) {
            overlay.classList.remove("active");
            document.body.style.overflow = "";
            
            // Add hidden class after transition completes
            setTimeout(() => {
                if (!cartSidebar.classList.contains("open")) {
                    cartSidebar.classList.remove("flex");
                    cartSidebar.classList.add("hidden");
                }
                if (!mobileSidebar.classList.contains("open")) {
                    overlay.classList.add("hidden");
                }
            }, 300);
        }
    }

    function closeAllSidebars() {
        mobileSidebar.classList.remove("open");
        cartSidebar.classList.remove("open");
        overlay.classList.remove("active");
        document.body.style.overflow = "";
        
        // Add hidden class after transition completes
        setTimeout(() => {
            if (!mobileSidebar.classList.contains("open")) {
                mobileSidebar.classList.add("hidden");
            }
            if (!cartSidebar.classList.contains("open")) {
                cartSidebar.classList.remove("flex");
                cartSidebar.classList.add("hidden");
            }
            overlay.classList.add("hidden");
        }, 300);
    }
});


document.addEventListener('DOMContentLoaded', function() {
    // Accordion functionality for mobile
    const accordionButtons = document.querySelectorAll('.accordion-btn');
    
    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Get the content related to this button
            const content = this.nextElementSibling;
            const icon = this.querySelector('.ri-add-line, .ri-subtract-line');
            
            // Toggle active class 
            this.classList.toggle('active');
            
            // Toggle content visibility
            if (content.classList.contains('hidden')) {
                // Show content
                content.classList.remove('hidden');
                content.style.maxHeight = content.scrollHeight + 'px';
                
                // Change icon to minus
                if (icon.classList.contains('ri-add-line')) {
                    icon.classList.remove('ri-add-line');
                    icon.classList.add('ri-subtract-line');
                }
            } else {
                // Hide content
                content.style.maxHeight = '0px';
                
                // Use setTimeout to allow the transition to complete before hiding
                setTimeout(() => {
                    content.classList.add('hidden');
                }, 300);
                
                // Change icon back to plus
                if (icon.classList.contains('ri-subtract-line')) {
                    icon.classList.remove('ri-subtract-line');
                    icon.classList.add('ri-add-line');
                }
            }
        });
    });
});
