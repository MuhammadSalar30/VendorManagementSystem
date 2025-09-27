<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\MenuItem;
use App\Models\MenuSection;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['addToCart', 'updateQuantity', 'removeFromCart', 'clearCart', 'getCartCount','side']);
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

    // public function index()
    // {
    //     $cartItems = Cart::where('user_id', Auth::id())
    //     ->with('menuItem')
    //     ->get();

    // $total = $cartItems->sum(fn($item) => $item->price * $item->quantity);

    // $menuSections = $this->getMenuSections();

    // return view('client.cart', compact('cartItems', 'total', 'menuSections'));
    //     $cartItems = Auth::user()->cart()->with('menuItem')->get();
    //     $total = $cartItems->sum(function($item) {
    //         return $item->price * $item->quantity;
    //     });

    //     // Get menu sections for navbar
    //     $menuSections = $this->getMenuSections();

    //     return view('client.cart', compact('cartItems', 'total', 'menuSections'));
    // }

    public function index()
{
    $cartItems = Cart::where('user_id', Auth::id())
        ->with('menuItem')
        ->get();

    $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);

    // Load delivery fee and tax rates from settings
    $defaultDeliveryCharge = (float) \App\Models\Setting::getValue('defaultDeliveryCharge', 0);
    $deliveryFee = $defaultDeliveryCharge;

    $taxRate = (float) \App\Models\Setting::getValue('taxCashOnDelivery', 0); // assume COD by default
    $taxAmount = $subtotal * ($taxRate / 100);

    $discountAmount = 0;
    $total = $subtotal + $deliveryFee + $taxAmount - $discountAmount;

    $menuSections = $this->getMenuSections();

    return view('client.cart', compact(
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
     * Render side cart drawer content (partial view)
     */
    // public function side()
    // {
    //     if (Auth::check()) {
    //         // For authenticated users
    //         $cartItems = Auth::user()->cart()->with('menuItem')->get();
    //         $total = $cartItems->sum(function ($item) {
    //             return $item->price * $item->quantity;
    //         });
    //     } else {
    //         // For guest users
    //         $cart = session()->get('cart', []);
    //         $cartItems = collect($cart)->map(function ($item) {
    //             // Fetch the MenuItem model for each cart item
    //             $menuItem = MenuItem::find($item['menu_item_id']);
    //             return [
    //                 'menu_item_id' => $item['menu_item_id'],
    //                 'size' => $item['size'],
    //                 'quantity' => $item['quantity'],
    //                 'price' => $item['price'],
    //                 'name' => $menuItem ? $menuItem->name : 'Unknown Item',
    //                 'menu_item' => $menuItem, // Include the MenuItem model
    //             ];
    //         });
    //         $total = $cartItems->sum(function ($item) {
    //             return $item['price'] * $item['quantity'];
    //         });
    //     }
    //     // dd($cartItems);
    //     return view('client.partials.side-cart', compact('cartItems', 'total'));
    // }

public function side()
{
    if (Auth::check()) {
        $cartItems = Auth::user()->cart()->with('menuItem')->get();
        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
    } else {
        $cart = session()->get('cart', []);
        $cartItems = collect($cart)->map(function ($item) {
            $menuItem = \App\Models\MenuItem::find($item['menu_item_id']);
            return [
                'menu_item_id' => $item['menu_item_id'],
                'size' => $item['size'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'name' => $menuItem ? $menuItem->name : 'Unknown Item',
                'menu_item' => $menuItem,
            ];
        });
        $subtotal = $cartItems->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    // Same calculation as checkout
    $defaultDeliveryCharge = (float) \App\Models\Setting::getValue('defaultDeliveryCharge', 0);
    $deliveryFee = $defaultDeliveryCharge;
    $taxRate = (float) \App\Models\Setting::getValue('taxCashOnDelivery', 0);
    $taxAmount = $subtotal * ($taxRate / 100);
    $discountAmount = 0;
    $total = $subtotal + $deliveryFee + $taxAmount - $discountAmount;

    return view('client.partials.side-cart', compact(
        'cartItems',
        'subtotal',
        'deliveryFee',
        'taxRate',
        'taxAmount',
        'discountAmount',
        'total'
    ));
}

    public function addToCart(Request $request)
{

    $request->validate([
        'menu_item_id' => 'required|exists:menu_items,id',
        'quantity' => 'required|integer|min:1',
        'size' => 'nullable|string',
    ]);

    $menuItem = MenuItem::findOrFail($request->menu_item_id);

    // Determine price based on size
    $price = $menuItem->price;
    if ($request->size && $menuItem->size && is_array($menuItem->size)) {
        foreach ($menuItem->size as $sizeOption) {
            // Handle both old format ["full"] and new format [{"name": "full", "price": 100}]
            if (is_array($sizeOption) && isset($sizeOption['name']) && $sizeOption['name'] === $request->size) {
                // New structured format
                $price = $sizeOption['price'];
                break;
            } elseif (is_string($sizeOption) && $sizeOption === $request->size) {
                // Old simple format - use the item's main price
                $price = $menuItem->price;
                break;
            }
        }
    }

    // Check if user is logged in
    if (Auth::check()) {
        // Handle authenticated user's cart
        $existingCartItem = Cart::where('user_id', Auth::id())
            ->where('menu_item_id', $request->menu_item_id)
            ->where('size', $request->size)
            ->first();

        if ($existingCartItem) {
            $existingCartItem->update([
                'quantity' => $existingCartItem->quantity + $request->quantity
            ]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'menu_item_id' => $request->menu_item_id,
                'size' => $request->size,
                'quantity' => $request->quantity,
                'price' => $price
            ]);
        }

        $cartCount = Auth::user()->cart()->sum('quantity');
    } else {
        // Handle guest user's cart using session
        $cart = session()->get('cart', []);

        $key = $request->menu_item_id . ':' . ($request->size ?? 'default');
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->quantity;
        } else {
            $cart[$key] = [
                'menu_item_id' => $request->menu_item_id,
                'size' => $request->size,
                'quantity' => $request->quantity,
                'price' => $price,
                'name' => $menuItem->name,
            ];
        }

        session()->put('cart', $cart);
        $cartCount = array_sum(array_column($cart, 'quantity'));
    }

    return response()->json([
        'success' => true,
        'message' => 'Item added to cart successfully',
        'cart_count' => $cartCount
    ]);
}

    public function transferSessionCartToDatabase()
    {
        if (!Auth::check()) {
            return;
        }

        $cart = session()->get('cart', []);
        foreach ($cart as $item) {
            $existingCartItem = Cart::where('user_id', Auth::id())
                ->where('menu_item_id', $item['menu_item_id'])
                ->where('size', $item['size'])
                ->first();

            if ($existingCartItem) {
                $existingCartItem->update([
                    'quantity' => $existingCartItem->quantity + $item['quantity']
                ]);
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'menu_item_id' => $item['menu_item_id'],
                    'size' => $item['size'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
        }

        session()->forget('cart');
          Auth::user()->load('cart');
    }

    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if (Auth::check()) {
            // For authenticated users
            $cartItem = Cart::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $cartItem->update(['quantity' => $request->quantity]);

            return response()->json([
                'success' => true,
                'subtotal' => $cartItem->price * $request->quantity
            ]);
        } else {
            // For guest users
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] = $request->quantity;
                session()->put('cart', $cart);

                return response()->json([
                    'success' => true,
                    'subtotal' => $cart[$id]['price'] * $request->quantity
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Item not found in cart'], 404);
        }
    }

    public function removeFromCart($id)
{
    if (Auth::check()) {
        // For authenticated users
        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully'
        ]);
    } else {
        // For guest users
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Item not found in cart'], 404);
    }
}

public function clearCart()
{
    if (Auth::check()) {
        // For authenticated users
        Auth::user()->cart()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    } else {
        // For guest users
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }
}

public function getCartCount()
{
    if (Auth::check()) {
        // For authenticated users
        $count = Auth::user()->cart()->sum('quantity');
        return response()->json(['count' => $count]);
    } else {
        // For guest users
        $cart = session()->get('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));
        return response()->json(['count' => $count]);
    }
}

    /**
     * Debug method to check cart functionality
     */
    public function debug()
    {
        $user = Auth::user();
        $cartItems = $user->cart()->with('menuItem')->get();

        $debug = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'cart_items_count' => $cartItems->count(),
            'cart_items' => $cartItems->toArray(),
            'all_cart_items_in_db' => \App\Models\Cart::all()->toArray(),
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
        ];

        return response()->json($debug);
    }
<<<<<<< HEAD
    public function getCartData()
    {
        if (auth()->check()) {
            $cartItems = Cart::where('user_id', auth()->id())->with('menuItem')->get();
            $count = $cartItems->sum('quantity');
            $subtotal = $cartItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
        } else {
            // Handle guest cart from session
            $cart = session()->get('cart', []);
            $count = collect($cart)->sum('quantity');
            $subtotal = collect($cart)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            });
            $cartItems = collect($cart);
        }

        return response()->json([
            'count' => $count,
            'subtotal' => $subtotal,
            'items' => $cartItems
        ]);
    }
=======

>>>>>>> 40a619db682e5611abd4ac0e9bc831820cbfa368
}
