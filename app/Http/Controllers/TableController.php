<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = [
                'number' => $request->input('number'),
                'status' => 'available',
            ];

            // Validasi input
            $request->validate([
                'number' => 'required|unique:tables|max:255',
            ]);

            Table::create($data);

            return redirect()
                ->route('table.index')
                ->with('message_insert', 'Data meja berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->route('error.index')
                ->with('error_message', 'Terjadi kesalahan saat menambahkan data meja: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = [
                'number' => $request->input('number'),
                'status' => $request->input('status', 'available'), // Default status jika tidak diisi
            ];

            // Validasi input
            $request->validate([
                'number' => 'required|max:255|unique:tables,number,' . $id,
            ]);

            $table = Table::findOrFail($id);
            $table->update($data);

            return redirect()
                ->route('table.index')
                ->with('message_update', 'Data meja berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->route('error.index')
                ->with('error_message', 'Terjadi kesalahan saat memperbarui data meja: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $table = Table::findOrFail($id);
            $table->delete();

            return back()->with('message_delete', 'Data meja berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error_message', 'Terjadi kesalahan saat menghapus data meja: ' . $e->getMessage());
        }
    }

    /**
     * Check if a table number exists.
     */
    // TableController.php
    public function checkNumber($number)
    {
        $exists = Table::where('number', $number)->exists();
        return response()->json(['exists' => $exists]);
    }
}
