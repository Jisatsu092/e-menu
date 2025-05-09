<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class TableController extends Controller
{
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
        } catch (Exception $e) {
            return redirect()
                ->route('error.index')
                ->with('error_message', 'Terjadi kesalahan saat memuat data meja: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Auto generate number
            $lastTable = Table::orderBy('number', 'desc')->first();
            $nextNumber = $lastTable ? (int) str_replace('MEJA-', '', $lastTable->number) + 1 : 1;

            $table = Table::create([
                'number' => 'MEJA-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT),
                'status' => 'available'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'table' => $table
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }

        $table = Table::findOrFail($request->table_id);

        if ($table->is_occupied) {
            return response()->json([
                'success' => false,
                'message' => 'Meja sudah dipakai'
            ], 400);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'number' => 'sometimes|required|max:255|unique:tables,number,' . $id, // Tambahkan 'sometimes'
                'status' => 'required|in:available,occupied'
            ]);

            $table = Table::findOrFail($id);

            // Update hanya field yang ada di request
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

    public function destroy($id)
    {
        try {
            $table = Table::findOrFail($id);
            $table->delete();

            return response()->json([
                'success' => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkNumber($number)
    {
        try {
            $exists = Table::where('number', $number)->exists();
            return response()->json(['exists' => $exists]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
