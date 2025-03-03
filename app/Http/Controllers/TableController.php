<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::paginate(5);
        return view('page.table.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $data = [
            'number' => $request->number,
            'status' => 'available'
        ];

        Table::create($data);
        return redirect()->route('table.index')->with('success', 'Data meja berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $table = Table::findOrFail($id);
        
        $data = [
            'number' => $request->number,
            'status' => $request->status
        ];

        $table->update($data);
        return redirect()->route('table.index')->with('success', 'Data meja berhasil diupdate');
    }

    public function destroy($id)
    {
        try {
            $table = Table::findOrFail($id);
            $table->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function checkNumber($number, Request $request)
    {
        $id = $request->query('id');
        $query = Table::where('number', $number);
        
        if ($id) {
            $query->where('id', '!=', $id);
        }

        return response()->json(['exists' => $query->exists()]);
    }
}