@extends('layouts.admin', ['subtitle' => "Admin", 'title' => "Delivery & Tax Settings"])

@section('content')

<div class="p-6 border rounded-lg border-default-200 mb-6">
    <h4 class="text-xl font-medium text-default-900 mb-4">Tax Settings</h4>

    <div class="grid lg:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-default-900 mb-2" for="tax_cash_on_delivery">Tax for Cash on Delivery (%)</label>
            <input id="tax_cash_on_delivery" value="{{ (float)($taxCashOnDelivery ?? 0) }}" class="block w-full bg-transparent rounded-full py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200" type="number" placeholder="Enter tax percentage">
        </div>

        <div>
            <label class="block text-sm font-medium text-default-900 mb-2" for="tax_online_payment">Tax for Online Payment (%)</label>
            <input id="tax_online_payment" value="{{ (float)($taxOnlinePayment ?? 0) }}" class="block w-full bg-transparent rounded-full py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200" type="number" placeholder="Enter tax percentage">
        </div>
    </div>
</div>

<div class="p-6 border rounded-lg border-default-200 mb-6">
    <h4 class="text-xl font-medium text-default-900 mb-4">Delivery Charges</h4>

    <div class="grid lg:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-default-900 mb-2" for="default_delivery_charge">Default Delivery Charge</label>
            <input id="default_delivery_charge" value="{{ (float)($defaultDeliveryCharge ?? 0) }}" class="block w-full bg-transparent rounded-full py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200" type="number" placeholder="Enter default delivery charge">
        </div>

        <div>
            <label class="block text-sm font-medium text-default-900 mb-2" for="area_based_delivery_charge">Enable Area-Based Delivery Charges</label>
            <select id="area_based_delivery_charge" class="block w-full bg-transparent rounded-full py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200">
                <option value="0" {{ ($areaBasedDelivery ?? 0) ? '' : 'selected' }}>No</option>
                <option value="1" {{ ($areaBasedDelivery ?? 0) ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
    </div>

    <div id="areaDeliveryCharges" class="{{ ($areaBasedDelivery ?? 0) ? '' : 'hidden' }} mt-6">
        <h5 class="text-lg font-medium text-default-900 mb-4">Area-Based Delivery Charges</h5>
        <div class="grid lg:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-default-900 mb-2" for="area_name">Area Name</label>
                @php
                    $defaultAreas = [
                        'Gulshan-e-Iqbal','North Nazimabad','Clifton','Defence (DHA)','Korangi','Malir','Saddar','Tariq Road','Bahadurabad','Nazimabad','Federal B Area','Gulberg','Gulistan-e-Jauhar','Gulshan-e-Iqbal','Scheme 33','Gulshan-e-Maymar','Shah Faisal','Landhi','Orangi Town','Baldia Town','SITE','New Karachi','Surjani Town'
                    ];
                    $areaOptions = (isset($areas) && is_array($areas) && count($areas)) ? $areas : $defaultAreas;
                @endphp
                <select id="area_name" class="block w-full bg-transparent rounded-full py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200">
                    <option value="" selected>Select area</option>
                    @foreach($areaOptions as $area)
                        <option value="{{ $area }}">{{ $area }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-default-900 mb-2" for="area_delivery_charge">Delivery Charge</label>
                <input id="area_delivery_charge" class="block w-full bg-transparent rounded-full py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200" type="number" placeholder="Enter delivery charge">
            </div>

            <div class="lg:col-span-2">
                <button id="addAreaCharge" class="flex items-center justify-center gap-2 rounded-full border border-primary bg-primary px-6 py-2.5 text-center text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:border-primary-700 hover:bg-primary-500">Add Area</button>
            </div>
        </div>

        <div id="areaChargesList" class="mt-4">
            @foreach(($areaCharges ?? []) as $ac)
            <div class="flex justify-between items-center border border-default-200 p-4 rounded-lg mb-2">
                <span>{{ $ac['areaName'] ?? '' }}</span>
                <span>{{ $ac['areaCharge'] ?? 0 }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div>
    <button id="saveSettings" class="flex items-center justify-center gap-2 rounded-full border border-primary bg-primary px-6 py-2.5 text-center text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:border-primary-700 hover:bg-primary-500">Save Changes</button>
</div>

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const areaBasedDeliveryCharge = document.getElementById('area_based_delivery_charge');
        const areaDeliveryCharges = document.getElementById('areaDeliveryCharges');

        // Toggle area-based delivery charges section
        areaBasedDeliveryCharge.addEventListener('change', () => {
            if (areaBasedDeliveryCharge.value === '1') {
                areaDeliveryCharges.classList.remove('hidden');
            } else {
                areaDeliveryCharges.classList.add('hidden');
            }
        });

        // Add area-based delivery charge
        document.getElementById('addAreaCharge').addEventListener('click', () => {
            const areaName = document.getElementById('area_name').value;
            const areaCharge = document.getElementById('area_delivery_charge').value;

            if (areaName && areaCharge) {
                const areaList = document.getElementById('areaChargesList');
                const areaItem = document.createElement('div');
                areaItem.classList.add('flex', 'justify-between', 'items-center', 'border', 'border-default-200', 'p-4', 'rounded-lg', 'mb-2');
                areaItem.innerHTML = `
                    <span>${areaName}</span>
                    <span>${areaCharge}</span>
                `;
                areaList.appendChild(areaItem);

                // Clear inputs
                document.getElementById('area_name').value = '';
                document.getElementById('area_delivery_charge').value = '';
            } else {
                alert('Please enter both area name and delivery charge.');
            }
        });

        // Save settings
        document.getElementById('saveSettings').addEventListener('click', () => {
            const taxCashOnDelivery = document.getElementById('tax_cash_on_delivery').value;
            const taxOnlinePayment = document.getElementById('tax_online_payment').value;
            const defaultDeliveryCharge = document.getElementById('default_delivery_charge').value;
            const areaBasedDelivery = document.getElementById('area_based_delivery_charge').value;

            const areaCharges = [];
            document.querySelectorAll('#areaChargesList > div').forEach(item => {
                const areaName = item.children[0].textContent;
                const areaCharge = item.children[1].textContent;
                areaCharges.push({ areaName, areaCharge });
            });

            const settings = {
                taxCashOnDelivery,
                taxOnlinePayment,
                defaultDeliveryCharge,
                areaBasedDelivery,
                areaCharges
            };

            console.log('Settings to save:', settings);

            // Send settings to the server via AJAX
            fetch('/admin/delivery-settings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(settings)
            })
            .then(async (response) => {
                // Prefer JSON, fallback to plain text
                let data = null;
                const text = await response.text();
                try { data = JSON.parse(text); } catch (_) { data = { message: text || '' }; }
                return { ok: response.ok, data };
            })
            .then(({ ok, data }) => {
                if (ok) {
                    alert(data.message || 'Settings saved successfully!');
                } else {
                    alert('Failed to save settings: ' + (data && data.message ? data.message : 'Server error'));
                }
            })
            .catch(error => {
                console.error('Error saving settings:', error);
                alert('Failed to save settings.');
            });
        });
    });
</script>
@endsection
