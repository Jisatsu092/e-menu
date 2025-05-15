<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PaymentProvider;
use App\Models\Table;
use App\Models\Toping;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'number' => 'sometimes|required|max:255|unique:tables,number,' . $id,
                'status' => 'required|in:available,occupied'
            ]);

            $table = Table::findOrFail($id);

            if ($request->status === 'occupied' && $table->status === 'occupied') {
                return response()->json([
                    'success' => false,
                    'message' => 'Meja sudah dipesan'
                ], 400);
            }

            $table->update($request->only(['number', 'status']));

            return response()->json([
                'success' => true,
                'table' => $table
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
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
        // Validasi input
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'provider_id' => 'required|exists:payment_providers,id',
            'order_data' => 'required|json',
        ]);

        try {
            DB::beginTransaction();

            // Parse order data
            $orderData = json_decode($request->input('order_data'), true);

            // Validasi orderData
            if (!isset($orderData['table_id'], $orderData['spiciness_level'], $orderData['bowl_size'], $orderData['total_price'], $orderData['items']) || empty($orderData['items'])) {
                throw new \Exception('Data pesanan tidak lengkap');
            }

            // Verifikasi status meja
            $table = Table::findOrFail($orderData['table_id']);
            if ($table->status === 'occupied') {
                throw new \Exception('Meja sudah dipesan');
            }

            // Buat transaksi
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'table_id' => $orderData['table_id'],
                'spiciness_level' => $orderData['spiciness_level'],
                'bowl_size' => $orderData['bowl_size'],
                'total_price' => $orderData['total_price'],
                'status' => 'pending',
                'payment_provider_id' => $request->provider_id,
            ]);

            // Update status meja
            $table->update(['status' => 'occupied']);

            // Proses detail transaksi dan update stok
            foreach ($orderData['items'] as $item) {
                if (!isset($item['id'], $item['quantity']) || $item['quantity'] <= 0) {
                    throw new \Exception('Data item tidak valid');
                }

                $toping = Toping::findOrFail($item['id']);
                
                // Verifikasi stok
                if ($toping->stock < $item['quantity']) {
                    throw new \Exception("Stok {$toping->name} tidak cukup");
                }

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'toping_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $toping->price * $item['quantity'],
                ]);

                // Kurangi stok
                $toping->decrement('stock', $item['quantity']);
            }

            // Simpan buk配bukti pembayaran
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $transaction->update(['payment_proof' => $path]);

            DB::commit();

            return response()->json([
                'success' => true,
                'transactionId' => $transaction->id,
                'status' => $transaction->status,
                'message' => 'Pembayaran berhasil dikonfirmasi, menunggu verifikasi'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment confirmation failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
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
    public function updateStatus(Request $request, $id)
{
    try {
        $request->validate([
            'status' => 'required|in:available,occupied'
        ]);

        $table = Table::findOrFail($id);
        $table->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status meja berhasil diupdate'
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal update status meja: ' . $e->getMessage()
        ], 500);
    }
}
}
