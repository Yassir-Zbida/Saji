@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12 md:px-12 mb-12">
    <div class="mb-8">
        <h1 class="text-3xl font-medium text-primary mb-2 font-jost">My Wishlist</h1>
        <p class="text-gray-600">Items you've saved for later</p>
    </div>

    <div class="bg-white rounded-lg shadow-soft p-6 pb-12 border border-gray-200">
        @if(count($wishlistItems) > 0)
            <div class="flex justify-between items-center mb-8">
                <p class="text-gray-700"><span class="font-medium">{{ count($wishlistItems) }}</span> {{ Str::plural('item', count($wishlistItems)) }}</p>
                <button type="button" id="clear-wishlist" class="text-gray-600 hover:text-primary flex items-center transition duration-200 group">
                    <i class="ri-delete-bin-7-line mr-1"></i> Clear all
                </button>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 lg:gap-8">
                @foreach($wishlistItems as $item)
                    <div class="wishlist-item h-full flex flex-col" data-id="{{ $item->id }}">
                        <div class="relative overflow-hidden rounded-lg aspect-[3/4] mb-3 bg-gray-100">
                            <!-- Sale or New Tag -->
                            @if(isset($item->product->sale_price) && $item->product->sale_price)
                                <div class="absolute top-2 left-2 z-10 bg-black text-white text-xs font-medium px-2 py-1 rounded-full">
                                    SALE
                                </div>
                            @elseif(isset($item->product->is_new) && $item->product->is_new)
                                <div class="absolute top-2 left-2 z-10 bg-black text-white text-xs font-medium px-2 py-1 rounded-full">
                                    NEW
                                </div>
                            @endif
                            
                            <!-- Wishlist Remove Button -->
                            <button type="button" class="remove-wishlist-btn absolute top-2 right-2 z-10 bg-white w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-all duration-300 hover:bg-gray-100" data-id="{{ $item->id }}">
                                <i class="ri-heart-fill text-lg"></i>
                            </button>
                            
                            <a href="{{ route('products.show', $item->product->slug ?? $item->product->id) }}" class="block">
                                @if($item->product->images && $item->product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $item->product->images->first()->path) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $item->product->name }}"
                                         class="w-full h-full object-cover">
                                @endif
                            </a>
                        </div>
                        
                        <div class="product-info flex flex-col flex-grow min-h-[120px]">
                            <div class="mb-1">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-sm font-medium text-black/80 truncate max-w-[70%]">
                                        <a href="{{ route('products.show', $item->product->slug ?? $item->product->id) }}" 
                                           class="hover:text-black transition-colors">{{ $item->product->name }}</a>
                                    </h3>
                                    @if(isset($item->product->category) && $item->product->category)
                                        <span class="text-xs uppercase tracking-wider text-black/50 truncate ml-1">
                                            {{ $item->product->category->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex flex-col">
                                    @if(isset($item->product->sale_price) && $item->product->sale_price)
                                        <span class="font-medium text-sm">${{ number_format($item->product->sale_price, 2) }}</span>
                                        <span class="text-xs text-black/40 line-through">${{ number_format($item->product->price, 2) }}</span>
                                    @else
                                        <span class="font-medium text-sm">${{ number_format($item->product->price, 2) }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <i class="ri-star-fill text-black text-xs"></i>
                                    <span class="text-xs ml-1">4.8</span>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap items-center mb-2">
                                <button type="button" class="add-notes-btn text-xs text-black/50 hover:text-black transition-colors flex items-center">
                                    <i class="ri-add-line mr-1"></i> Add notes
                                </button>
                                
                                @if($item->notes)
                                    <div class="mt-1 w-full px-2 py-1 bg-gray-100 rounded-md text-xs text-black/70 truncate">
                                        {{ Str::limit($item->notes, 20) }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-auto">
                                <button type="button" data-id="{{ $item->id }}" class="add-to-cart-btn w-full bg-black text-white hover:bg-black/80 transition-colors duration-300 py-2 rounded-lg font-medium text-sm">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                        
                        <!-- Notes Modal (hidden by default) -->
                        <div class="notes-modal fixed inset-0 bg-black bg-opacity-30 z-50 hidden flex items-center justify-center">
                            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Product Notes</h3>
                                    <button type="button" class="close-modal text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="ri-close-line text-xl"></i>
                                    </button>
                                </div>
                                <form class="update-notes-form">
                                    <input type="hidden" name="wishlist_id" value="{{ $item->id }}">
                                    <textarea name="notes" class="w-full border border-gray-300 rounded-lg p-3 mb-4 focus:ring-1 focus:ring-black focus:border-black transition-all" rows="3" placeholder="Add your notes here...">{{ $item->notes }}</textarea>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" class="close-modal px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">Cancel</button>
                                        <button type="submit" class="px-4 py-2 bg-black text-white rounded-lg hover:bg-black/80 transition">Save Notes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="inline-block p-6 rounded-full bg-secondary mb-6">
                    <i class="ri-heart-line text-5xl text-gray-400"></i>
                </div>
                <h2 class="text-2xl font-medium text-primary mb-3">Your wishlist is empty</h2>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">Save items you love by clicking the heart icon on products you like</p>
                <a href="/shop" class="inline-flex items-center bg-black text-white py-3 px-8 rounded-lg hover:bg-black/80 transition duration-300">
                    <span>Explore Products</span>
                    <i class="ri-arrow-right-line ml-2"></i>
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed bottom-6 right-6 z-50"></div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const headers = {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    };
    
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const wishlistId = this.getAttribute('data-id');
            const button = this;
            const originalText = button.innerHTML;
            
            button.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Adding...';
            button.disabled = true;
            
            fetch(`/wishlist/${wishlistId}/move-to-cart`, {
                method: 'POST',
                headers: headers
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const wishlistItem = button.closest('.wishlist-item');
                    wishlistItem.style.opacity = '0';
                    wishlistItem.style.transform = 'translateY(10px)';
                    wishlistItem.style.transition = 'all 0.3s ease';
                    
                    setTimeout(() => {
                        wishlistItem.remove();
                        updateItemCount(-1);
                        showToast('Item added to cart', 'success');
                        
                        if (document.querySelectorAll('.wishlist-item').length === 0) {
                            location.reload();
                        }
                    }, 300);
                } else {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    showToast(data.message || 'Failed to add to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                button.innerHTML = originalText;
                button.disabled = false;
                showToast('An error occurred', 'error');
            });
        });
    });
    
    document.querySelectorAll('.remove-wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const wishlistId = this.getAttribute('data-id');
            const wishlistItem = this.closest('.wishlist-item');
            
            fetch(`/wishlist/${wishlistId}`, {
                method: 'DELETE',
                headers: headers
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    wishlistItem.style.opacity = '0';
                    wishlistItem.style.transform = 'translateY(10px)';
                    wishlistItem.style.transition = 'all 0.3s ease';
                    
                    setTimeout(() => {
                        wishlistItem.remove();
                        updateItemCount(-1);
                        showToast('Item removed from wishlist', 'success');
                        
                        if (document.querySelectorAll('.wishlist-item').length === 0) {
                            location.reload();
                        }
                    }, 300);
                } else {
                    showToast(data.message || 'Failed to remove item', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred', 'error');
            });
        });
    });
    
    document.getElementById('clear-wishlist')?.addEventListener('click', function() {
        if (confirm('Are you sure you want to clear your wishlist?')) {
            fetch('/wishlist/clear', {
                method: 'POST',
                headers: headers
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast('Wishlist cleared successfully', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    showToast(data.message || 'Failed to clear wishlist', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred', 'error');
            });
        }
    });
    
    document.querySelectorAll('.add-notes-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.wishlist-item').querySelector('.notes-modal');
            modal.classList.remove('hidden');
        });
    });
    
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.notes-modal');
            modal.classList.add('hidden');
        });
    });
    
    document.querySelectorAll('.update-notes-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const wishlistId = this.querySelector('input[name="wishlist_id"]').value;
            const notes = this.querySelector('textarea[name="notes"]').value;
            const modal = this.closest('.notes-modal');
            const wishlistItem = this.closest('.wishlist-item');
            
            fetch(`/wishlist/${wishlistId}/update-notes`, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({
                    notes: notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    modal.classList.add('hidden');
                    
                    let notesDisplay = wishlistItem.querySelector('.mt-1.w-full.px-2.py-1.bg-gray-100');
                    if (notes.trim()) {
                        if (notesDisplay) {
                            notesDisplay.textContent = notes.length > 20 ? notes.substring(0, 20) + '...' : notes;
                        } else {
                            const addNotesBtn = wishlistItem.querySelector('.add-notes-btn');
                            notesDisplay = document.createElement('div');
                            notesDisplay.className = 'mt-1 w-full px-2 py-1 bg-gray-100 rounded-md text-xs text-black/70 truncate';
                            notesDisplay.textContent = notes.length > 20 ? notes.substring(0, 20) + '...' : notes;
                            addNotesBtn.parentNode.appendChild(notesDisplay);
                        }
                    } else if (notesDisplay) {
                        notesDisplay.remove();
                    }
                    
                    showToast('Notes updated successfully', 'success');
                } else {
                    showToast(data.message || 'Failed to update notes', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred', 'error');
            });
        });
    });
    
    function updateItemCount(change) {
        const countElement = document.querySelector('.text-gray-700 span');
        if (!countElement) return;
        
        const currentCount = parseInt(countElement.textContent) + change;
        countElement.textContent = currentCount;
        
        const wishlistCounters = document.querySelectorAll('.wishlist-count');
        wishlistCounters.forEach(counter => {
            const count = parseInt(counter.textContent || '0') + change;
            counter.textContent = count;
            
            if (count > 0) {
                counter.classList.remove('hidden');
            } else {
                counter.classList.add('hidden');
            }
        });
        
        const nextSibling = countElement.nextSibling;
        if (nextSibling && nextSibling.nodeType === Node.TEXT_NODE) {
            nextSibling.nodeValue = ' ' + (currentCount === 1 ? 'item' : 'items');
        }
    }
    
    function showToast(message, type = 'info') {
        let container = document.getElementById('toast-container');
        
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed bottom-6 right-6 z-50';
            document.body.appendChild(container);
        }
        
        const toast = document.createElement('div');
        toast.className = 'px-4 py-3 rounded-lg shadow-lg mb-3 flex items-center justify-between transform translate-y-2 opacity-0 transition-all';
        
        switch (type) {
            case 'success':
                toast.classList.add('bg-green-500', 'text-white');
                break;
            case 'error':
                toast.classList.add('bg-red-500', 'text-white');
                break;
            default:
                toast.classList.add('bg-black', 'text-white');
        }
        
        toast.innerHTML = `
            <span>${message}</span>
            <button class="ml-4" onclick="this.parentElement.remove()">
                <i class="ri-close-line"></i>
            </button>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.remove('translate-y-2', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        }, 10);
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    }
    
    // Handle existing session messages
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
    
    @if(session('info'))
        showToast('{{ session('info') }}', 'info');
    @endif
});
</script>

<style>
    .wishlist-item {
        transition: transform 0.3s ease, opacity 0.3s ease;
    }
    
    .remove-wishlist-btn .ri-heart-fill,
    .remove-wishlist-btn.active .ri-heart-fill {
        display: block;
    }
    
    .notes-modal {
        opacity: 1;
        transition: opacity 0.3s ease;
    }
    
    #toast-container > div {
        transition: all 0.3s ease;
        opacity: 1;
    }
    
    @media (max-width: 767px) {
        .product-info {
            min-height: 150px;
        }
    }
    
    .product-info h3 {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .add-notes-btn + div {
        max-width: 100%;
    }
</style>