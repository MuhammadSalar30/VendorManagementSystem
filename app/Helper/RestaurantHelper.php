<?php

// namespace App\Helper;
use App\Models\MenuItem;
use App\Models\MenuSection;
use App\Models\Currency;
// class RestaurantHelper
// {


    if (!function_exists('saveProduct')) {
        function saveProduct($data, $id = null)
        {
            // Validate the data
            $validatedData = validator($data, [
                'name' => 'required|string|max:255',
                'menu_section_id' => 'required|exists:menu_sections,id',
                'price' => 'required|integer',
                'cost_price' => 'required|integer',
                'order_type' => 'required|array', // Ensure it's an array
                'order_type.*' => 'in:delivery,pickup,dine_in', // Validate each value
                'size' => 'nullable|string',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png|max:2048',
                'additional_image_1' => 'nullable|image|mimes:jpeg,png|max:2048',
                'additional_image_2' => 'nullable|image|mimes:jpeg,png|max:2048',
            ])->validate();

            // Convert order_type to JSON
            $validatedData['order_type'] = json_encode($validatedData['order_type']);

            // Handle image uploads
            if (isset($data['image']) && $data['image']->isValid()) {
                $validatedData['image'] = $data['image']->store('products');
            }

            if (isset($data['additional_image_1']) && $data['additional_image_1']->isValid()) {
                $validatedData['additional_image_1'] = $data['additional_image_1']->store('products');
            }

            if (isset($data['additional_image_2']) && $data['additional_image_2']->isValid()) {
                $validatedData['additional_image_2'] = $data['additional_image_2']->store('products');
            }

            if ($id) {
                // Update existing product
                $product = MenuItem::find($id);
                if ($product) {
                    $product->update($validatedData);
                    return $product;
                }
                return null;
            } else {
                // Create new product
                return MenuItem::create($validatedData);
            }
        }
    }

    if (!function_exists('getRestaurantName')) {
        function getRestaurantName()
        {
            return "My Restaurant 123";
        }
    }

    if (!function_exists('AllItems')) {
        function AllItems()
        {
            return MenuItem::get();
            // return "My Restaurant 123";
        }
    }
    if (!function_exists('GetCategoryName')) {
        function GetCategoryName($CategoryID)
        {
            $Category= MenuSection::find($CategoryID);
            if($Category){

                return $Category->name;
            }
            return '';
            // return "My Restaurant 123";
        }
    }

    if (!function_exists('AllCategory')) {
        function AllCategory()
        {
            return MenuSection::get();
            // return "My Restaurant 123";
        }
    }
if (!function_exists('getCurrentCurrency')) {
    function getCurrentCurrency()
    {
        $code = session('currency_code', null);
        $currency = null;
        if ($code) {
            $currency = Currency::where('code', $code)->first();
        }
        if (!$currency) {
            $currency = Currency::where('is_default', true)->first() ?? Currency::first();
        }
        return $currency;
    }
}

if (!function_exists('currency_to_display')) {
    // $amountBase = amount stored in base currency (e.g. PKR)
    function currency_to_display($amountBase)
    {
        $c = getCurrentCurrency();
        if (!$c || !((float)$c->rate > 0)) {
            return (float)$amountBase;
        }
        // rate = base-per-unit (e.g. PKR per USD) — convert base -> display
        return (float)$amountBase / (float)$c->rate;
    }
}

if (!function_exists('currency_format')) {
    function currency_format($amountBase, $decimals = 2)
    {
        $c = getCurrentCurrency();
        $display = currency_to_display($amountBase);
        $symbol = $c->symbol ?? $c->code ?? '';
        return trim($symbol . ' ' . number_format((float)$display, $decimals));
    }
}

