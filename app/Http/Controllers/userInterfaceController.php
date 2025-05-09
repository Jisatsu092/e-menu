<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PaymentProvider;
use App\Models\Table;
use App\Models\Toping;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class userInterfaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topings = Toping::all();
        $paymentProviders = PaymentProvider::all();
        $categories = Category::all();
        $tables = Table::select('id', 'number')->orderBy('number')->get();
        return view('page.user_interface.index', [
            'topings' => $topings,
            'categories' => $categories,
            'tables' => $tables,
            'paymentProviders' => $paymentProviders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
        ]);

        // Update status meja jadi occupied
        $table = Table::find($request->table_id);
        $table->status = 'occupied';
        $table->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function confirmPayment(Request $request)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'provider_id' => 'required|exists:payment_providers,id',
        ]);

        try {
            DB::beginTransaction();

            // Parse order data
            $orderData = json_decode($request->input('order_data'), true);

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'table_id' => $orderData['table_id'],
                'spiciness_level' => $orderData['spiciness_level'],
                'bowl_size' => $orderData['bowl_size'],
                'total_price' => $orderData['total_price'],
                'status' => 'pending',
                'payment_provider_id' => $request->provider_id,
            ]);

            // TAMBAHKAN INI: Update status meja
            $table = Table::find($orderData['table_id']);
            $table->update(['status' => 'occupied']);

            // Create transaction details
            foreach ($orderData['items'] as $item) {
                $toping = Toping::findOrFail($item['id']);

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'toping_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $toping->price * $item['quantity'],
                ]);

                // Update stock
                $toping->decrement('stock', $item['quantity']);
            }

            // Handle payment proof
            if ($request->hasFile('payment_proof')) {
                $path = $request->file('payment_proof')->store('payment_proofs', 'public');
                $transaction->update(['payment_proof' => $path]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'transactionId' => $transaction->id,
                'status' => $transaction->status
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error processing payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function completeTransaction($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->table->update(['status' => 'available']);

            return response()->json([
                'success' => true,
                'message' => 'Status meja telah diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
