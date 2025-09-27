<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuSection;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = MenuSection::withCount('items')->orderBy('name')->get();
        return view('products.categorylist', compact('categories'));
    }

    public function create()
    {
        $categories = MenuSection::withCount('items')->orderBy('name')->get();
        return view('products.categorysetup', compact('categories'));
    }

    public function edit($id)
    {
        $category = MenuSection::findOrFail($id);
        $categories = MenuSection::withCount('items')->orderBy('name')->get();
        return view('products.categorysetup', compact('category', 'categories'));
    }
    public function getProducts($id)
{
    $category = MenuSection::findOrFail($id);
    $products = $category->menuItems; // Assuming a relationship exists

    return response()->json([
        'products' => $products
    ]);
}
    public function showProducts($id)
{
    $category = MenuSection::findOrFail($id);
    $products = $category->menuItems; // Assuming a relationship exists
    return view('category.products', compact('category', 'products'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'discount' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image',
        ]);

        $category = new MenuSection();
        $category->name = $validated['name'];
        $category->discount = $validated['discount'] ?? 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'category_' . time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('categories', $image, $imageName);
            // Web-accessible path
            $category->image = 'storage/categories/' . $imageName;
        }

        $category->save();

        return back()->with('status', 'Category created');
    }

    public function update(Request $request, $id)
    {
        $category = MenuSection::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'discount' => 'nullable|numeric|min:0|max:100',
            'image' => 'nullable|image',
        ]);

        $category->name = $validated['name'];
        $category->discount = $validated['discount'] ?? 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists (both legacy and storage paths)
            if ($category->image) {
                // Remove from public path if present
                $oldPublicPath = public_path($category->image);
                if (is_file($oldPublicPath)) { @unlink($oldPublicPath); }
                // Remove from storage disk using filename
                $oldBase = basename($category->image);
                if ($oldBase) { Storage::disk('public')->delete('categories/' . $oldBase); }
            }

            $image = $request->file('image');
            $imageName = 'category_' . time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('categories', $image, $imageName);
            // Web-accessible path
            $category->image = 'storage/categories/' . $imageName;
        }

        $category->save();

        return back()->with('status', 'Category updated');
    }

    public function destroy(Request $request, $id)
    {
        $category = MenuSection::findOrFail($id);

        // Delete associated image if exists (both legacy and storage paths)
        if ($category->image) {
            $oldPublicPath = public_path($category->image);
            if (is_file($oldPublicPath)) { @unlink($oldPublicPath); }
            $oldBase = basename($category->image);
            if ($oldBase) { Storage::disk('public')->delete('categories/' . $oldBase); }
        }

        // If you need to handle items, you can move/delete here. For now, just delete the category.
        $category->delete();
        return response()->json(['success' => true]);
    }
}

