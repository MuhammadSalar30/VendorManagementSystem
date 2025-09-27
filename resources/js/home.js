/*
Template Name: Yum - Multipurpose Food Tailwind CSS Template
Version: 1.0
Author: coderthemes
Email: support@coderthemes.com
*/

import Swiper from "swiper";
import 'swiper/swiper-bundle.css';
import { Thumbs, FreeMode, Navigation } from "swiper/modules";
import { applyFilters } from './filter-utils';

document.addEventListener('DOMContentLoaded', () => {
    // Update price utility used by product cards
    window.updatePrice = (productId, price) => {
        const priceElement = document.getElementById(`price-${productId}`);
        if (priceElement) {
            priceElement.textContent = `Rs ${parseInt(price).toLocaleString()}`;
        }
    };

    // Select size helper: update active state and price
    window.selectSize = (buttonEl, productId, price) => {
        // Scope to nearest group to avoid cross-card interference
        const group = buttonEl.closest('.size-buttons') || buttonEl.parentElement;
        if (group) {
            group.querySelectorAll('.size-button').forEach((b) => {
                b.classList.remove('bg-primary', 'text-white', 'border-primary');
                b.classList.add('border-default-200', 'text-default-800');
            });
            buttonEl.classList.add('bg-primary', 'text-white', 'border-primary');
            buttonEl.classList.remove('border-default-200', 'text-default-800');
        }
        window.updatePrice(productId, price);
    };

    // Quantity controls for home product cards
    window.updateQuantity = (productId, change) => {
        const input = document.getElementById(`quantity-${productId}`);
        if (!input) return;
        const current = parseInt(input.value || '1');
        const next = Math.max(1, Math.min(100, current + change));
        input.value = String(next);
    };

    // Add to cart for home product cards
    window.addToCart = (productId) => {
        const qtyInput = document.getElementById(`quantity-${productId}`);
        const quantity = Math.max(1, parseInt(qtyInput?.value || '1'));

        // detect selected size in the card
        let size = null;
        const card = qtyInput?.closest('.order-3');
        if (card) {
            const activeSizeBtn = card.querySelector('.size-buttons .size-button.bg-primary');
            if (activeSizeBtn) {
                size = (activeSizeBtn.textContent || '').trim();
            }
        }

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrf) { alert('CSRF token not found. Please refresh the page.'); return; }

        fetch('/cart/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ menu_item_id: productId, quantity, size })
        })
        .then(r => { if (!r.ok) throw new Error(`HTTP ${r.status}`); return r.json(); })
        .then(data => {
            if (data.success) {
                if (typeof updateCartCount === 'function') { updateCartCount(data.cart_count); }
                if (typeof loadCartCount === 'function') { loadCartCount(); }
                if (typeof showAddToCartToast === 'function') { showAddToCartToast(); }
            } else {
                alert(data.message || 'Failed to add to cart');
            }
        })
        .catch(err => {
            console.error('Add to cart error:', err);
            alert('There was an error adding the item to cart. Please try again.');
        });
    };

    // Category click -> navigate with categories param (server renders)
    const categoryItems = document.querySelectorAll('.category-item');
    categoryItems.forEach((item) => {
        item.addEventListener('click', () => {
            const categoryId = item.getAttribute('data-category-id');
            applyFilters([categoryId]);
        });
    });

    // Delivery / Pickup filter -> set order_type query param and reload
    const orderTypeButtons = document.querySelectorAll('.order-type-button');
    orderTypeButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const orderType = button.getAttribute('data-order-type'); // 'delivery' | 'pickup'
            const url = new URL(window.location.href);
            const params = url.searchParams;
            const current = params.get('order_type');
            if (current === orderType) {
                // Toggle off -> remove filter
                params.delete('order_type');
            } else {
                params.set('order_type', orderType);
            }
            url.search = params.toString();
            window.location.href = url.toString();
        });
    });

    // Home search form handler (if present)
    const form = document.getElementById('homeSearchForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const q = (document.getElementById('homeSearchInput')?.value || '').trim();
            const url = new URL(window.location.origin + (window.routes && window.routes.productGrid ? window.routes.productGrid : '/client/product-grid'));
            const params = new URLSearchParams(window.location.search);
            if (q) { params.set('q', q); } else { params.delete('q'); }
            url.search = params.toString();
            window.location.href = url.toString();
        });
    }
});
document.addEventListener('DOMContentLoaded', () => {
    const swiper = new Swiper('.categories-swiper', {
        modules: [Navigation],
        slidesPerView: 6,
        spaceBetween: 20,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        scrollbar: {
            hide: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 15,
            },
            1024: {
                slidesPerView: 6,
                spaceBetween: 20,
            },
        },
    });
});
//

function menuSwiper() {
    var swiper = new Swiper(".menu-swiper", {
        modules: [Thumbs, FreeMode, Navigation],
        spaceBetween: 12,
        freeMode: true,
        loop: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },

        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },

        breakpoints: {
            320: {
                slidesPerView: 1,
            },

            768: {
                slidesPerView: 2,

            },
            1300: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
        },
    });
}

menuSwiper();

//
function clientsSwiper() {
    const clientsNavSlider = new Swiper(".clients-testimonial-pagination", {
        loop: false,
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
        modules: [Thumbs, FreeMode],
    });

    const clientsThumbnailSlider = new Swiper(".clients-testimonial", {
        modules: [Thumbs, FreeMode],
        loop: true,
        spaceBetween: 24,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: clientsNavSlider,
        },
    });
}

clientsSwiper();


