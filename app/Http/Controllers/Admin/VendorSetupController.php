<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorSetupController extends Controller
{
    public function index()
    {
        \Log::info('VendorSetupController index method called');
        $title = "Vendor List";
        $vendors = Vendor::all();
        \Log::info('Vendors count: ' . $vendors->count());
        return view('vendor.list', compact('vendors'));
    }

    public function create()
    {
        return view('vendor.create');
    }
    public function store(Request $request)
{
    // 🚨 TEMPORARY TEST - COMMENT OUT THE EXISTING CODE



    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:vendors,email',
        'phone' => 'required',
        'city' => 'required',
        'country' => 'required',
        'status' => 'required',
    ]);

    $vendor = Vendor::create($request->all());

    return redirect()->route('admin.vendors.index')->with('success', 'Vendor created successfully.');
}
    public function edit(Vendor $vendor)
    {
        return view('vendor.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:vendors,email,'.$vendor->id,
            'phone' => 'required',
            'city' => 'required',
            'country' => 'required',
            'status' => 'required',
        ]);

        $vendor->update($request->all());

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('admin.vendors.index')->with('success', 'Vendor deleted successfully.');
    }
}
