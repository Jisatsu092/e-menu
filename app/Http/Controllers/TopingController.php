<?php

namespace App\Http\Controllers;

use App\Models\Toping;
use Illuminate\Http\Request;

class TopingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $search = request('search');
            $entries = request('entries', 5);

            $tables = Table::when($search, function ($query) use ($search) {
                $query->where('number', 'like', "%$search%");
            })
            ->paginate($entries)
            ->withQueryString();

            return view('page.table.index', [
                'tables' => $tables,
                'search' => $search,
                'entries' => $entries
            ]);
        } catch (\Exception $e) {
            return redirect()->route('error.index')->with('error_message', 'Error: ' . $e->getMessage());
        }
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
    public function show(Toping $toping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Toping $toping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Toping $toping)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Toping $toping)
    {
        //
    }
}
