<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuSection;
use App\Models\MenuItem;

class MenuSectionController extends Controller
{

    public function destroy($id)
    {
        $section = MenuSection::find($id);
        if ($section) {
            $section->delete();
        }
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back()->with('status', 'Category deleted');
    }
    public function saveProduct(Request $request)
    {
        // Normalize JSON string inputs for arrays before validation (from hidden inputs)
        if ($request->has('size') && is_string($request->input('size'))) {
            $decodedSize = json_decode($request->input('size'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['size' => $decodedSize]);
            }
        }
        if ($request->has('size_prices') && is_string($request->input('size_prices'))) {
            $decodedPrices = json_decode($request->input('size_prices'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['size_prices' => $decodedPrices]);
            }
        }

        // Validate the request data
        $validatedData = $request->validate([
            'id' => 'nullable|numeric',
            'name' => 'required|string|max:255',
            'menu_section_id' => 'required|exists:menu_sections,id',
            'price' => 'required|numeric',
            'cost_price' => 'required|numeric',
            'order_type' => 'required',
            'size' => 'nullable|array',
            'size_prices' => 'nullable|array',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'additional_image_1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'additional_image_2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);


        // dd($validatedData);

        try {
            // Convert order_type to JSON if it's a string
            if (is_string($validatedData['order_type'])) {
                $validatedData['order_type'] = json_decode($validatedData['order_type'], true);
            }

            // Handle size and size_prices - combine them into a structured array
            if (isset($validatedData['size']) && isset($validatedData['size_prices'])) {
                $sizeData = [];
                foreach ($validatedData['size'] as $index => $size) {
                    if (!empty($size)) {
                        $sizeData[] = [
                            'name' => $size,
                            'price' => isset($validatedData['size_prices'][$index]) ? (float)$validatedData['size_prices'][$index] : $validatedData['price']
                        ];
                    }
                }
                $validatedData['size'] = $sizeData;
            } else {
                $validatedData['size'] = [];
            }

            // Remove size_prices from validated data as it's now combined with size
            unset($validatedData['size_prices']);

            // Handle image uploads
            if ($request->hasFile('image')) {
                $validatedData['image'] = $this->uploadImage($request->file('image'), 'product');
            }

            if ($request->hasFile('additional_image_1')) {
                $validatedData['additional_image_1'] = $this->uploadImage($request->file('additional_image_1'), 'product_additional_1');
            }

            if ($request->hasFile('additional_image_2')) {
                $validatedData['additional_image_2'] = $this->uploadImage($request->file('additional_image_2'), 'product_additional_2');
            }
            if(isset($validatedData['id'])){
                MenuItem::where('id',$validatedData['id'])->update($validatedData);
            }
            else{
                // Save the product
                MenuItem::create($validatedData);
            }

            return response()->json([
                'message' => 'Product saved successfully.',
            ], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to save product: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to save product. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //\Log::info('Request Data:', $request->all());


    private function uploadImage($file, $prefix)
    {
        $imageName = $prefix . '_' . time() . '.' . $file->getClientOriginalExtension();
        // Use Laravel Filesystem to handle cross-platform permissions reliably
        $stored = \Illuminate\Support\Facades\Storage::disk('public')
            ->putFileAs('products', $file, $imageName);

        if (!$stored) {
            throw new \RuntimeException('Failed to store image using public disk.');
        }

        // Return web-accessible path via public/storage symlink
        return 'storage/products/' . $imageName;
    }
}


