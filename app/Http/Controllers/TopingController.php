<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Toping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
    
            $topings = Toping::with('category') // Eager load category
                ->when($search, function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                })
                ->paginate($entries)
                ->withQueryString();
    
            // Ambil semua kategori untuk dropdown
            $categories = \App\Models\Category::all();
            
    
            return view('page.toping.index', [
                'topings' => $topings,
                'categories' => $categories, // Tambahkan ini
                'search' => $search,
                'entries' => $entries
            ]);
        

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
        try {
            $data = [
                'name' => $request->input('name'),
                'category_id' => $request->input('category_id'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                // 'image' => handle image upload logic here
            ];
        
            // Validasi input
            $request->validate([
                'name' => 'required|unique:topings|max:255',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
        
            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('toping_images', 'public');
                $data['image'] = $imagePath;
            }
        
            Toping::create($data);
        
            return redirect()
                ->route('toping.index')
                ->with('message_insert', 'Data topping berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->route('error.index')
                ->with('error_message', 'Terjadi kesalahan saat menambahkan data topping: ' . $e->getMessage());
        }
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
    public function update(Request $request, string $id)
    {
        try {
            $data = [
                'name' => $request->input('name'),
                'category_id' => $request->input('category_id'),
                'price' => $request->input('price'),
                'stock' => $request->input('stock'),
                // Image akan dihandle terpisah
            ];
    
            // Validasi input
            $request->validate([
                'name' => 'required|max:255|unique:topings,name,' . $id,
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
    
            // Handle image upload
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                $toping = Toping::findOrFail($id);
                if ($toping->image) {
                    Storage::disk('public')->delete($toping->image);
                }
                
                // Upload gambar baru
                $imagePath = $request->file('image')->store('toping_images', 'public');
                $data['image'] = $imagePath;
            }
    
            $toping = Toping::findOrFail($id);
            $toping->update($data);
    
            return redirect()
                ->route('toping.index')
                ->with('message_update', 'Data topping berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->route('error.index')
                ->with('error_message', 'Terjadi kesalahan saat memperbarui data topping: ' . $e->getMessage());
        }
    }
    
    public function destroy(string $id)
    {
        try {
            $toping = Toping::findOrFail($id);
            
            // Hapus gambar jika ada
            if ($toping->image) {
                Storage::disk('public')->delete($toping->image);
            }
            
            $toping->delete();
    
            return back()->with('message_delete', 'Data topping berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error_message', 'Terjadi kesalahan saat menghapus data topping: ' . $e->getMessage());
        }
    }
    
    public function checkName($name, Request $request)
    {
        $id = $request->query('id');
        $exists = Toping::where('name', $name)
            ->when($id, function ($query) use ($id) {
                $query->where('id', '!=', $id);
            })
            ->exists();
    
        return response()->json(['exists' => $exists]);
    }

    
}
