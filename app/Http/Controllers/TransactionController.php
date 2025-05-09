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
use Illuminate\Support\Facades\Storage;

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

    public function show($id)
    {
        abort(404); // atau redirect ke halaman lain
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

            // Cek status meja
            $table = Table::findOrFail($validated['table_id']);
            if ($table->status === 'occupied') {
                return response()->json([
                    'success' => false,
                    'message' => 'Meja sedang digunakan'
                ], 400);
            }

            // Simpan file ke storage
            $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

            // Buat transaksi dengan path relatif
            $transaction = Transaction::create([
                'user_id' => $validated['user_id'],
                'table_id' => $validated['table_id'],
                'total_price' => $validated['total_price'],
                'status' => $validated['status'],
                'payment_provider_id' => $validated['payment_provider_id'],
                'spiciness_level' => $validated['spiciness_level'],
                'bowl_size' => $validated['bowl_size'],
                'payment_proof' => $proofPath
            ]);

            // Update status meja setelah transaksi berhasil dibuat
            $table->update(['status' => 'occupied']);

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
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
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
                if ($transaction->payment_proof) {
                    Storage::disk('public')->delete($transaction->payment_proof);
                }
                $data['payment_proof'] = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            // Perubahan logika status meja
            if ($request->status !== $transaction->status) {
                $newStatus = ($request->status === 'cancelled') ? 'available' : 'occupied';
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

            // Hapus file payment proof
            if ($transaction->payment_proof) {
                Storage::disk('public')->delete($transaction->payment_proof);
            }

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

            if (!isset($orderData->bowl_size)) {
                throw new \Exception('Bowl size is required');
            }

            $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'table_id' => $orderData->table_id,
                'bowl_size' => $orderData->bowl_size,
                'spiciness_level' => $orderData->spiciness_level,
                'total_price' => $orderData->total_price,
                'payment_provider_id' => $request->provider_id,
                'payment_proof' => $proofPath,
                'status' => 'paid'
            ]);

            // Set meja ke 'occupied' karena transaksi baru dibuat
            $transaction->table->update(['status' => 'occupied']);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function report(Request $request)
    {
        try {
            // Handle date filter
            $startDate = $request->start ? Carbon::parse($request->start) : null;
            $endDate = $request->end ? Carbon::parse($request->end)->endOfDay() : null;

            $query = Transaction::with(['user', 'table', 'paymentProvider'])
                ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                    return $q->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->when($request->search, function ($q, $search) {
                    return $q->where(function ($query) use ($search) {
                        $query->whereHas('user', function ($u) use ($search) {
                            $u->where('name', 'like', "%$search%");
                        })
                            ->orWhereHas('table', function ($t) use ($search) {
                                $t->where('number', 'like', "%$search%");
                            })
                            ->orWhere('total_price', 'like', "%$search%");
                    });
                })
                ->orderBy('created_at', 'desc');

            // Get min-max dates
            $minDate = Transaction::oldest('created_at')->value('created_at') ?? now();
            $maxDate = Transaction::latest('created_at')->value('created_at') ?? now();

            $transactions = $query->paginate(10)->withQueryString();

            return view('page.transaction.report', [
                'transactions' => $transactions,
                'minDate' => Carbon::parse($minDate)->format('Y-m-d'),
                'maxDate' => Carbon::parse($maxDate)->format('Y-m-d'),
                'request' => $request
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal memuat laporan: ' . $e->getMessage()]);
        }
    }
    public function printAll(Request $request)
    {
        $query = Transaction::with(['user', 'table', 'paymentProvider'])
            ->when($request->start && $request->end, function ($q) use ($request) {
                $start = Carbon::parse($request->start)->startOfDay();
                $end = Carbon::parse($request->end)->endOfDay();
                return $q->whereBetween('created_at', [$start, $end]);
            });

        $transactions = $query->get();

        return view('page.transaction.print-all', compact('transactions'));
    }

    public function print($id)
    {
        $transaction = Transaction::with([
            'details.toping',
            'user',
            'table',
            'paymentProvider'
        ])->findOrFail($id);

        return view('page.transaction.print', compact('transaction'));
    }

    // Contoh controller upload
    public function uploadPayment(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        $path = $request->file('payment_proof')->store('payment_proofs', 'public');
        $transaction->update(['payment_proof' => $path]);

        return back();
    }

    public function checkStatus($id)
    {
        $transaction = Transaction::findOrFail($id);
        return response()->json([
            'status' => $transaction->status
        ]);
    }
    // app/Http/Controllers/TransactionController.php
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
