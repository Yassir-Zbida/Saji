<div class="navbar-top py-2 hidden lg:block">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <!-- Left Side -->
            <div class="flex items-center space-x-6">
                <div class="flex items-center">
                    <img src="{{ asset('storage/assets/icons/flag-eu.svg') }}" alt="Drapeau UE" class="h-4 mr-2">
                    <span class="text-sm font-medium">EUR</span>
                    <i class="ri-arrow-down-s-line text-xs ml-1"></i>
                </div>
                <a href="#" class="text-sm hover:text-gray-600 transition">Cartes Cadeaux</a>
                <a href="#" class="text-sm hover:text-gray-600 transition">Suivre Commande</a>
                <a href="#" class="text-sm hover:text-gray-600 transition">À Propos</a>
            </div>
            
            <!-- Right Side -->
            <div class="flex items-center space-x-6">
                <div class="flex items-center">
                    <i class="ri-phone-line text-sm mr-2"></i>
                    <span class="text-sm">(+212) 492-1044</span>
                </div>
                <div class="flex items-center group">
                    <div class="flex -space-x-2 mr-2">
                        <img src="/api/placeholder/24/24" alt="Profil" class="w-6 h-6 rounded-full border border-white">
                        <img src="/api/placeholder/24/24" alt="Profil" class="w-6 h-6 rounded-full border border-white">
                        <img src="/api/placeholder/24/24" alt="Profil" class="w-6 h-6 rounded-full border border-white">
                    </div>
                    <span class="text-sm group-hover:text-gray-600 transition">Support Client</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Header -->
<header class="bg-white py-4 shadow-sm sticky top-0 z-30">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between">
            <!-- Mobile Menu Toggle -->
            <button id="menuToggle" class="lg:hidden text-gray-600 focus:outline-none">
                <i class="ri-menu-line text-2xl"></i>
            </button>
            
            <!-- Logo -->
            <a href="#" class="flex items-center">
                <img src="{{ asset('storage/assets/images/saji-logo.svg') }}" alt="Woodmart" class="h-8">
            </a>
            
            <!-- Search Bar -->
            <div class="hidden lg:block relative flex-grow max-w-lg mx-8">
                <input type="text" placeholder="Rechercher des produits" class="search-input w-full pl-10 pr-4 py-2 rounded-full focus:outline-none text-sm">
                <i class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            
            <!-- Icons -->
            <div class="flex items-center space-x-2 md:space-x-4">
                <button class="icon-btn hidden md:flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition">
                    <i class="ri-arrow-left-right-line"></i>
                </button>
                
                <button class="icon-btn hidden md:flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition">
                    <i class="ri-heart-line"></i>
                </button>
                
                <a href="#" class="icon-btn hidden md:flex items-center space-x-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition">
                        <i class="ri-user-line"></i>
                    </div>
                    <div class="hidden lg:block">
                        <span class="text-sm">Connexion / Inscription</span>
                    </div>
                </a>
                
                <button id="cartToggle" class="cart-btn flex items-center space-x-2 rounded-full px-4 py-2">
                    <i class="ri-shopping-cart-2-line"></i>
                    <span class="font-medium">0,00 €</span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Search -->
<div class="lg:hidden px-4 py-3 bg-white border-t border-gray-100">
    <div class="relative">
        <input type="text" placeholder="Rechercher des produits" class="search-input w-full pl-10 pr-4 py-2 rounded-full focus:outline-none text-sm">
        <i class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
    </div>
</div>

<!-- Category Navigation -->
<nav class="category-nav py-3">
    <div class="container mx-auto px-4">
        <div class="scrollbar-hide overflow-x-auto">
            <div class="flex space-x-8 min-w-max">
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black">
                    <img src="{{ asset('storage/assets/icons/Chair.svg') }}" alt="Chaises" class="w-5 h-5 mb-1">
                    <span class="text-sm">Chaises</span>
                </a>
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black">
                    <img src="{{ asset('storage/assets/icons/tables.svg') }}" alt="Tables" class="w-5 h-5 mb-1">
                    <span class="text-sm">Tables</span>
                </a>
                <a href="#" class="category-link flex gap-1.5 justify-center items-center text-black">
                    <img src="{{ asset('storage/assets/icons/sofas.svg') }}" alt="Canapés" class="w-5 h-5 mb-1">
                    <span class="text-sm">Canapés</span>
                </a>
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black">
                    <img src="{{ asset('storage/assets/icons/Armchairs.svg') }}" alt="Fauteuils" class="w-5 h-5 mb-1">
                    <span class="text-sm">Fauteuils</span>
                </a>
                <a href="#" class="category-link flex gap-1.5 justify-center items-center text-black">
                    <img src="{{ asset('storage/assets/icons/Beds.svg') }}" alt="Lits" class="w-5 h-5 mb-1">
                    <span class="text-sm">Lits</span>
                </a>
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black">
                    <img src="{{ asset('storage/assets/icons/storage.svg') }}" alt="Rangement" class="w-5 h-5 mb-1">
                    <span class="text-sm">Rangement</span>
                </a>
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black">
                    <img src="{{ asset('storage/assets/icons/textiles.svg') }}" alt="Textiles" class="w-5 h-5 mb-1">
                    <span class="text-sm">Textiles</span>
                </a>
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black">
                    <img src="{{ asset('storage/assets/icons/lighting.svg') }}" alt="Éclairage" class="w-5 h-5 mb-1">
                    <span class="text-sm">Éclairage</span>
                </a>
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black">
                    <img src="{{ asset('storage/assets/icons/toys.svg') }}" alt="Jouets" class="w-5 h-5 mb-1">
                    <span class="text-sm">Jouets</span>
                </a>
                <a href="#" class="category-link flex gap-1 justify-center items-center text-black">
                    <img src="{{ asset('storage/assets/icons/Decor.svg') }}" alt="Décoration" class="w-5 h-5 mb-1">
                    <span class="text-sm">Décoration</span>
                </a>
            </div>
        </div>
    </div>
</nav>



<!-- Mobile Sidebar -->
<div id="mobileSidebar" class="sidebar fixed top-0 left-0 h-full w-80 bg-white z-40 shadow-xl overflow-y-auto">
    <div class="p-4 border-b border-gray-100 flex justify-between items-center">
        <img src="{{ asset('storage/assets/images/saji-logo.svg') }}" alt="Woodmart" class="h-6">
        <button id="closeSidebar" class="text-gray-500 hover:text-gray-700">
            <i class="ri-close-line text-2xl"></i>
        </button>
    </div>
    
    <!-- Sidebar content -->
    <div class="p-4 border-b border-gray-100">
        <h3 class="text-xs uppercase text-gray-500 font-semibold mb-3">Sélectionner la devise</h3>
        <div class="grid grid-cols-2 gap-2">
            <div class="p-2 border rounded flex items-center hover:bg-gray-50 cursor-pointer">
                <img src="{{ asset('storage/assets/icons/flag-eu.svg') }}" alt="Drapeau UE" class="w-5 h-3 mr-2">
                <span class="text-sm">EUR (€)</span>
            </div>
            <div class="p-2 border rounded flex items-center hover:bg-gray-50 cursor-pointer">
                <img src="{{ asset('storage/assets/icons/flag-usa.svg') }}" alt="Drapeau US" class="w-5 h-3 mr-2">
                <span class="text-sm">USD ($)</span>
            </div>
        </div>
    </div>
    
    <div class="p-4">
        <div class="space-y-4">
            <!-- Account links -->
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-xs uppercase text-gray-500 font-semibold mb-3">Compte</h3>
                <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                    <i class="ri-user-line text-lg mr-3"></i>
                    <span>Connexion / Inscription</span>
                </a>
                <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                    <i class="ri-heart-line text-lg mr-3"></i>
                    <span>Liste de souhaits</span>
                </a>
            </div>
            
            <!-- Categories -->
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-xs uppercase text-gray-500 font-semibold mb-3">Catégories</h3>
                <div class="grid grid-cols-1 gap-2">
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('storage/assets/icons/Chair.svg') }}" alt="Chaise" class="w-5 h-5 mr-3">
                        <span>Chaises</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('storage/assets/icons/tables.svg') }}" alt="Table" class="w-5 h-5 mr-3">
                        <span>Tables</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('storage/assets/icons/sofas.svg') }}" alt="Canapé" class="w-5 h-5 mr-3">
                        <span>Canapés</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('storage/assets/icons/Armchairs.svg') }}" alt="Fauteuil" class="w-5 h-5 mr-3">
                        <span>Fauteuils</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('storage/assets/icons/Beds.svg') }}" alt="Lit" class="w-5 h-5 mr-3">
                        <span>Lits</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('storage/assets/icons/storage.svg') }}" alt="Rangement" class="w-5 h-5 mr-3">
                        <span>Rangement</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('storage/assets/icons/textiles.svg') }}" alt="Textiles" class="w-5 h-5 mr-3">
                        <span>Textiles</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('storage/assets/icons/lighting.svg') }}" alt="Éclairage" class="w-5 h-5 mr-3">
                        <span>Éclairage</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('storage/assets/icons/toys.svg') }}" alt="Jouets" class="w-5 h-5 mr-3">
                        <span>Jouets</span>
                    </a>
                    <a href="#" class="flex items-center py-2 text-gray-600 hover:text-gray-900 transition">
                        <img src="{{ asset('storage/assets/icons/Decor.svg') }}" alt="Décoration" class="w-5 h-5 mr-3">
                        <span>Décoration</span>
                    </a>
                </div>
            </div>
            <div class="bg-black text-white p-2 text-center text-sm rounded">
                <a href="">Livraison gratuite pour toutes les commandes de plus de 1 300 €</a>
            </div>
            
            <!-- Info links -->
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-xs uppercase text-gray-500 font-semibold mb-3">Informations</h3>
                <a href="#" class="block py-2 text-gray-600 hover:text-gray-900 transition">Cartes Cadeaux</a>
                <a href="#" class="block py-2 text-gray-600 hover:text-gray-900 transition">Salles d'exposition</a>
                <a href="#" class="block py-2 text-gray-600 hover:text-gray-900 transition">À Propos</a>
            </div>
        </div>
    </div>
</div>

<!-- Cart Sidebar -->
<div id="cartSidebar" class="cart-sidebar fixed top-0 right-0 h-full w-80 md:w-96 bg-white z-40 shadow-xl overflow-hidden flex flex-col">
    <div class="p-4 border-b border-gray-100 flex justify-between items-center">
        <h2 class="font-medium">Panier d'achat</h2>
        <button id="closeCart" class="text-gray-500 hover:text-gray-700">
            <i class="ri-close-line text-2xl"></i>
        </button>
    </div>
    
    <!-- Empty cart state -->
    <div class="flex-1 flex flex-col items-center justify-center p-4">
        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
            <i class="ri-shopping-cart-line text-3xl text-gray-400"></i>
        </div>
        <h3 class="font-medium mb-1">Votre panier est vide</h3>
        <p class="text-sm text-gray-500 text-center mb-6">Il semble que vous n'ayez pas encore ajouté de produits à votre panier.</p>
        <button class="bg-gray-900 text-white px-6 py-2 rounded-full hover:bg-black transition">
            Commencer vos achats
        </button>
    </div>
    
    <!-- Cart footer -->
    <div class="p-4 border-t border-gray-100">
        <div class="flex justify-between mb-4">
            <span>Sous-total</span>
            <span class="font-medium">0,00 €</span>
        </div>
        <button class="w-full bg-gray-900 text-white py-3 rounded-full hover:bg-black transition">
            Passer à la caisse
        </button>
        <button class="w-full text-gray-600 py-2 mt-2 hover:text-gray-900 transition">
            Voir le panier
        </button>
    </div>
</div>

<div id="overlay" class="overlay fixed inset-0 bg-opacity-50 z-30"></div>