<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransactionController extends Controller
{
    public function index()
    {
        try {
            $search = request('search');
            $entries = request('entries', 10);

            
            $transactions = Transaction::with('table')
                ->when($search, function($query) use ($search) {
                    $query->whereHas('table', function($q) use ($search) {
                        $q->where('number', 'like', "%$search%");
                    })
                    ->orWhere('total_price', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%");
                })
                ->orderBy('created_at', 'desc')
                ->paginate($entries)
                ->withQueryString();

            $availableTables = Table::where('status', 'available')->get();
            $tables = Table::all();
            $users = User::all();

            return view('page.transaction.index', [
                'transactions' => $transactions,
                'availableTables' => $availableTables,
                'tables' => $tables,
                'users' => $users,
                'search' => $search,
                'entries' => $entries
            ]);
            
        } catch(\Exception $e) {
            return redirect()->route('error.index')
                ->with('error_message', 'Error: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'table_id' => 'required|exists:tables,id',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,cancelled'
        ]);

        DB::beginTransaction();
        try {
            $table = Table::findOrFail($request->table_id);
            $user = User::findOrFail($request->user_id);
            
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'table_id' => $request->table_id,
                'total_price' => $request->total_price,
                'status' => $request->status
            ]);

            $table->update([
                'status' => ($request->status === 'paid') ? 'available' : 'occupied'
            ]);

            DB::commit();
            return redirect()->route('transaction.index')
                ->with('message_insert', 'Transaksi berhasil ditambahkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('error.index')
                ->with('error_message', 'Gagal menambahkan transaksi: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,cancelled'
        ]);

        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($id);
            $table = $transaction->table;
            
            if($request->status !== $transaction->status) {
                $newStatus = ($request->status === 'paid') ? 'available' : 'occupied';
                $table->update(['status' => $newStatus]);
            }

            $transaction->update($request->all());
            
            DB::commit();
            return redirect()->route('transaction.index')
                ->with('message_insert', 'Transaksi berhasil diperbarui');
                
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('error.index')
                ->with('error_message', 'Transaksi tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('error.index')
                ->with('error_message', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($id);
            
            if($transaction->status === 'paid') {
                $transaction->table->update(['status' => 'available']);
            }
            
            $transaction->delete();
            
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus'
            ]);
            
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message_delete' => 'Transaksi tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error_message' => 'Gagal menghapus transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}