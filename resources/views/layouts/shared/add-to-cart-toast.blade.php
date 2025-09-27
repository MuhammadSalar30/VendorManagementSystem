<div id="bottomCartBar" class="fixed left-0 right-0 bottom-0 z-40">
    <div class="container">
      <div class="mx-auto mb-4 flex items-center justify-center">
        <button id="bottomCartButton" type="button" onclick="openCartDrawer()" class="flex items-center gap-3 bg-primary text-white rounded-full shadow-lg px-6 py-3 hidden">
          <i data-lucide="shopping-bag" class="h-5 w-5"></i>
          <span class="text-sm font-medium">View Cart</span>
          <span class="ml-2 inline-flex items-center justify-center p-1 h-5 w-5 text-xs font-bold bg-white/20 rounded-full"><span class="cart-count">0</span></span>
        </button>
      </div>
    </div>
  </div>

  <div id="cartDrawer" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/30" onclick="closeCartDrawer()"></div>
    <div class="absolute right-0 top-0 h-full w-full sm:w-[420px] bg-white shadow-xl animate-slide-in">
      <div id="cartDrawerBody" class="h-full"></div>
    </div>
  </div>

  <style>
  @keyframes slideIn { from { transform: translateX(100%);} to { transform: translateX(0);} }
  .animate-slide-in { animation: slideIn .25s ease-out; }
  </style>
<script>
    // Global cart state management
    window.cartState = {
        count: 0,
        items: [],
        subtotal: 0,
        initialized: false,
        pendingUpdates: 0,
        lastCartOpen: 0
    };

    // Utility function to safely parse numbers
    function safeParseInt(value, defaultValue = 0) {
        if (value === null || value === undefined) return defaultValue;
        const parsed = parseInt(value);
        return isNaN(parsed) ? defaultValue : parsed;
    }

    function safeParseFloat(value, defaultValue = 0) {
        if (value === null || value === undefined) return defaultValue;
        const parsed = parseFloat(value);
        return isNaN(parsed) ? defaultValue : parsed;
    }

    // Function to immediately update cart count without waiting for server
    function updateCartCountImmediately(change = 0) {
        if (change !== 0) {
            const currentCount = safeParseInt(window.cartState.count, 0);
            const changeAmount = safeParseInt(change, 0);
            window.cartState.count = Math.max(0, currentCount + changeAmount);
        }

        updateBottomCartBar();
        updateNavbarCartCount();
    }

    function updateGlobalCartState(forceRefresh = false) {
        if (window.cartState.pendingUpdates > 0 && !forceRefresh) {
            return;
        }

        fetch('/cart/count?' + new URLSearchParams({ _: Date.now() }))
            .then(r => {
                if (!r.ok) throw new Error('Cart count endpoint not available');
                return r.json();
            })
            .then(data => {
                const newCount = safeParseInt(data.count, 0);
                window.cartState.count = newCount;
                window.cartState.initialized = true;
                window.cartState.pendingUpdates = 0;
                syncAllCartComponents();
            })
            .catch(error => {
                fetch('/cart/data?' + new URLSearchParams({ _: Date.now() }))
                    .then(r => r.json())
                    .then(data => {
                        const count = safeParseInt(data.count, 0);
                        window.cartState.count = count;
                        window.cartState.items = data.items || [];
                        window.cartState.subtotal = safeParseInt(data.subtotal, 0);
                        window.cartState.initialized = true;
                        window.cartState.pendingUpdates = 0;
                        syncAllCartComponents();
                    })
                    .catch(error2 => {
                        calculateCartFromDOM();
                    });
            });
    }

    // FIXED: Enhanced DOM calculation that reads from the actual side cart content
    function calculateCartFromDOM() {
        let count = 0;
        let subtotal = 0;

        // First try to read from the side cart if it's open and has content
        const cartItems = document.querySelectorAll('#cartDrawer [data-cart-item-id]');

        if (cartItems.length > 0) {
            // Calculate from the actual side cart content
            cartItems.forEach(item => {
                const qtyInput = item.querySelector('input[type="text"]');
                const price = safeParseFloat(item.getAttribute('data-unit-price'), 0);
                const quantity = safeParseInt(qtyInput?.value, 0);

                count += quantity;
                subtotal += price * quantity;
            });
        } else {
            // If side cart is empty, try to get count from navbar or use current state
            const navbarCountEl = document.querySelector('.navbar-cart-count, .cart-badge, #cartCount, .cart-count');
            if (navbarCountEl) {
                count = safeParseInt(navbarCountEl.textContent, window.cartState.count);
            } else {
                count = window.cartState.count; // Fallback to current state
            }
        }

        window.cartState.count = count;
        window.cartState.subtotal = subtotal;
        window.cartState.initialized = true;
        syncAllCartComponents();
    }

    function syncAllCartComponents() {
        if (!window.cartState.initialized) return;

        updateBottomCartBar();
        updateNavbarCartCount();
        updateCheckoutPage();
    }

    function updateBottomCartBar() {
        const count = window.cartState.count;
        const bottomCartButton = document.getElementById('bottomCartButton');
        const cartCountEls = document.querySelectorAll('#bottomCartBar .cart-count, .cart-count');

        cartCountEls.forEach(el => {
            if (el.textContent !== count.toString()) {
                el.textContent = count;
            }
        });

        if (bottomCartButton) {
            if (count > 0) {
                bottomCartButton.classList.remove('hidden');
            } else {
                bottomCartButton.classList.add('hidden');
            }
        }
    }

    function updateNavbarCartCount() {
        const navbarCountEls = document.querySelectorAll('.navbar-cart-count, .cart-badge, #cartCount');
        const count = window.cartState.count;

        navbarCountEls.forEach(el => {
            if (el.textContent !== count.toString()) {
                el.textContent = count;
                el.style.display = count > 0 ? 'inline-block' : 'none';
            }
        });
    }

    function updateCheckoutPage() {
        if (window.location.pathname.includes('/checkout')) {
            localStorage.setItem('cartLastUpdated', Date.now());
            const cartUpdateEvent = new CustomEvent('cartUpdated', {
                detail: { count: window.cartState.count, timestamp: Date.now() }
            });
            window.dispatchEvent(cartUpdateEvent);

            if (typeof window.refreshCheckoutCart === 'function') {
                window.refreshCheckoutCart();
            }
        }
    }

    window.showAddToCartToast = function() {
        updateCartCountImmediately(1);

        const bottomCartButton = document.getElementById('bottomCartButton');
        if (bottomCartButton) {
            bottomCartButton.classList.remove('hidden');
        }

        const existing = document.getElementById('inlineCartNotice');
        if (existing) existing.remove();

        const notice = document.createElement('div');
        notice.id = 'inlineCartNotice';
        notice.className = 'fixed bottom-24 left-1/2 -translate-x-1/2 z-50 bg-white border border-default-200 rounded-full shadow px-4 py-2 text-sm text-default-800';
        notice.textContent = 'Item added successfully';
        document.body.appendChild(notice);

        setTimeout(() => { notice.remove(); }, 1800);

        setTimeout(() => {
            updateGlobalCartState(true);
        }, 800);
    };

    function updateSideQuantity(cartItemId, change) {
        const row = document.querySelector(`#cartDrawer [data-cart-item-id="${cartItemId}"]`);
        if (!row) return;

        const input = row.querySelector('input[type="text"]');
        const current = safeParseInt(input.value, 1);
        const next = Math.max(1, Math.min(100, current + change));

        if (next === current) return;

        window.cartState.pendingUpdates++;

        input.value = next;
        const unit = safeParseFloat(row.getAttribute('data-unit-price'), 0);
        const rowTotal = row.querySelector('.row-total');
        if (rowTotal) {
            rowTotal.textContent = `Rs ${Number(unit * next).toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}`;
        }

        updateCartCountImmediately(change);

        const leftBtn = row.querySelector('.inline-flex > button:first-child');
        if (leftBtn) {
            if (next === 1) {
                leftBtn.className = 'flex-shrink-0 bg-red-100 text-red-600 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center';
                leftBtn.innerHTML = '<i data-lucide="trash-2" class="w-4 h-4"></i>';
                leftBtn.onclick = function(){ confirmRemoveFromSideCart(cartItemId); };
                if (window.lucide && typeof window.lucide.createIcons === 'function') window.lucide.createIcons();
            } else {
                leftBtn.className = 'minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center';
                leftBtn.textContent = '–';
                leftBtn.onclick = function(){ updateSideQuantity(cartItemId, -1); };
            }
        }

        if (window.isLoggedIn) {
            fetch(`/cart/${cartItemId}/quantity`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ quantity: next })
            })
            .then(r => r.json())
            .then(data => {
                window.cartState.pendingUpdates--;

                if (!data.success) {
                    input.value = current;
                    if (rowTotal) {
                        rowTotal.textContent = `Rs ${Number(unit * current).toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}`;
                    }
                    updateCartCountImmediately(-change);
                } else {
                    setTimeout(() => updateGlobalCartState(true), 300);
                }
            })
            .catch(error => {
                window.cartState.pendingUpdates--;
                input.value = current;
                if (rowTotal) {
                    rowTotal.textContent = `Rs ${Number(unit * current).toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}`;
                }
                updateCartCountImmediately(-change);
            });
        } else {
            let cart = JSON.parse(localStorage.getItem('guestCart') || '{}');
            if (cart[cartItemId]) {
                cart[cartItemId].quantity = next;
                localStorage.setItem('guestCart', JSON.stringify(cart));
            }
        }
    }

    function removeFromSideCart(cartItemId) {
        const row = document.querySelector(`#cartDrawer [data-cart-item-id="${cartItemId}"]`);
        let quantityToRemove = 0;

        if (row) {
            const input = row.querySelector('input[type="text"]');
            quantityToRemove = safeParseInt(input.value, 1);
        }

        updateCartCountImmediately(-quantityToRemove);
        window.cartState.pendingUpdates++;

        if (window.isLoggedIn) {
            fetch(`/cart/${cartItemId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            })
            .then(r => r.json())
            .then(data => {
                window.cartState.pendingUpdates--;

                if (data.success) {
                    if (row) row.remove();
                    setTimeout(() => updateGlobalCartState(true), 300);
                } else {
                    updateCartCountImmediately(quantityToRemove);
                }
            })
            .catch(error => {
                window.cartState.pendingUpdates--;
                updateCartCountImmediately(quantityToRemove);
            });
        } else {
            let cart = JSON.parse(localStorage.getItem('guestCart') || '{}');
            if (cart[cartItemId]) {
                delete cart[cartItemId];
                localStorage.setItem('guestCart', JSON.stringify(cart));
            }
            if (row) row.remove();
        }
    }

    function recalcSideSubtotal() {
        let subtotal = 0;
        let count = 0;

        document.querySelectorAll('#cartDrawer [data-cart-item-id]').forEach(function(r){
            const qty = safeParseInt(r.querySelector('input[type="text"]').value, 1);
            const price = safeParseFloat(r.getAttribute('data-unit-price'), 0);
            subtotal += qty * price;
            count += qty;
        });

        const subEl = document.getElementById('side-subtotal');
        if (subEl) {
            subEl.textContent = `Rs ${subtotal.toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}`;
        }

        // FIXED: Also update the global count when recalculating from side cart
        if (count !== window.cartState.count) {
            window.cartState.count = count;
            window.cartState.subtotal = subtotal;
            syncAllCartComponents();
        }
    }

    window.bumpCartCount = function(amount) {
        updateCartCountImmediately(amount);
    };

    // FIXED: Completely rewritten openCartDrawer function
    function openCartDrawer() {
        const drawer = document.getElementById('cartDrawer');
        const body = document.getElementById('cartDrawerBody');
        if (!drawer || !body) return;

        const now = Date.now();
        if (now - window.cartState.lastCartOpen < 500) {
            return;
        }
        window.cartState.lastCartOpen = now;

        drawer.classList.remove('hidden');

        // Show loading state
        body.innerHTML = `
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
                    <p class="text-default-500 mt-2">Loading cart...</p>
                </div>
            </div>
        `;

        // FIXED: Use a completely fresh request with no caching
        fetch('/cart/side?' + new URLSearchParams({
            t: now, // Use timestamp as parameter
            rand: Math.random().toString(36).substring(7) // Random string to bust cache
        }), {
            method: 'GET',
            cache: 'no-cache',
            headers: {
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            // FIXED: Clear and set HTML content
            body.innerHTML = html;

            // Reinitialize icons after a short delay to ensure DOM is ready
            setTimeout(() => {
                if (window.lucide && typeof window.lucide.createIcons === 'function') {
                    window.lucide.createIcons();
                }

                // FIXED: Calculate from the loaded DOM content immediately
                recalcSideSubtotal();

                // FIXED: Force a server sync to ensure accuracy
                updateGlobalCartState(true);
            }, 100);
        })
        .catch(error => {
            console.error('Error loading cart:', error);
            body.innerHTML = '<div class="p-6 text-center">Failed to load cart. Please try again.</div>';
            // FIXED: Even on error, try to calculate from any existing DOM
            calculateCartFromDOM();
        });
    }

    function closeCartDrawer() {
        const drawer = document.getElementById('cartDrawer');
        if (drawer) {
            drawer.classList.add('hidden');
            // FIXED: Force update after closing to sync with any changes made
            setTimeout(() => {
                updateGlobalCartState(true);
            }, 200);
        }
    }

    function confirmRemoveFromSideCart(cartItemId) {
        if (!window.confirm('Are you sure you want to remove this product?')) return;
        removeFromSideCart(cartItemId);
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function(){
        // FIXED: Wait for page to be fully ready before initializing cart
        setTimeout(() => {
            updateGlobalCartState(true);
        }, 1500); // Increased delay to ensure all elements are loaded

        // FIXED: More frequent but gentle sync
        setInterval(() => {
            if (window.cartState.initialized) {
                // Only sync if cart drawer is closed to avoid conflicts
                const drawer = document.getElementById('cartDrawer');
                if (!drawer || drawer.classList.contains('hidden')) {
                    updateGlobalCartState(false);
                }
            }
        }, 5000); // Every 5 seconds when cart is closed
    });

    // FIXED: Add event listener for cart drawer state changes
    document.addEventListener('DOMContentLoaded', function() {
        // Observe cart drawer for open/close state changes
        const drawer = document.getElementById('cartDrawer');
        if (drawer) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        if (drawer.classList.contains('hidden')) {
                            // Cart was just closed - force a sync
                            setTimeout(() => updateGlobalCartState(true), 300);
                        }
                    }
                });
            });

            observer.observe(drawer, { attributes: true });
        }
    });
</script>
