<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Currency;
use App\Models\MenuItem;
use App\Models\MenuSection;

class RoutingController extends Controller
{

    public function __construct()
    {
        // $this->
        // middleware('auth')->
        // except('index');
    }

    /**
     * Get menu sections with items for the navbar
     */
    public static function getMenuSections()
    {
        return MenuSection::with(['items' => function($query) {
            $query->select('id', 'name', 'menu_section_id');
        }])->orderBy('name')->get();
    }

    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index(Request $request)
    {
        // if (Auth::user()) {
        //     // Get menu sections for navbar
        //     $menuSections = $this->getMenuSections();

        //     return view('index', compact('menuSections'));
        // } else {
            //     return redirect('/login');
            // }
        $menuSections = $this->getMenuSections();
        return view('index', compact('menuSections'));
    }

    /**
     * Display a view based on first route param
     *
     *
     */
    public function root(Request $request, $first)
    {
        if ($first == "style.css.map")
            return redirect('/home');

        // Get menu sections for navbar
        $menuSections = $this->getMenuSections();
        if($first == 'admin'){
            if (Auth::user()) {
                return view('admin.dashboard');

            }else{
                return redirect('/login');

            }

        }
        return view($first, compact('menuSections'));
    }

    /**
     * second level route
     */
    public function secondLevel(Request $request, $first, $second)
    {
        // Handle admin orders routes - redirect to proper admin routes


        if ($first === 'admin' && $second === 'dashboard') {
            if (Auth::check()) {
                if(Auth::user()->role == 'admin'){
                    return view('admin.dashboard');
                }else{
                    return redirect()->route('home');

                }

            }else{
                return redirect('/login');

            }
        }

        if ($first === 'admin' && $second === 'currency-settings') {
            $currencies = Currency::orderBy('id', 'desc')->get() ?? collect();

            return view('admin.currency-settings', compact('currencies'));

        }

        if ($first === 'orders' && $second === 'list') {
            return redirect()->route('admin.orders.index');
        }

        if ($first === 'orders' && $second === 'details') {
            // If there's an order ID in the request, redirect to admin order details
            if ($request->has('id')) {
                return redirect()->route('admin.orders.show', $request->get('id'));
            }
            return redirect()->route('admin.orders.index');
        }

        // Redirect client checkout to dedicated controller route so cart data exists
        if ($first === 'client' && $second === 'checkout') {
            return redirect()->route('checkout.index');
        }

        // Get menu sections for navbar
        $menuSections = $this->getMenuSections();

        if ($first === 'client' && $second === 'product-list') {
            // Support category + price range filtering (same behavior as product grid)
            $categories = MenuSection::orderBy('name')->get(['id','name','discount']);

            // Read selected categories from query string
            $selectedCats = collect(explode(',', (string) $request->query('categories', '')))
                ->filter(fn($v) => strlen(trim($v)) > 0)
                ->map(fn($v) => (int) $v)
                ->values()
                ->all();

            $min = (int) $request->query('min_price', 0);
            $max = (int) $request->query('max_price', 5000);

            $query = MenuItem::with('section');
            if (!empty($selectedCats)) {
                $query->whereIn('menu_section_id', $selectedCats);
            }
            // Optional text search
            $q = trim((string) $request->query('q', ''));
            if ($q !== '') {
                $query->where(function($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('description', 'like', "%{$q}%");
                });
            }
            $raw = $query->get();

            $items = $raw->groupBy('name')->map(function ($group) {
                $firstItem = $group->sortByDesc('updated_at')->first();
                $sizes = [];
                foreach ($group as $g) {
                    if ($g->size && is_array($g->size)) {
                        foreach ($g->size as $key => $sizeOption) {
                            // Supported formats:
                            // 1) [{ name: 'full', price: 1000 }, ...]
                            // 2) ['full', 'half', 'quarter']  -> use item's price
                            // 3) { 'full': 1000, 'half': 600 }  (associative map)
                            // 4) ["{\"name\":\"full\",\"price\":1000}", ...] (stringified JSON)
                            if (is_array($sizeOption) && isset($sizeOption['name'])) {
                                $sizes[$sizeOption['name']] = $sizeOption['price'];
                            } elseif (is_string($sizeOption)) {
                                $decoded = json_decode($sizeOption, true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && isset($decoded['name'])) {
                                    $sizes[$decoded['name']] = $decoded['price'] ?? $g->price;
                                } else {
                                    $sizes[$sizeOption] = $g->price; // name-only format
                                }
                            } elseif ((is_int($key) || is_string($key)) && (is_numeric($sizeOption))) {
                                // Associative map format: key => price
                                $sizes[(string) $key] = (float) $sizeOption;
                            }
                        }
                    } elseif (is_string($g->size) && strlen(trim($g->size)) > 0) {
                        // Single string value like 'full' from legacy rows
                        $sizes[trim($g->size)] = $g->price;
                    }
                }
                // Determine display price: prefer 'Full' size (case-insensitive); fallback to max price
                $displayPrice = $group->max('price');
                foreach ($sizes as $k => $v) {
                    if (strtolower((string) $k) === 'full') { $displayPrice = $v; break; }
                }

                return (object) [
                    'id' => $firstItem->id,
                    'name' => $firstItem->name,
                    'description' => $firstItem->description,
                    'image' => $firstItem->image,
                    'section' => $firstItem->section,
                    'sizes' => $sizes,
                    'display_price' => $displayPrice,
                    'min_price' => $group->min('price'),
                    'max_price' => $group->max('price'),
                    'has_multiple_sizes' => count($sizes) > 1,
                ];
            })
            // Filter by the display price so the slider matches what users see
            ->filter(function ($item) use ($min, $max) {
                return ($item->display_price >= $min && $item->display_price <= $max);
            })
            ->values();

            return view($first . '.' . $second, [
                'categories' => $categories,
                'items' => $items,
                'min_price' => $min,
                'max_price' => $max,
                'selected_categories' => $selectedCats,
                'menuSections' => $menuSections,
                'q' => $q,
            ]);
        }

        // Inject categories and price bounds for client/product-grid
        if ($first === 'client' && $second === 'product-grid') {
            $categories = MenuSection::orderBy('name')->get(['id','name','discount']);

            // Read filters from query
            $selectedCats = collect(explode(',', (string) $request->query('categories', '')))
                ->filter(fn($v) => strlen(trim($v)) > 0)
                ->map(fn($v) => (int) $v)
                ->values()
                ->all();
            $min = (int) $request->query('min_price', 0);
            $max = (int) $request->query('max_price', 5000);

            $query = MenuItem::with('section');
            if (!empty($selectedCats)) {
                $query->whereIn('menu_section_id', $selectedCats);
            }
            // Optional text search
            $q = trim((string) $request->query('q', ''));
            if ($q !== '') {
                $query->where(function($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('description', 'like', "%{$q}%");
                });
            }
            // We fetch all then compute display price per grouped product; price filter will be applied to display price
            $raw = $query->get();

            $items = $raw->groupBy('name')->map(function ($group) {
                $firstItem = $group->sortByDesc('updated_at')->first();
                $sizes = [];
                foreach ($group as $g) {
                    if ($g->size && is_array($g->size)) {
                        foreach ($g->size as $key => $sizeOption) {
                            if (is_array($sizeOption) && isset($sizeOption['name'])) {
                                $sizes[$sizeOption['name']] = $sizeOption['price'];
                            } elseif (is_string($sizeOption)) {
                                $decoded = json_decode($sizeOption, true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && isset($decoded['name'])) {
                                    $sizes[$decoded['name']] = $decoded['price'] ?? $g->price;
                                } else {
                                    $sizes[$sizeOption] = $g->price;
                                }
                            } elseif ((is_int($key) || is_string($key)) && (is_numeric($sizeOption))) {
                                $sizes[(string) $key] = (float) $sizeOption;
                            }
                        }
                    } elseif (is_string($g->size) && strlen(trim($g->size)) > 0) {
                        $sizes[trim($g->size)] = $g->price;
                    }
                }
                // Determine display price: prefer 'Full' size; fallback to max price
                $displayPrice = $group->max('price');
                foreach ($sizes as $k => $v) {
                    if (strtolower((string) $k) === 'full') { $displayPrice = $v; break; }
                }

                return (object) [
                    'id' => $firstItem->id,
                    'name' => $firstItem->name,
                    'description' => $firstItem->description,
                    'image' => $firstItem->image,
                    'section' => $firstItem->section,
                    'sizes' => $sizes,
                    'display_price' => $displayPrice,
                    'min_price' => $group->min('price'),
                    'max_price' => $group->max('price'),
                    'has_multiple_sizes' => count($sizes) > 1,
                ];
            })
            // Apply price range filter against display price
            ->filter(function ($item) use ($min, $max) {
                return ($item->display_price >= $min && $item->display_price <= $max);
            })
            ->values();

            return view($first . '.' . $second, [
                'categories' => $categories,
                'items' => $items,
                'min_price' => $min,
                'max_price' => $max,
                'selected_categories' => $selectedCats,
                'menuSections' => $menuSections,
                'q' => $q,
            ]);
        }

        // Category details by numeric ID: /category/{id}
        if ($first === 'category' && is_numeric($second)) {
            $category = MenuSection::find($second);
            if (!$category) {
                abort(404);
            }
            $products = MenuItem::where('menu_section_id', (int) $second)->latest('id')->get();
            return view('category.products', [
                'category' => $category,
                'products' => $products,
                'menuSections' => $menuSections,
            ]);
        }

        return view($first . '.' . $second, compact('menuSections'));
    }


    /**
     * third level route
     */
    public function thirdLevel(Request $request, $first, $second, $third)
    {
        // Get menu sections for navbar
        $menuSections = $this->getMenuSections();

        $data = null;
        $recommendations = [];
        if ($third) {
            if ($first === 'client' && $second === 'product-detail') {
                $data = MenuItem::find($third);
                if ($data) {
                    $recommendations = MenuItem::where('menu_section_id', $data->menu_section_id)
                        ->where('id', '!=', $data->id)
                        ->latest('id')
                        ->take(3)
                        ->get();

                    // Collect size options for this product (grouped by name)
                    $siblings = MenuItem::where('name', $data->name)->get();
                    $sizes = [];
                    foreach ($siblings as $s) {
                        if ($s->size && is_array($s->size)) {
                            foreach ($s->size as $sizeOption) {
                                // Handle both old format ["full"] and new format [{"name": "full", "price": 100}]
                                if (is_array($sizeOption) && isset($sizeOption['name'])) {
                                    // New structured format
                                    $sizes[$sizeOption['name']] = $sizeOption['price'];
                                } elseif (is_string($sizeOption)) {
                                    // Old simple format - use the item's main price
                                    $sizes[$sizeOption] = $s->price;
                                }
                            }
                        }
                    }
                    $data->sizes = $sizes;
                    $data->min_price = $siblings->min('price');
                }
            } elseif ($first === 'products') {
                $data = MenuItem::find($third);
            }
        }

        return view($first . '.' . $second , [
            'data' => $data,
            'recommendations' => $recommendations,
            'menuSections' => $menuSections,
        ]);
    }
}
