<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->input('page', 1); 
        $entries = request()->input('entries', 10); 
        $search = request()->input('search');
        $category= Category::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%'); 
        })->paginate($entries);

        // $category = Category::paginate(5);
        return view('page.category.index')->with([
            'category' => $category
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Category::all();
        return response()->json([
            'data' => $data,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
        ];

        Category::create($data);
        

        return back()->with('success', 'Data Kategori Sudah ditambahkan');
        return response()->json([
            'succes' => "Data Added!"
        ]);
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
        $data = [
            'name' => $request->input('name'),
        ];

        $datas = Category::findOrFail($id);
        $datas->update($data);
        return back()->with('message_update', 'Data Kategori Sudah diupdate');
        return response()->json([
            'message_update' => "Data Updated!"
        ]); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
    
            // Kembalikan respons sukses
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            // Tangkap error jika terjadi kesalahan
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkName($name)
    {
        $exists = Category::where('name', $name)->exists();
        return response()->json(['exists' => $exists]);
    }
}
