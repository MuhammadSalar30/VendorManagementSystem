<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::orderBy('id', 'desc')->get() ?? collect();
            // dd(['count'=>$currencies->count(), 'rows' => $currencies->toArray()]);
              dd($currencies);

        return view('admin.currency-settings', compact('currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|max:10',
            'symbol' => 'required|string|max:10',
            'rate'   => 'nullable|numeric',
        ]);

        Currency::create([
            'name'       => $request->name,
            'code'       => strtoupper($request->code),
            'symbol'     => $request->symbol,
            'rate'       => $request->input('rate', 1),
            'is_default' => false,
        ]);

        return redirect()->route('admin.currency-settings.index')->with('success', 'Currency added successfully.');
    }

    public function setDefault($id)
    {
        Currency::query()->update(['is_default' => false]);
        $currency = Currency::findOrFail($id);
        $currency->update(['is_default' => true]);

        return redirect()->route('admin.currency-settings.index')->with('success', 'Default currency updated.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|max:10',
            'symbol' => 'required|string|max:10',
            'rate'   => 'nullable|numeric',
        ]);

        $currency = Currency::findOrFail($id);
        $currency->update($request->only('name','code','symbol','rate'));

        return redirect()->route('admin.currency-settings.index')->with('success', 'Currency updated.');
    }

    public function destroy($id)
    {
        Currency::findOrFail($id)->delete();
        return redirect()->route('admin.currency-settings.index')->with('success', 'Currency deleted.');
    }

}
