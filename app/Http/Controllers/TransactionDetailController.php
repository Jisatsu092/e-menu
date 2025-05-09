<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Toping;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class TransactionDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactionDetail = TransactionDetail::latest()->paginate(10);
        $transaction = Transaction::all();
        $topings = Toping::all();
        return view('page.transaction_detail.index', [
            'details' => $transactionDetail,
            'transaction' => $transaction,
            'toping' => $topings
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load([
            'details.toping',
            'user',
            'table'
        ]);

        return view('transactions.details.show', compact('transaction'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionDetail $transactionDetail)
    {
        //
    }

    public function destroyAll()
    {
        TransactionDetail::query()->delete();
        return redirect()->back()->with('success', 'All transaction details cleared successfully.');
    }

    public function report()
    {
        $transactions = TransactionDetail::with(['transaction', 'toping'])
            ->get()
            ->groupBy('transaction_id');

        return view('page.transaction_detail.report', compact('transactions'));
    }
}
