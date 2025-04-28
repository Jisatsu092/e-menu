<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Table;
use App\Models\User;
use App\Models\PaymentProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        try {
            $search = request('search');
            $entries = request('entries', 10);


            $transactions = Transaction::with('table', 'user', 'paymentProvider')
                ->when($search, function ($query) use ($search) {
                    $query->whereHas('table', function ($q) use ($search) {
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
            $paymentProviders = PaymentProvider::all();

            return view('page.transaction.index', [
                'transactions' => $transactions,
                'paymentProviders' => $paymentProviders,
                'availableTables' => $availableTables,
                'tables' => $tables,
                'users' => $users,
                'search' => $search,
                'entries' => $entries
            ]);
        } catch (\Exception $e) {
            return redirect()->route('error.index')
                ->with('error_message', 'Error: ' . $e->getMessage());
        }
    }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'table_id' => 'required|exists:tables,id',
                'total_price' => 'required|numeric|min:0',
                'status' => 'required|in:pending,proses,paid,cancelled',
                'payment_provider_id' => 'required|exists:payment_providers,id',
                'spiciness_level' => 'required|in:mild,medium,hot,extreme',
                'bowl_size' => 'required|in:small,medium,large',
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            $transaction = Transaction::create($validated + ['payment_proof' => $proofPath]);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat',
                'data' => $transaction
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Validasi gagal'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        try {
            $request->validate(['status' => 'required|in:pending,proses,paid,cancelled']);

            $transaction->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Di TransactionController.php - method update
    public function update(Request $request, $id)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,cancelled,proses',
            'payment_provider_id' => 'required|exists:payment_providers,id',
            'spiciness_level' => 'required|in:mild,medium,hot,extreme',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bowl_size' => 'required|in:small,medium,large',
        ]);

        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($id);
            $data = $request->all();

            if ($request->hasFile('payment_proof')) {
                $data['payment_proof'] = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            // Update status meja
            if ($request->status !== $transaction->status) {
                $newStatus = ($request->status === 'paid') ? 'available' : 'occupied';
                $transaction->table->update(['status' => $newStatus]);
            }

            $transaction->update($data);

            DB::commit();
            return redirect()->route('transaction.index')
                ->with('message_insert', 'Transaksi berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('transaction.index')
                ->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::findOrFail($id);

            if ($transaction->status === 'paid') {
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

    public function process(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => 'proses']);

        return response()->json(['success' => 'Status updated to Proses']);
    }

    public function confirmPayment(Request $request)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'order_data' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $orderData = json_decode($request->order_data);
            $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

            Transaction::create([
                'user_id' => Auth::id(),
                'table_id' => $orderData->table_id,
                'bowl_size' => $orderData->bowl_size,
                'spiciness_level' => $orderData->spiciness_level,
                'total_price' => $orderData->total_price,
                'payment_proof' => $proofPath,
                'status' => 'paid'
            ]);

            Table::find($orderData->table_id)->update(['status' => 'occupied']);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function report(Request $request)
    {
        $minDate = Transaction::min('created_at') ?? now();
        $maxDate = Transaction::max('created_at') ?? now();

        $query = Transaction::with(['user', 'table', 'paymentProvider'])
            ->when($request->start && $request->end, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->start, $request->end]);
            })
            ->when($request->search, function ($q, $search) {
                return $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%$search%");
                })
                    ->orWhereHas('table', function ($t) use ($search) {
                        $t->where('number', 'like', "%$search%");
                    })
                    ->orWhere('total_price', 'like', "%$search%");
            });

        $transactions = $query->paginate(10);

        return view('page.transaction.report', [
            'transactions' => $transactions,
            'minDate' => $minDate ? Carbon::parse($minDate)->format('Y-m-d') : now()->toDateString(),
            'maxDate' => $maxDate ? Carbon::parse($maxDate)->format('Y-m-d') : now()->toDateString()

        ]);
    }

    public function printAll(Request $request)
    {
        $query = Transaction::with(['user', 'table', 'paymentProvider'])
            ->when($request->start && $request->end, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->start, $request->end]);
            });

        $transactions = $query->get();

        return view('page.transaction.print-all', compact('transactions'));
    }

    public function print($id)
    {
        $transaction = Transaction::with(['user', 'table', 'paymentProvider'])
            ->findOrFail($id);

        return view('page.transaction.print', compact('transaction'));
    }
}
