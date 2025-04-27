<?php

namespace App\Http\Controllers;

use App\Models\PaymentProvider;
use App\Models\Toping;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentProviders = PaymentProvider::latest()->paginate(10);
        return view('page.payment_provider.index',[
            'providers' => $paymentProviders
        ]
    );
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

    public function checkout()
{
    $providers = PaymentProvider::where('is_active', true)->get();
    return view('checkout', compact('providers'));
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
    
    return response()->json([
        'success' => true,
        'message' => 'Status updated successfully',
        'new_status' => $payment_provider->is_active
    ]);
}

    public function confirmPayment(Request $request)
{
    try {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'provider_id' => 'required|exists:payment_providers,id',
        ]);

        // Decode transaction data
        $transactionData = json_decode($request->transaction_data, true);

        // Simpan payment proof
        $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

        // Create transaction
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'table_id' => $transactionData['table_id'],
            'spiciness_level' => $transactionData['spiciness_level'],
            'status' => 'paid', // Status langsung paid
            'total_price' => $transactionData['total_price'],
            'payment_proof' => $paymentProofPath,
            'payment_provider_id' => $request->provider_id,
        ]);

        // Create transaction Items
        foreach ($transactionData['items'] as $item) {
            $transaction->items()->create([
                'toping_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Update stok topping
            $toping = Toping::find($item['id']);
            $toping->stock -= $item['quantity'];
            $toping->save();
        }

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}