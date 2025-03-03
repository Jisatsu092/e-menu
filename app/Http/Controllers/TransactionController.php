<?php
namespace App\Http\Controllers;

use App\Models\Toping;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller {
    // Tampilkan semua transaksi
    public function index() {
        $transactions = Transaction::with('toppings')->get();
        return response()->json($transactions);
    }

    // Simpan transaksi baru dengan topping yang dipilih
    public function store(Request $request) {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'toppings' => 'array', // Array berisi ID topping
            'toppings.*' => 'exists:toppings,id', // Setiap topping harus ada di tabel toppings
        ]);

        // Buat transaksi baru
        $transaction = Transaction::create([
            'table_id' => $request->table_id,
            'total_price' => 0, // Akan dihitung berdasarkan topping
            'status' => 'pending'
        ]);

        // Simpan topping yang dipilih ke pivot table
        $transaction->toppings()->attach($request->toppings);

        // Hitung total harga berdasarkan topping yang dipilih
        $totalPrice = Toping::whereIn('id', $request->toppings)->sum('price');
        $transaction->update(['total_price' => $totalPrice]);

        return response()->json(['message' => 'Transaksi berhasil dibuat', 'transaction' => $transaction]);
    }

    // Tampilkan detail transaksi
    public function show($id) {
        $transaction = Transaction::with('toppings')->findOrFail($id);
        return response()->json($transaction);
    }
}
