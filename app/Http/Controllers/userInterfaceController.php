<?php

namespace App\Http\Controllers;

use App\Models\PaymentProvider;
use App\Models\Table;
use App\Models\Toping;
use Illuminate\Http\Request;

class userInterfaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topings = Toping::all();
        $paymentProviders = PaymentProvider::all();
        $tables = Table::select('id', 'number')->orderBy('number')->get();
        return view('page.user_interface.index', [
            'topings' => $topings,
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
        //
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
}
