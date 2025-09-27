<?php
namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Filter products by order type (Delivery or Pick-up)
    public function filterByOrderType(Request $request)
    {
        $orderType = $request->query('order_type'); // Get order type from query parameter

        // Fetch products filtered by order type
        $products = MenuItem::whereJsonContains('order_type', $orderType)->get();

        return response()->json([
            'products' => $products
        ]);
    }

    // Filter products by category
    public function filterByCategory($id)
    {
        $products = MenuItem::where('category_id', $id)->get(); // Assuming category_id exists

        return response()->json([
            'products' => $products
        ]);
    }
}
