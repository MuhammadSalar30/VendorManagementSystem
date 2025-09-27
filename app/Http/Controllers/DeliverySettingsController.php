<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class DeliverySettingsController extends Controller
{
    public function saveDeliverySettings(Request $request)
    {
        $validatedData = $request->validate([
            'taxCashOnDelivery' => 'required|numeric',
            'taxOnlinePayment' => 'required|numeric',
            'defaultDeliveryCharge' => 'required|numeric',
            'areaBasedDelivery' => 'required|boolean',
            'areaCharges' => 'nullable|array',
            'areaCharges.*.areaName' => 'required|string',
            'areaCharges.*.areaCharge' => 'required|numeric',
        ]);

        // Save settings to the database or configuration file
        // Example: Save to a settings table
        foreach ($validatedData as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => json_encode($value)]);
        }

        return response()->json(['message' => 'Settings saved successfully!']);
    }

    public function deleteDeliverySettings(Request $request)
    {
        $validatedData = $request->validate([
            'taxCashOnDelivery' => 'required|numeric',
            'taxOnlinePayment' => 'required|numeric',
            'defaultDeliveryCharge' => 'required|numeric',
            'areaBasedDelivery' => 'required|boolean',
            'areaCharges' => 'nullable|array',
            'areaCharges.*.areaName' => 'required|string',
            'areaCharges.*.areaCharge' => 'required|numeric',
        ]);

        foreach ($validatedData as $key => $value) {
            Setting::where('key', $key)->delete();
        }

        return response()->json(['message' => 'Settings deleted successfully']);
    }
}
