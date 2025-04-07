import "./bootstrap";

document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menuToggle");
    const mobileSidebar = document.getElementById("mobileSidebar");
    const closeSidebar = document.getElementById("closeSidebar");
    const cartToggle = document.getElementById("cartToggle");
    const cartSidebar = document.getElementById("cartSidebar");
    const closeCart = document.getElementById("closeCart");
    const overlay = document.getElementById("overlay");

    if (mobileSidebar) mobileSidebar.style.display = "none";
    if (cartSidebar) cartSidebar.style.display = "none";
    if (overlay) overlay.style.display = "none";

    if (menuToggle) {
        menuToggle.addEventListener("click", () => {
            mobileSidebar.style.display = "block";
            mobileSidebar.classList.add("open");
            overlay.style.display = "block";
            overlay.classList.add("active");
            document.body.style.overflow = "hidden";
        });
    }

    if (closeSidebar) {
        closeSidebar.addEventListener("click", () => {
            mobileSidebar.classList.remove("open");
            setTimeout(() => {
                mobileSidebar.style.display = "none";
            }, 300);
            overlay.classList.remove("active");
            setTimeout(() => {
                overlay.style.display = "none";
            }, 300);
            document.body.style.overflow = "";
        });
    }

    if (cartToggle) {
        cartToggle.addEventListener("click", () => {
            cartSidebar.style.display = "flex";
            cartSidebar.classList.add("open");
            overlay.style.display = "block";
            overlay.classList.add("active");
            document.body.style.overflow = "hidden";
        });
    }

    if (closeCart) {
        closeCart.addEventListener("click", () => {
            cartSidebar.classList.remove("open");
            setTimeout(() => {
                cartSidebar.style.display = "none";
            }, 300);
            overlay.classList.remove("active");
            setTimeout(() => {
                overlay.style.display = "none";
            }, 300);
            document.body.style.overflow = "";
        });
    }
    if (overlay) {
        overlay.addEventListener("click", () => {
            mobileSidebar.classList.remove("open");
            cartSidebar.classList.remove("open");
            setTimeout(() => {
                mobileSidebar.style.display = "none";
                cartSidebar.style.display = "none";
            }, 300);
            overlay.classList.remove("active");
            setTimeout(() => {
                overlay.style.display = "none";
            }, 300);
            document.body.style.overflow = "";
        });
    }
});
