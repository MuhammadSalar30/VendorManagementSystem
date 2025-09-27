<?php
namespace App\Http\Controllers\Admin;

use App\Models\Setting; // Ensure the Setting model is imported

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class AdminController extends Controller
{
    public function deliverySettings()
    {
        $taxCashOnDelivery = \App\Models\Setting::getValue('taxCashOnDelivery', 0);
        $taxOnlinePayment = \App\Models\Setting::getValue('taxOnlinePayment', 0);
        $defaultDeliveryCharge = \App\Models\Setting::getValue('defaultDeliveryCharge', 0);
        $areaBasedDelivery = (int) \App\Models\Setting::getValue('areaBasedDelivery', 0);
        $areaCharges = \App\Models\Setting::getValue('areaCharges', []);

        // Master list of areas (kept in sync with checkout fee areas)
        $areas = [
            'Gulshan-e-Iqbal','North Nazimabad','Clifton','Defence (DHA)','Korangi','Malir','Saddar','Tariq Road','Bahadurabad','Nazimabad','Federal B Area','Gulberg','Johar Town','Shah Faisal','Landhi','Orangi Town','Baldia Town','SITE','New Karachi','Surjani Town'
        ];

        return view('admin.delivery-settings', compact(
            'taxCashOnDelivery','taxOnlinePayment','defaultDeliveryCharge','areaBasedDelivery','areaCharges','areas'
        ));
    }

    public function saveDeliverySettings(Request $request)
    {
        $data = $request->validate([
            'taxCashOnDelivery' => 'required|numeric|min:0',
            'taxOnlinePayment' => 'required|numeric|min:0',
            'defaultDeliveryCharge' => 'required|numeric|min:0',
            'areaBasedDelivery' => 'required',
            'areaCharges' => 'nullable|array',
            'areaCharges.*.areaName' => 'required|string|max:255',
            'areaCharges.*.areaCharge' => 'required|numeric|min:0',
        ]);

        // Save settings to the database or configuration file
        // Example: Save to a settings table
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => json_encode($value)]);
        }

        return response()->json(['message' => 'Settings saved successfully.']);
    }
}
