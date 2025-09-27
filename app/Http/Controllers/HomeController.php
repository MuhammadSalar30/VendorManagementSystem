<?php
namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\MenuSection;
use App\Http\Controllers\RoutingController;

class HomeController extends Controller
{
   public function index(Request $request)
{
    $menuItems = \App\Models\MenuItem::latest()->take(9)->get();
    $categories = MenuSection::orderBy('name')->get(['id','name','discount']);

    // Read filters from query
    $selectedCats = collect(explode(',', (string) $request->query('categories', '')))
        ->filter(fn($v) => strlen(trim($v)) > 0)
        ->map(fn($v) => (int) $v)
        ->values()
        ->all();
    $min = (int) $request->query('min_price', 0);
    $max = (int) $request->query('max_price', 5000);
    $orderType = $request->query('order_type'); // 'delivery' | 'pickup' | null
    $q = trim((string) $request->query('q', ''));

    $query = MenuItem::with('section');
    if (!empty($selectedCats)) {
        $query->whereIn('menu_section_id', $selectedCats);
    }
    if (!empty($orderType)) {
        // MenuItem.order_type is a JSON column containing allowed order modes
        $query->whereJsonContains('order_type', $orderType);
    }
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
    $menuSections = RoutingController::getMenuSections();

    return view('index', [
        'categories' => $categories,
        'items' => $items,
        'min_price' => $min,
        'max_price' => $max,
        'selected_categories' => $selectedCats,
        'menuSections' => $menuSections,
        'selected_order_type' => $orderType,
        'q' => $q,
    ]);
}

}
