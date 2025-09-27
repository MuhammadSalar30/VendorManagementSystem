@extends('layouts.default', ['title' => 'Checkout'])

@section('content')

@include('layouts.shared/page-title', ['title' => 'Checkout'] )

<section class="lg:py-10 py-6">
    <div class="container">
        <form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}">
            @csrf
            <div class="grid lg:grid-cols-3 grid-cols-1 gap-6">
                <div class="lg:col-span-2 col-span-1">
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-default-800 mb-6">Customer Information</h4>

                        <div class="grid lg:grid-cols-4 gap-6">
                            <div class="lg:col-span-2">
                                <label for="customer_name" class="block text-sm text-default-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                                <input id="customer_name" name="customer_name" class="block w-full bg-transparent dark:bg-default-50 rounded-full py-2.5 px-4 border border-default-200" type="text" placeholder="Enter your full name" value="{{ auth()->user()->name ?? '' }}" required>
                                @error('customer_name')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="lg:col-span-2">
                                <label for="customer_email" class="block text-sm text-default-700 mb-2">Email <span class="text-red-500">*</span></label>
                                <input id="customer_email" name="customer_email" class="block w-full bg-transparent dark:bg-default-50 rounded-full py-2.5 px-4 border border-default-200" type="email" placeholder="example@example.com" value="{{ auth()->user()->email ?? '' }}" required>
                                @error('customer_email')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="lg:col-span-2">
                                <label for="customer_phone" class="block text-sm text-default-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                                <input id="customer_phone" name="customer_phone" class="block w-full bg-transparent dark:bg-default-50 rounded-full py-2.5 px-4 border border-default-200" type="text" placeholder="+92 300-1234567" required>
                                @error('customer_phone')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="lg:col-span-2">
                                <label for="order_type" class="block text-sm text-default-700 mb-2">Order Type <span class="text-red-500">*</span></label>
                                <select id="order_type" name="order_type" class="block w-full bg-transparent dark:bg-default-50 rounded-full py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200" required>
                                    <option value="delivery" selected>Delivery</option>
                                    <option value="takeaway">Takeaway</option>
                                    <option value="dine-in">Dine In</option>
                                </select>
                                @error('order_type')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Delivery Address Section (shown only for delivery) -->
                            <div id="delivery_section" class="lg:col-span-4">
                                <h5 class="text-md font-medium text-default-800 mb-4">Delivery Address</h5>

                                <div class="grid lg:grid-cols-3 gap-4">
                                    <div class="lg:col-span-3">
                                        <label for="delivery_address" class="block text-sm text-default-700 mb-2">Complete Address <span class="text-red-500">*</span></label>
                                        <textarea id="delivery_address" name="delivery_address" class="block w-full bg-transparent dark:bg-default-50 rounded-lg py-2.5 px-4 border border-default-200" rows="3" placeholder="House/Flat No, Street, Landmark"></textarea>
                                        @error('delivery_address')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="delivery_area" class="block text-sm text-default-700 mb-2">Area <span class="text-red-500">*</span></label>
                                        <select id="delivery_area" name="delivery_area" class="block w-full bg-transparent dark:bg-default-50 rounded-full py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200">
                                            <option value="">Select Area</option>
                                            <option value="Gulshan-e-Iqbal">Gulshan-e-Iqbal</option>
                                            <option value="North Nazimabad">North Nazimabad</option>
                                            <option value="Clifton">Clifton</option>
                                            <option value="Defence (DHA)">Defence (DHA)</option>
                                            <option value="Korangi">Korangi</option>
                                            <option value="Malir">Malir</option>
                                            <option value="Saddar">Saddar</option>
                                            <option value="Tariq Road">Tariq Road</option>
                                            <option value="Bahadurabad">Bahadurabad</option>
                                            <option value="Nazimabad">Nazimabad</option>
                                            <option value="Federal B Area">Federal B Area</option>
                                            <option value="Gulberg">Gulberg</option>
                                            <option value="Gulistan-e-Jauhar">Gulistan-e-Jauhar</option>
                                             <option value="Gulshan-e-Iqbal">Gulshan-e-Iqbal</option>
                                             <option value="Scheme 33">Scheme 33</option>
                                            <option value="Gulshan-e-Maymar">Gulshan-e-Maymar</option>
                                            <option value="Shah Faisal">Shah Faisal</option>
                                            <option value="Landhi">Landhi</option>
                                            <option value="Orangi Town">Orangi Town</option>
                                            <option value="Baldia Town">Baldia Town</option>
                                            <option value="SITE">SITE</option>
                                            <option value="New Karachi">New Karachi</option>
                                            <option value="Surjani Town">Surjani Town</option>
                                        </select>
                                        @error('delivery_area')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="delivery_city" class="block text-sm text-default-700 mb-2">City</label>
                                        <input id="delivery_city" name="delivery_city" class="block w-full bg-transparent dark:bg-default-50 rounded-full py-2.5 px-4 border border-default-200 bg-gray-100" type="text" value="Karachi" readonly>
                                    </div>


                                </div>
                            </div>

                            <!-- Table Number Section (shown only for dine-in) -->
                            <div id="table_section" class="lg:col-span-2" style="display: none;">
                                <label for="table_no" class="block text-sm text-default-700 mb-2">Table Number</label>
                                <input id="table_no" name="table_no" class="block w-full bg-transparent dark:bg-default-50 rounded-full py-2.5 px-4 border border-default-200" type="text" placeholder="Enter table number">
                                @error('table_no')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-default-800 mb-6">Payment Option</h4>

                        <div class="border border-default-200 rounded-lg p-6 lg:w-5/6 mb-5">
                            <div class="grid lg:grid-cols-4 grid-cols-2">
                                <div class="text-center p-6">
                                    <label for="paymentCOD" class="flex flex-col items-center justify-center mb-4 cursor-pointer">
                                        <i data-lucide="dollar-sign" class="text-primary mb-4"></i>
                                        <h5 class="text-sm font-medium text-default-700">Cash on Delivery</h5>
                                    </label>
                                    <input id="paymentCOD" class="text-primary w-5 h-5 bg-transparent border-default-200 focus:ring-0" type="radio" name="payment_method" value="cash_on_delivery" checked>
                                </div>

                                <div class="text-center p-6">
                                    <label for="paymentOnline" class="flex flex-col items-center justify-center mb-4 cursor-pointer">
                                        <i data-lucide="credit-card" class="text-primary mb-4"></i>
                                        <h5 class="text-sm font-medium text-default-700">Online Payment</h5>
                                    </label>
                                    <input id="paymentOnline" class="text-primary w-5 h-5 bg-transparent border-default-200 focus:ring-0" type="radio" name="payment_method" value="online_payment">
                                </div>
                            </div>
                        </div>

                        <div id="card_details" class="grid lg:grid-cols-2 gap-6" style="display: none;">
                            <div class="lg:col-span-2">
                                <label for="card_holder_name" class="block text-sm text-default-700 mb-2">Name on Card</label>
                                <input id="card_holder_name" name="card_holder_name" class="block w-full bg-transparent dark:bg-default-50 rounded-full py-2.5 px-4 border border-default-200" type="text" placeholder="Enter card holder name">
                            </div>

                            <div class="lg:col-span-2">
                                <label for="card_number" class="block text-sm text-default-700 mb-2">Card Number</label>
                                <input id="card_number" name="card_number" class="block w-full bg-transparent dark:bg-default-50 rounded-full py-2.5 px-4 border border-default-200" type="text" placeholder="Enter card number">
                            </div>

                            <div>
                                <label for="card_expiry" class="block text-sm text-default-700 mb-2">Expire Date</label>
                                <input id="card_expiry" name="card_expiry" class="block w-full bg-transparent dark:bg-default-50 rounded-full py-2.5 px-4 border border-default-200" type="text" placeholder="MM/YY">
                            </div>

                            <div>
                                <label for="card_cvc" class="block text-sm text-default-700 mb-2">CVC</label>
                                <input id="card_cvc" name="card_cvc" class="block w-full bg-transparent dark:bg-default-50 rounded-full py-2.5 px-4 border border-default-200" type="text" placeholder="Enter CVV number">
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-medium text-default-800 mb-6">Additional Information</h4>

                        <div>
                            <label for="notes" class="block text-sm text-default-700 mb-2">Order Notes <span class="text-default-500">(Optional)</span></label>
                            <textarea id="notes" name="notes" class="block w-full bg-transparent dark:bg-default-50 rounded-lg py-2.5 px-4 border border-default-200" rows="4" placeholder="Notes about your order, e.g. special notes for delivery"></textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="border border-default-200 rounded-lg p-5" id="ordersumray">
                        <h4 class="text-lg font-semibold text-default-700 mb-5">Order Summary</h4>

                        @if(isset($cartItems) && $cartItems->count() > 0)
                            @foreach($cartItems as $item)
                            @if($item->menuItem)
                            <div class="flex items-center mb-4" data-cart-item-id="{{ $item['id'] ?? ($item->id ?? $loop->index) }}" data-unit-price="{{ $item['price'] ?? $item->price }}>
                                <img src="{{ $item->menuItem->image ? (str_starts_with($item->menuItem->image, 'http') ? $item->menuItem->image : asset($item->menuItem->image)) : '/images/dishes/burger.png' }}"
                                     class="h-20 w-20 me-2 rounded-lg object-cover"
                                     alt="{{ $item->menuItem->name }}"
                                     onerror="this.src='/images/dishes/burger.png'">
                                <div class="flex-1">
                                    <h4 class="text-sm text-default-600 mb-1">{{ $item->menuItem->name }}</h4>
                                    @if($item->size)
                                        <p class="text-xs text-default-400 mb-1">Size: {{ ucfirst($item->size) }}</p>
                                    @endif
                                    {{-- <h4 class="text-sm text-default-400">{{ $item->quantity }} x <span class="text-primary font-semibold">Rs. {{ number_format($item->price, 2) }}</span></h4> --}}
                                    <div class="mt-2 flex items-center justify-between">
                                        <div class="inline-flex justify-between border border-default-200 p-1 rounded-full">
                                          @php $qty = $item['quantity'] ?? $item->quantity; @endphp
                                          @if($qty > 1)
                                          <button type="button" class="minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" onclick="updateSideQuantityCheck({{ $item['id'] ?? ($item->id ?? $loop->index) }}, -1)">–</button>
                                          @else
                                          <button type="button" class="flex-shrink-0 bg-red-100 text-red-600 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" onclick="confirmRemoveFromSideCartCheck({{ $item['id'] ?? ($item->id ?? $loop->index) }})" title="Remove"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                          @endif
                                          <input type="text" class="w-8 border-0 text-sm text-center text-default-800 focus:ring-0 p-0 bg-transparent" value="{{ $qty }}" min="1" max="100" readonly>
                                          <button type="button" class="plus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" onclick="updateSideQuantityCheck({{ $item['id'] ?? ($item->id ?? $loop->index) }}, 1)">+</button>
                                        </div>

                                      </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-default-700 row-total">Rs. {{ number_format($item->price * $item->quantity, 0) }}</p>
                                </div>
                            </div>
                            @else
                            <div class="flex items-center mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="text-red-600">
                                    <p class="text-sm">Item not found (ID: {{ $item->menu_item_id }})</p>
                                    <p class="text-xs">This item may have been deleted from the menu.</p>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <p class="text-default-500">Your cart is empty</p>
                                <a href="{{ route('second', ['client', 'home']) }}" class="text-primary hover:underline">Continue Shopping</a>
                            </div>
                        @endif

                        @if(isset($cartItems) && $cartItems->count() > 0)
                        <div class="mb-6">
                            <div class="flex justify-between mb-3">
                                <p class="text-sm text-default-500">Sub-total</p>
                                <p class="text-sm text-default-700 font-medium" id="subtotal">Rs {{ number_format($subtotal ?? 0, 0) }}</p>
                            </div>
                            <div class="flex justify-between mb-3">
                                <p class="text-sm text-default-500">Delivery Fee</p>
                                <p class="text-sm text-default-700 font-medium" id="delivery_fee">Rs {{ number_format($defaultDeliveryCharge ?? 0, 0) }}</p>
                            </div>
                            <div class="flex justify-between mb-3">
                                <p class="text-sm text-default-500">Tax (<span id="tax_rate_label">{{ $taxRate ?? 0 }}</span>%)</p>
                                <p class="text-sm text-default-700 font-medium" id="tax_amount">Rs {{ number_format($taxAmount ?? 0, 0) }}</p>
                            </div>
                            @if(isset($discountAmount) && $discountAmount > 0)
                            <div class="flex justify-between mb-3">
                                <p class="text-sm text-default-500">Discount</p>
                                <p class="text-sm text-default-700 font-medium text-green-600" id="discountamount">-Rs {{ number_format($discountAmount, 2) }}</p>
                            </div>
                            @endif
                            <div class="border-b border-default-200 my-4"></div>
                            <div class="flex justify-between mb-3">
                                <p class="text-base text-default-700 font-semibold">Total</p>
                                <p class="text-base text-default-700 font-semibold " id="total_amount">Rs {{ number_format($total ?? 0, 0) }}</p>
                            </div>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center rounded-full border border-primary bg-primary px-10 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500" id="place_order_btn">
                            <span class="loading-spinner hidden mr-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            <span class="btn-text">Place Order</span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@endsection

@section('script')
<script>
    function parseMoney(text) {
        return parseFloat(String(text).replace(/[^0-9.]/g, '')) || 0;
    }

    function formatMoney(num) {
        return 'Rs. ' + Number(num).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }
<<<<<<< HEAD

    // Global variable to track checkout cart state
    window.checkoutCartState = {
        items: [],
        subtotal: 0,
        count: 0,
        lastUpdate: 0
    };

    // Enhanced event listener for cart updates
    window.addEventListener('cartUpdated', function(event) {
        console.log('Cart updated event received in checkout:', event.detail);
        refreshCheckoutCart();
    });

    // Listen for storage events (cross-tab communication)
    window.addEventListener('storage', function(event) {
        if (event.key === 'cartLastUpdated') {
            console.log('Cart updated via storage event');
            refreshCheckoutCart();
        }
    });

    // Enhanced refresh function
    window.refreshCheckoutData = function() {
        refreshCheckoutCart();
    };

    // Make this function globally accessible
    window.refreshCheckoutCart = function() {
        console.log('Refreshing checkout cart data...');

        // Show loading indicator
        const orderSummary = document.getElementById('ordersumray');
        if (orderSummary) {
            const originalContent = orderSummary.innerHTML;
            orderSummary.innerHTML = `
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
                    <p class="text-default-500 mt-2">Updating cart...</p>
                </div>
            `;
        }

        fetch('/checkout/cart-items?' + new URLSearchParams({ _t: Date.now() }))
            .then(r => {
                if (!r.ok) throw new Error('Failed to fetch cart items');
                return r.json();
            })
            .then(data => {
                if (data.success) {
                    console.log('Cart items fetched successfully:', data.items.length, 'items');
                    updateCheckoutItems(data.items);
                    updateCheckoutTotals(data.totals);
                    window.checkoutCartState = {
                        items: data.items,
                        subtotal: data.totals.subtotal,
                        count: data.totals.count,
                        lastUpdate: Date.now()
                    };
                } else {
                    throw new Error('Invalid response format');
                }
            })
            .catch(error => {
                console.error('Error refreshing checkout cart:', error);
                // Fallback: try to recalculate from current DOM
                recalcCheckoutTotals();

                // Restore original content if update failed
                if (orderSummary && originalContent) {
                    orderSummary.innerHTML = originalContent;
                }
            });
    };

    function updateCheckoutItems(items) {
        console.log('Updating checkout items:', items);

        if (!items || items.length === 0) {
            // If cart is empty, show empty message
            document.getElementById('ordersumray').innerHTML = `
                <h4 class="text-lg font-semibold text-default-700 mb-5">Order Summary</h4>
                <div class="text-center py-8">
                    <p class="text-default-500">Your cart is empty</p>
                    <a href="{{ route('second', ['client', 'home']) }}" class="text-primary hover:underline">Continue Shopping</a>
                </div>
            `;
            return;
        }

        // Generate new items HTML
        let itemsHTML = `
            <h4 class="text-lg font-semibold text-default-700 mb-5">Order Summary</h4>
            <div id="checkout-items-container">
        `;

        items.forEach((item, index) => {
            const itemId = item.id || item.menu_item_id || index;
            const menuItem = item.menu_item || item.menuItem || {};
            const quantity = item.quantity || 1;
            const price = item.price || 0;
            const size = item.size || '';

            // Safely handle image URL
            let imageUrl = '/images/dishes/burger.png';
            if (menuItem.image) {
                imageUrl = menuItem.image.startsWith('http') ? menuItem.image : '{{ asset("") }}' + menuItem.image;
            }

            itemsHTML += `
                <div class="flex items-center mb-4" data-cart-item-id="${itemId}" data-unit-price="${price}">
                    <img src="${imageUrl}"
                         class="h-20 w-20 me-2 rounded-lg object-cover"
                         alt="${menuItem.name || 'Product'}"
                         onerror="this.src='/images/dishes/burger.png'">
                    <div class="flex-1">
                        <h4 class="text-sm text-default-600 mb-1">${menuItem.name || 'Unnamed Item'}</h4>
                        ${size ? `<p class="text-xs text-default-400 mb-1">Size: ${size}</p>` : ''}
                        <div class="mt-2 flex items-center justify-between">
                            <div class="inline-flex justify-between border border-default-200 p-1 rounded-full">
                                ${quantity > 1 ?
                                    `<button type="button" class="minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" onclick="updateSideQuantityCheck(${itemId}, -1)">–</button>` :
                                    `<button type="button" class="flex-shrink-0 bg-red-100 text-red-600 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" onclick="confirmRemoveFromSideCartCheck(${itemId})" title="Remove"><i class="w-4 h-4 fa fa-trash"></i></button>`
                                }
                                <input type="text" class="w-8 border-0 text-sm text-center text-default-800 focus:ring-0 p-0 bg-transparent" value="${quantity}" min="1" max="100" readonly>
                                <button type="button" class="plus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center" onclick="updateSideQuantityCheck(${itemId}, 1)">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-default-700 row-total">Rs. ${(price * quantity).toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}</p>
                    </div>
                </div>
            `;
        });

        itemsHTML += `</div>`;

        // Update the order summary section
        const orderSummary = document.getElementById('ordersumray');
        if (orderSummary) {
            orderSummary.innerHTML = itemsHTML + generateOrderTotalsHTML(items);
        }

        // Reattach event listeners
        attachCheckoutEventListeners();
    }

    function generateOrderTotalsHTML(items) {
        const subtotal = items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const deliveryFeeElement = document.getElementById('delivery_fee');
        const taxRateElement = document.getElementById('tax_rate_label');
        const discountElement = document.getElementById('discountamount');

        const deliveryFee = deliveryFeeElement ? parseMoney(deliveryFeeElement.textContent) : 0;
        const taxRate = taxRateElement ? parseMoney(taxRateElement.textContent) : 0;
        const discountAmount = discountElement ? parseMoney(discountElement.textContent) : 0;

        const taxAmount = (subtotal * taxRate) / 100;
        const total = subtotal + deliveryFee + taxAmount - discountAmount;

        return `
            <div class="mb-6">
                <div class="flex justify-between mb-3">
                    <p class="text-sm text-default-500">Sub-total</p>
                    <p class="text-sm text-default-700 font-medium" id="subtotal">Rs ${subtotal.toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}</p>
                </div>
                <div class="flex justify-between mb-3">
                    <p class="text-sm text-default-500">Delivery Fee</p>
                    <p class="text-sm text-default-700 font-medium" id="delivery_fee">Rs ${deliveryFee.toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}</p>
                </div>
                <div class="flex justify-between mb-3">
                    <p class="text-sm text-default-500">Tax (<span id="tax_rate_label">${taxRate}</span>%)</p>
                    <p class="text-sm text-default-700 font-medium" id="tax_amount">Rs ${taxAmount.toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}</p>
                </div>
                ${discountAmount > 0 ? `
                <div class="flex justify-between mb-3">
                    <p class="text-sm text-default-500">Discount</p>
                    <p class="text-sm text-default-700 font-medium text-green-600" id="discountamount">-Rs ${discountAmount.toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}</p>
                </div>
                ` : ''}
                <div class="border-b border-default-200 my-4"></div>
                <div class="flex justify-between mb-3">
                    <p class="text-base text-default-700 font-semibold">Total</p>
                    <p class="text-base text-default-700 font-semibold" id="total_amount">Rs ${total.toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}</p>
                </div>
            </div>
            <button type="submit" class="w-full inline-flex items-center justify-center rounded-full border border-primary bg-primary px-10 py-3 text-center text-sm font-medium text-white shadow-sm transition-all duration-500 hover:bg-primary-500" id="place_order_btn">
                <span class="loading-spinner hidden mr-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
                <span class="btn-text">Place Order</span>
            </button>
        `;
    }

    function updateCheckoutTotals(totals) {
        if (totals.subtotal) {
            const subtotalEl = document.getElementById('subtotal');
            if (subtotalEl) {
                subtotalEl.textContent = `Rs ${Number(totals.subtotal).toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}`;
            }
        }
        if (totals.total) {
            const totalEl = document.getElementById('total_amount');
            if (totalEl) {
                totalEl.textContent = `Rs ${Number(totals.total).toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}`;
            }
        }
    }

    function attachCheckoutEventListeners() {
        // Reattach event listeners to new DOM elements
        document.querySelectorAll('.plus, .minus').forEach(btn => {
            btn.addEventListener('click', function() {
                const cartItemId = this.closest('[data-cart-item-id]').getAttribute('data-cart-item-id');
                const change = this.classList.contains('plus') ? 1 : -1;
                updateSideQuantityCheck(cartItemId, change);
            });
        });
    }

    // Enhanced update function for checkout page
    function updateSideQuantityCheck(cartItemId, change) {
        const row = document.querySelector(`#ordersumray [data-cart-item-id="${cartItemId}"]`);
        if (!row) return;

        const input = row.querySelector('input[type="text"]');
        const current = parseInt(input.value);
        const next = Math.max(1, Math.min(100, current + change));
        if (next === current) return;

        // Optimistic UI update
        input.value = next;
        const unit = parseFloat(row.getAttribute('data-unit-price')) || 0;
        const rowTotal = row.querySelector('.row-total');
        if (rowTotal) {
            rowTotal.textContent = `Rs ${Number(unit * next).toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}`;
        }

        // Update button states
        const leftBtn = row.querySelector('.inline-flex > button:first-child');
        if (leftBtn) {
            if (next === 1) {
                leftBtn.className = 'flex-shrink-0 bg-red-100 text-red-600 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center';
                leftBtn.innerHTML = '<i class="w-4 h-4 fa fa-trash"></i>';
                leftBtn.onclick = function(){ confirmRemoveFromSideCartCheck(cartItemId); };
            } else {
                leftBtn.className = 'minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center';
                leftBtn.textContent = '–';
                leftBtn.onclick = function(){ updateSideQuantityCheck(cartItemId, -1); };
            }
        }

        // Update server and sync
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
            if (data.success) {
                // Update totals
                recalcCheckoutTotals();
                // Notify side cart about the change
                if (window.updateGlobalCartState) {
                    window.updateGlobalCartState();
                }
                // Also refresh the entire cart to ensure consistency
                setTimeout(() => refreshCheckoutCart(), 300);
            }
        });
    }

    function removeFromSideCartCheck(cartItemId) {
        fetch(`/cart/${cartItemId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Refresh the entire cart section
                refreshCheckoutCart();

                // Notify side cart
                if (window.updateGlobalCartState) {
                    window.updateGlobalCartState();
                }
            }
        });
    }

    function confirmRemoveFromSideCartCheck(cartItemId) {
        if (!window.confirm('Are you sure you want to remove this product?')) return;
        removeFromSideCartCheck(cartItemId);
    }

    // Enhanced recalc function for checkout
    function recalcCheckoutTotals() {
        let subtotal = 0;
        let cartcount = 0;

        document.querySelectorAll('#ordersumray [data-cart-item-id]').forEach(function(r){
            const qty = parseInt(r.querySelector('input[type="text"]').value || '1');
            const price = parseFloat(r.getAttribute('data-unit-price')) || 0;
            subtotal += qty * price;
            cartcount += qty;
        });

        const subEl = document.getElementById('subtotal');
        if (subEl){
            subEl.textContent = `Rs ${subtotal.toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}`;

            var delivery_fee = parseMoney($('#delivery_fee').text());
=======
document.addEventListener('DOMContentLoaded', function() {
    $('#bottomCartBar').hide();
    const orderTypeSelect = document.getElementById('order_type');
    const deliverySection = document.getElementById('delivery_section');
    const tableSection = document.getElementById('table_section');
    const deliveryAddressField = document.getElementById('delivery_address');
    const deliveryAreaField = document.getElementById('delivery_area');
    const tableNoField = document.getElementById('table_no');

    const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
    const cardDetailsSection = document.getElementById('card_details');

    const subtotalEl = document.getElementById('subtotal');
    const deliveryFeeEl = document.getElementById('delivery_fee');
    const taxAmountEl = document.getElementById('tax_amount');
    const taxRateLabel = document.getElementById('tax_rate_label');
    const totalAmountEl = document.getElementById('total_amount');



    function recalcTotals(taxRate) {
        const subtotal = parseMoney(subtotalEl.textContent);
        const delivery = parseMoney(deliveryFeeEl.textContent);
        const tax = (subtotal * taxRate) /  100;
        // const tax = (subtotal + delivery) * (taxRate / 100);
        taxAmountEl.textContent = formatMoney(tax);
        totalAmountEl.textContent = formatMoney(subtotal + delivery + tax);
    }

    // Handle order type change
    async function handleOrderTypeChange() {
        const orderType = orderTypeSelect.value;

        if (orderType === 'delivery') {
            deliverySection.style.display = 'block';
            tableSection.style.display = 'none';
            deliveryAddressField.required = true;
            deliveryAreaField.required = true;
            tableNoField.required = false;
            await refreshCharges();
        } else if (orderType === 'dine-in') {
            deliverySection.style.display = 'none';
            tableSection.style.display = 'block';
            deliveryAddressField.required = false;
            deliveryAreaField.required = false;
            tableNoField.required = true;
            // No delivery for dine-in
            deliveryFeeEl.textContent = formatMoney(0);
            // Tax depends on payment method only
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;
            const taxRate = await fetchTaxRate(selectedPayment);
            taxRateLabel.textContent = taxRate;
            recalcTotals(taxRate);
            await refreshCharges();
        } else { // takeaway
            deliverySection.style.display = 'none';
            tableSection.style.display = 'none';
            deliveryAddressField.required = false;
            deliveryAreaField.required = false;
            tableNoField.required = false;
            deliveryFeeEl.textContent = formatMoney(0);
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;
            const taxRate = await fetchTaxRate(selectedPayment);
            taxRateLabel.textContent = taxRate;
            recalcTotals(taxRate);
            await refreshCharges();
        }
    }

    // Handle payment method change
    async function handlePaymentMethodChange() {
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;

        if (selectedPayment === 'online_payment') {
            cardDetailsSection.style.display = 'grid';
            document.getElementById('card_holder_name').required = true;
            document.getElementById('card_number').required = true;
            document.getElementById('card_expiry').required = true;
            document.getElementById('card_cvc').required = true;
        } else {
            cardDetailsSection.style.display = 'none';
            document.getElementById('card_holder_name').required = false;
            document.getElementById('card_number').required = false;
            document.getElementById('card_expiry').required = false;
            document.getElementById('card_cvc').required = false;
        }

        await refreshCharges();
    }

    // Event listeners
    orderTypeSelect.addEventListener('change', handleOrderTypeChange);
    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', handlePaymentMethodChange);
    });

    // Helper: fetch tax rate only
    async function fetchTaxRate(paymentMethod) {
        try {
            const params = new URLSearchParams({
                order_type: orderTypeSelect.value,
                delivery_area: deliveryAreaField.value,
                payment_method: paymentMethod
            });
            const res = await fetch(`/checkout/calculate-delivery-fee?${params.toString()}`);
            const data = await res.json();
            return parseFloat(data.tax_rate || 0);
        } catch (_) { return parseFloat(taxRateLabel.textContent || '0'); }
    }

    async function refreshCharges() {
        try {
            const params = new URLSearchParams({
                order_type: orderTypeSelect.value,
                delivery_area: deliveryAreaField.value,
                payment_method: document.querySelector('input[name="payment_method"]:checked').value
            });
            const res = await fetch(`/checkout/calculate-delivery-fee?${params.toString()}`);
            const data = await res.json();
            const fee = parseFloat(data.delivery_fee || 0);
            const taxRate = parseFloat(data.tax_rate || 0);
            deliveryFeeEl.textContent = formatMoney(fee);
            taxRateLabel.textContent = taxRate;
            recalcTotals(taxRate);
        } catch (e) {
            console.error('Failed to refresh charges', e);
        }
    }

    // Initialize
    // handleOrderTypeChange();
    // handlePaymentMethodChange();
    // deliveryAreaField.addEventListener('change', refreshCharges);
    // Initialize
(async function initializeCheckout() {
    await handleOrderTypeChange();
    await handlePaymentMethodChange();
    await refreshCharges(); // ✅ FIX: ensures tax and total are recalculated on first load
})();
deliveryAreaField.addEventListener('change', refreshCharges);


    // Form submission
    const checkoutForm = document.getElementById('checkoutForm');
    const placeOrderBtn = document.getElementById('place_order_btn');
    const loadingSpinner = placeOrderBtn.querySelector('.loading-spinner');
    const btnText = placeOrderBtn.querySelector('.btn-text');

    checkoutForm.addEventListener('submit', function(e) {
        // Show loading state
        placeOrderBtn.disabled = true;
        loadingSpinner.classList.remove('hidden');
        btnText.textContent = 'Processing...';
    });

    // Phone number formatting
    const phoneInput = document.getElementById('customer_phone');
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.startsWith('92')) {
            value = '+' + value;
        } else if (value.startsWith('0')) {
            value = '+92' + value.substring(1);
        } else if (!value.startsWith('+92')) {
            value = '+92' + value;
        }
        e.target.value = value;
    });
});

function updateSideQuantityCheck(cartItemId, change) {
    // console.log('test');
    const row = document.querySelector(`#ordersumray [data-cart-item-id="${cartItemId}"]`);
    if (!row) return;
    const input = row.querySelector('input[type="text"]');
    const current = parseInt(input.value);
    const next = Math.max(1, Math.min(100, current + change));
    if (next === current) return;
    // Optimistic UI for snappier feel
    input.value = next;
    const unit = parseFloat(row.getAttribute('data-unit-price')) || 0;
    const rowTotal = row.querySelector('.row-total');
    if (rowTotal) { rowTotal.textContent = `Rs ${Number(unit * next).toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}`; }
    // Swap minus to trash when quantity hits 1
    const leftBtn = row.querySelector('.inline-flex > button:first-child');
    if (leftBtn) {
      if (next === 1) {
        leftBtn.className = 'flex-shrink-0 bg-red-100 text-red-600 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center';
        leftBtn.innerHTML = '<i  class="w-4 h-4 fa fa-trash"></i>';
        leftBtn.onclick = function(){ confirmRemoveFromSideCartCheck(cartItemId); };
        if (window.lucide && typeof window.lucide.createIcons === 'function') { window.lucide.createIcons(); }
      } else {
        leftBtn.className = 'minus flex-shrink-0 bg-default-200 text-default-800 rounded-full h-6 w-6 text-sm inline-flex items-center justify-center';
        leftBtn.textContent = '–';
        leftBtn.onclick = function(){ updateSideQuantityCheck(cartItemId, -1); };
      }
    }
    fetch(`/cart/${cartItemId}/quantity`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
      body: JSON.stringify({ quantity: next })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        // Recalculate subtotal client-side while server refresh is pending
        let subtotal = 0;
        let cartcount = 0;
        document.querySelectorAll('#ordersumray [data-cart-item-id]').forEach(function(r){
          const qty = parseInt(r.querySelector('input[type="text"]').value || '1');
          const price = parseFloat(r.getAttribute('data-unit-price')) || 0;
          subtotal += qty * price;
          cartcount += qty;
        });
        const subEl = document.getElementById('subtotal');
        if (subEl){
            subEl.textContent = `Rs ${subtotal.toLocaleString(undefined,{minimumFractionDigits:0,maximumFractionDigits:0})}`;
            var delivery_fee = String($('#delivery_fee').text()).replace(/[^0-9.]/g, '');
>>>>>>> 40a619db682e5611abd4ac0e9bc831820cbfa368
            var tax_rate_label = parseMoney($('#tax_rate_label').text());
            var discountamount = parseMoney($('#discountamount').text());
            var tax_amount = (subtotal * tax_rate_label) / 100;

            $('#tax_amount').text('Rs ' + tax_amount.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 0 }));
<<<<<<< HEAD

            var totalamount = parseFloat(subtotal) + parseFloat(delivery_fee) - parseFloat(discountamount) + parseFloat(tax_amount);
            $('#total_amount').text('Rs ' + totalamount.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 0 }));

            $('.cart-count').text(cartcount);
        }
    }

    // Polling function as a fallback
    function startCartPolling() {
        setInterval(() => {
            if (window.location.pathname.includes('/checkout')) {
                // Check if cart was updated recently
                const lastUpdate = localStorage.getItem('cartLastUpdated');
                if (lastUpdate && lastUpdate > window.checkoutCartState.lastUpdate) {
                    console.log('Cart update detected via polling');
                    refreshCheckoutCart();
                }
            }
        }, 2000); // Check every 2 seconds
    }

    // Initialize checkout page
    document.addEventListener('DOMContentLoaded', function() {
        $('#bottomCartBar').hide();

        // Your existing checkout initialization code...
        const orderTypeSelect = document.getElementById('order_type');
        const deliverySection = document.getElementById('delivery_section');
        const tableSection = document.getElementById('table_section');
        const deliveryAreaField = document.getElementById('delivery_area');
        const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
        const cardDetailsSection = document.getElementById('card_details');

        // Refresh cart when page loads to ensure it's in sync
        setTimeout(() => {
            refreshCheckoutCart();
            startCartPolling(); // Start the polling fallback
        }, 500);

        // Debug function to manually trigger refresh
        window.debugRefreshCart = function() {
            console.log('Manual cart refresh triggered');
            refreshCheckoutCart();
        };
    });

    // Add debug button (remove in production)
    document.addEventListener('DOMContentLoaded', function() {
        const debugButton = document.createElement('button');
        debugButton.textContent = 'Debug: Refresh Cart';
        debugButton.style.position = 'fixed';
        debugButton.style.top = '10px';
        debugButton.style.right = '10px';
        debugButton.style.zIndex = '10000';
        debugButton.style.padding = '10px';
        debugButton.style.background = '#f00';
        debugButton.style.color = '#fff';
        debugButton.onclick = window.debugRefreshCart;
        document.body.appendChild(debugButton);
    });
    <script>
    function parseMoney(text) {
        return parseFloat(String(text).replace(/[^0-9.]/g, '')) || 0;
    }

    function formatMoney(num) {
        return 'Rs. ' + Number(num).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    // Only show debug messages in development
    const isDevelopment = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';

    function debugLog(...args) {
        if (isDevelopment) {
            console.log(...args);
        }
    }

    // Global variable to track checkout cart state
    window.checkoutCartState = {
        items: [],
        subtotal: 0,
        count: 0,
        lastUpdate: 0
    };

    // Enhanced event listener for cart updates
    window.addEventListener('cartUpdated', function(event) {
        debugLog('Cart updated event received in checkout');
        refreshCheckoutCart();
    });

    // Listen for storage events (cross-tab communication)
    window.addEventListener('storage', function(event) {
        if (event.key === 'cartLastUpdated') {
            debugLog('Cart updated via storage event');
            refreshCheckoutCart();
        }
    });

    // Enhanced refresh function
    window.refreshCheckoutData = function() {
        refreshCheckoutCart();
    };

    window.refreshCheckoutCart = function() {
        debugLog('Refreshing checkout cart data...');

        fetch('/checkout/cart-items?' + new URLSearchParams({ _t: Date.now() }))
            .then(r => {
                if (!r.ok) throw new Error('Failed to fetch cart items');
                return r.json();
            })
            .then(data => {
                if (data.success) {
                    debugLog('Cart items fetched successfully:', data.items.length, 'items');
                    updateCheckoutItems(data.items);
                    updateCheckoutTotals(data.totals);
                    window.checkoutCartState = {
                        items: data.items,
                        subtotal: data.totals.subtotal,
                        count: data.totals.count,
                        lastUpdate: Date.now()
                    };
                }
            })
            .catch(error => {
                debugLog('Error refreshing checkout cart:', error);
                recalcCheckoutTotals();
            });
    };


    document.addEventListener('DOMContentLoaded', function(){
        $('#bottomCartBar').hide();

        // Your existing checkout initialization code...

        // Refresh cart when page loads
        setTimeout(() => {
            refreshCheckoutCart();
        }, 500);

        // Only add debug button in development
        if (isDevelopment) {
            const debugButton = document.createElement('button');
            debugButton.textContent = 'Debug: Refresh Cart';
            debugButton.style.position = 'fixed';
            debugButton.style.top = '10px';
            debugButton.style.right = '10px';
            debugButton.style.zIndex = '10000';
            debugButton.style.padding = '10px';
            debugButton.style.background = '#f00';
            debugButton.style.color = '#fff';
            debugButton.onclick = window.refreshCheckoutCart;
            document.body.appendChild(debugButton);
        }
    });
</script>
=======
            // $('#tax_amount').text('Rs '+tax_amount);
            var totalamount = parseFloat(subtotal) + parseFloat(delivery_fee) - parseFloat(discountamount) + parseFloat(tax_amount);
            $('#total_amount').text('Rs ' + totalamount.toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 0 }));

            // $('#total_amount').text('Rs ' +totalamount);
            $('.cart-count').text(cartcount);
        }
      }
    });
  }

  function removeFromSideCartCheck(cartItemId) {
    fetch(`/cart/${cartItemId}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {  $('#ordersumray [data-cart-item-id="' + cartItemId + '"]').remove(); } });
  }

  function confirmRemoveFromSideCartCheck(cartItemId) {
    // Simple confirm modal
    if (!window.confirm('Are you sure you want to remove this product?')) return;
    removeFromSideCartCheck(cartItemId);
  }
>>>>>>> 40a619db682e5611abd4ac0e9bc831820cbfa368
</script>
@endsection
