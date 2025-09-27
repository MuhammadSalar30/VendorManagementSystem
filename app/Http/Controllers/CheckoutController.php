<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuSection;
use App\Models\Setting;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get menu sections with items for the navbar
     */
    private function getMenuSections()
    {
        return MenuSection::with(['items' => function($query) {
            $query->select('id', 'name', 'menu_section_id');
        }])->orderBy('name')->get();
    }

    /**
     * Show the checkout page
     */
    public function index()
    {
        // Debug: Check authentication
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access checkout.');
        }

        $user = Auth::user();
        $cartItems = $user->cart()->with('menuItem')->get();

        // Debug: Log cart information
        \Log::info('Checkout Debug', [
            'user_id' => $user->id,
            'cart_items_count' => $cartItems->count(),
            'cart_items' => $cartItems->toArray()
        ]);

        if ($cartItems->count() === 0) {
            return redirect()->route('client.cart')->with('error', 'Your cart is empty. Please add items before checkout.');
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        // Calculate delivery fee for initial render (defaults to delivery with no area yet)
        $defaultDeliveryCharge = (float) (Setting::getValue('defaultDeliveryCharge', 0));
        $deliveryFee = $defaultDeliveryCharge; // initial assumption for delivery

        // Load tax rates from settings
        $taxCod = (float) (Setting::getValue('taxCashOnDelivery', 0));
        $taxOnline = (float) (Setting::getValue('taxOnlinePayment', 0));
        // Initial display uses COD by default
        $taxRate = $taxCod;
        // $taxAmount = ($subtotal + $deliveryFee) * ($taxRate / 100);
        $taxAmount = $subtotal * ($taxRate / 100);



        // Calculate discount (if any)
        $discountAmount = 0;

        // Calculate total
        $total = $subtotal + $deliveryFee + $taxAmount - $discountAmount;
//         dd([
//     'subtotal' => $subtotal,
//     'deliveryFee' => $deliveryFee,
//     'taxRate' => $taxRate,
//     'calculatedTax' => $taxAmount
// ]);


        // Get menu sections for navbar
        $menuSections = $this->getMenuSections();

        return view('client.checkout', compact(
            'cartItems',
            'subtotal',
            'deliveryFee',
            'taxRate',
            'taxAmount',
            'discountAmount',
            'total',
            'menuSections',
            'defaultDeliveryCharge'
        ));
    }

    /**
     * Process the checkout and create order
     */
    public function process(Request $request)
    {
        \Log::info('Checkout process started', ['request_data' => $request->all()]);

        // Create a test order if no data is provided (for testing)
        if (!$request->has('customer_name')) {
            \Log::info('Creating test order');
            $order = Order::create([
                'customer_name' => 'Test User',
                'customer_email' => 'test@example.com',
                'customer_phone' => '+923451234567',
                'order_type' => 'delivery',
                'delivery_address' => 'Test Address, Karachi',
                'delivery_area' => 'Gulshan',
                'payment_method' => 'cash_on_delivery',
                'subtotal' => 500.00,
                'tax_amount' => 0.00,
                'delivery_fee' => 100.00,
                'discount_amount' => 0.00,
                'total_price' => 600.00,
                'status' => 'confirmed',
                'notes' => 'Test order',
            ]);

            \Log::info('Test order created', ['order_id' => $order->id, 'order_number' => $order->generateOrderNumber()]);
            return redirect()->route('order.confirmation', $order->id)
                ->with('success', 'Test order created! Order ID: ' . $order->generateOrderNumber());
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'order_type' => 'required|in:delivery,takeaway,dine-in',
            'delivery_address' => 'required_if:order_type,delivery|nullable|string',
            'delivery_area' => 'required_if:order_type,delivery|nullable|string',
            'table_no' => 'required_if:order_type,dine-in|nullable|string|max:10',
            'payment_method' => 'required|in:cash_on_delivery,online_payment',
            // Card details will be collected on the payment page if online payment is selected
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            \Log::error('Checkout validation failed', ['errors' => $validator->errors()]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get cart items
        $cartItems = Auth::user()->cart()->with('menuItem')->get();
        \Log::info('Cart items count', ['count' => $cartItems->count(), 'user_id' => Auth::id()]);

        if ($cartItems->count() === 0) {
            \Log::error('Cart is empty during checkout');
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = $cartItems->sum(function($item) {
                return $item->price * $item->quantity;
            });

            // Calculate delivery fee
            $deliveryFee = $this->calculateDeliveryFee($request->order_type, $request->delivery_area);

            // Calculate tax based on payment method from settings
            $taxRate = $request->payment_method === 'online_payment'
                ? (float) (Setting::getValue('taxOnlinePayment', 0))
                : (float) (Setting::getValue('taxCashOnDelivery', 0));
            // $taxAmount = ($subtotal + $deliveryFee) * ($taxRate / 100);

            $taxAmount = $subtotal * ($taxRate / 100);




            // Calculate discount (if any)
            $discountAmount = 0;

            // Calculate total
            $totalPrice = $subtotal + $deliveryFee + $taxAmount - $discountAmount;

            // Map UI payment option to DB enum
            $dbPaymentMethod = $request->payment_method === 'online_payment' ? 'card' : $request->payment_method;
            // dd($taxAmount);

            // Create the order
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'order_type' => $request->order_type,
                'table_no' => $request->table_no,
                'delivery_address' => $request->delivery_address,
                'delivery_area' => $request->delivery_area,
                'delivery_city' => 'Karachi',
                'payment_method' => $dbPaymentMethod,
                'payment_status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'delivery_fee' => $deliveryFee,
                'discount_amount' => $discountAmount,
                'total_price' => $totalPrice,
                // 'status' => ($request->payment_method === 'cash_on_delivery') ? 'confirmed' : 'pending',
                'status' =>'pending',
                'notes' => $request->notes,
            ]);
// dd($order->tax_amount);
            // Set estimated delivery time
            $estimatedMinutes = $this->calculateEstimatedDeliveryTime($request->order_type);
            $order->setEstimatedDeliveryTime($estimatedMinutes);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $cartItem->menu_item_id,
                    'item_name' => $cartItem->menuItem->name,
                    'item_description' => $cartItem->menuItem->description,
                    'item_size' => $cartItem->size,
                    'item_price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                    'total_price' => $cartItem->price * $cartItem->quantity,
                ]);
            }

            // Clear the cart
            Auth::user()->cart()->delete();

            DB::commit();

            // Redirect based on payment method
            if ($request->payment_method === 'online_payment') {
                // Send user to payment page (simulated UI)
                return redirect()->route('checkout.payment', $order->id)
                    ->with('success', 'Order created. Please complete your online payment.');
            }

            // COD flow: go straight to confirmation page
            \Log::info('Order created successfully', ['order_id' => $order->id, 'order_number' => $order->generateOrderNumber()]);
            return redirect()->route('order.confirmation', $order->id)
                ->with('success', 'Order placed successfully! Order ID: ' . $order->generateOrderNumber());

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Something went wrong while processing your order. Please try again.')
                ->withInput();
        }
    }

    /**
     * Calculate delivery fee based on order type and area
     */
    private function calculateDeliveryFee($orderType, $deliveryArea = null)
    {
        if ($orderType !== 'delivery') {
            return 0;
        }

        // Load settings
        $defaultDeliveryCharge = (float) (Setting::getValue('defaultDeliveryCharge', 0));
        $areaBasedDelivery = (bool) (Setting::getValue('areaBasedDelivery', false));
        $areaCharges = Setting::getValue('areaCharges', []); // array of { areaName, areaCharge }

        if ($areaBasedDelivery && is_array($areaCharges) && $deliveryArea) {
            foreach ($areaCharges as $entry) {
                if (is_array($entry) && isset($entry['areaName']) && isset($entry['areaCharge'])) {
                    if (strcasecmp(trim($entry['areaName']), trim($deliveryArea)) === 0) {
                        return (float) $entry['areaCharge'];
                    }
                }
            }
        }

        return $defaultDeliveryCharge;
    }

    /**
     * Calculate estimated delivery time based on order type
     */
    private function calculateEstimatedDeliveryTime($orderType)
    {
        switch ($orderType) {
            case 'delivery':
                return 45; // 45 minutes for delivery
            case 'takeaway':
                return 20; // 20 minutes for takeaway
            case 'dine-in':
                return 25; // 25 minutes for dine-in
            default:
                return 30;
        }
    }

    /**
     * Show order confirmation page
     */
    public function confirmation($orderId)
    {
        $order = Order::with('orderItems.menuItem')->where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();

        // Get menu sections for navbar
        $menuSections = $this->getMenuSections();

        return view('client.order-confirmation', compact('order', 'menuSections'));
    }

    /**
     * Show mock payment page for Online Payment
     */
    public function payment($orderId)
    {
        $order = Order::with('orderItems.menuItem')->where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();
        if ($order->payment_method !== 'online_payment') {
            return redirect()->route('order.confirmation', $orderId);
        }
        $menuSections = $this->getMenuSections();
        return view('client.payment', compact('order', 'menuSections'));
    }

    /**
     * Complete online payment (simulate gateway success)
     */
    public function completePayment(Request $request, $orderId)
    {
        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();
        if ($order->payment_method !== 'online_payment') {
            return redirect()->route('order.confirmation', $orderId);
        }
        $order->update(['payment_status' => 'paid', 'status' => 'confirmed']);
        return redirect()->route('order.confirmation', $orderId)->with('success', 'Payment successful. Your order has been placed!');
    }

    /**
     * Cancel online payment (simulate gateway cancel)
     */
    public function cancelPayment($orderId)
    {
        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();
        if ($order->payment_method !== 'online_payment') {
            return redirect()->route('order.confirmation', $orderId);
        }
        // Keep payment_status pending and allow retry
        return redirect()->route('checkout.payment', $orderId)->with('error', 'Payment was cancelled. You can try again.');
    }

    /**
     * AJAX endpoint to calculate delivery fee
     */
    public function calculateDeliveryFeeAjax(Request $request)
    {
        $orderType = $request->order_type;
        $deliveryArea = $request->delivery_area;
        $paymentMethod = $request->payment_method; // 'cash_on_delivery' | 'online_payment'

        $deliveryFee = $this->calculateDeliveryFee($orderType, $deliveryArea);
        $taxRate = $paymentMethod === 'online_payment'
            ? (float) (Setting::getValue('taxOnlinePayment', 0))
            : (float) (Setting::getValue('taxCashOnDelivery', 0));

        return response()->json([
            'delivery_fee' => $deliveryFee,
            'formatted_delivery_fee' => 'Rs. ' . number_format($deliveryFee, 0)
            ,'tax_rate' => $taxRate
        ]);
    }

    /**
     * Track a specific order
     */
    public function trackOrder($orderId)
    {
        $order = Order::with('orderItems.menuItem')->where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();

        // Get menu sections for navbar
        $menuSections = $this->getMenuSections();

        return view('client.order-tracking', compact('order', 'menuSections'));
    }

    /**
     * Show order history for the authenticated user
     */
    public function orderHistory()
    {
        $orders = Order::with('orderItems.menuItem')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get menu sections for navbar
        $menuSections = $this->getMenuSections();

        return view('client.order-history', compact('orders', 'menuSections'));
    }
    public function getCheckoutData()
{
    // Return current cart data for checkout page
    $cartData = app(CartController::class)->getCartData()->getData();

    return response()->json([
        'success' => true,
        'items' => $cartData->items,
        'totals' => [
            'subtotal' => $cartData->subtotal,
            'total' => $cartData->subtotal // Add delivery/tax logic here
        ]
    ]);
}

public function getOrderSummary()
{
    // Return just the order summary HTML
    $cartItems = // your cart items logic
    $totals = // your totals logic

    view('partials.checkout-order-summary', compact('cartItems', 'totals'))->render();
}
public function getCartItems()
{
    try {
        if (auth()->check()) {
            // For logged-in users
            $cartItems = Cart::where('user_id', auth()->id())
                ->with('menuItem')
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'menu_item_id' => $item->menu_item_id,
                        'menu_item' => $item->menuItem,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'size' => $item->size
                    ];
                });

            $subtotal = $cartItems->sum(function($item) {
                return $item['price'] * $item['quantity'];
            });
            $count = $cartItems->sum('quantity');
        } else {
            // For guest users (adapt based on your guest cart implementation)
            $cart = session()->get('cart', []);
            $cartItems = collect($cart)->map(function($item, $key) {
                $menuItem = MenuItem::find($item['menu_item_id']);
                return [
                    'id' => $key,
                    'menu_item_id' => $item['menu_item_id'],
                    'menu_item' => $menuItem,
                    'quantity' => $item['quantity'] ?? 1,
                    'price' => $item['price'] ?? 0,
                    'size' => $item['size'] ?? null
                ];
            })->filter(); // Remove null items

            $subtotal = $cartItems->sum(function($item) {
                return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
            });
            $count = $cartItems->sum('quantity');
        }

        return response()->json([
            'success' => true,
            'items' => $cartItems,
            'totals' => [
                'subtotal' => $subtotal,
                'count' => $count,
                'total' => $subtotal
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'items' => [],
            'totals' => [
                'subtotal' => 0,
                'count' => 0,
                'total' => 0
            ]
        ]);
    }
}

}

