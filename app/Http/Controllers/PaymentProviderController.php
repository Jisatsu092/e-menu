<?php

namespace App\Http\Controllers;

use App\Models\PaymentProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $providers = PaymentProvider::latest()->paginate(10);
        return view('page.payment_provider.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payment_providers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'account_number' => 'required|max:255',
            'account_name' => 'required|max:255',
            'type' => 'required|in:e-wallet,bank,other',
            'instructions' => 'nullable',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('payment_providers', 'public');
        }

        PaymentProvider::create($validated);

        return redirect()->route('payment_providers.index')
            ->with('success', 'Payment provider created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentProvider $payment_provider)
    {
        return view('payment_providers.edit', compact('payment_provider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentProvider $payment_provider)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'account_number' => 'required|max:255',
            'account_name' => 'required|max:255',
            'type' => 'required|in:e-wallet,bank,other',
            'instructions' => 'nullable',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            if ($payment_provider->logo) {
                Storage::disk('public')->delete($payment_provider->logo);
            }
            $validated['logo'] = $request->file('logo')->store('payment_providers', 'public');
        }

        $payment_provider->update($validated);

        return redirect()->route('payment_providers.index')
            ->with('success', 'Payment provider updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentProvider $payment_provider)
    {
        if ($payment_provider->logo) {
            Storage::disk('public')->delete($payment_provider->logo);
        }

        $payment_provider->delete();

        return redirect()->route('payment_providers.index')
            ->with('success', 'Payment provider deleted successfully');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(PaymentProvider $payment_provider)
    {
        $payment_provider->update(['is_active' => !$payment_provider->is_active]);
        return back()->with('success', 'Status updated successfully');
    }
}