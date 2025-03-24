<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Laravel\Pail\ValueObjects\Origin\Console;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $page = request()->input('page', 1);
            $entries = request()->input('entries', 5);
            $search = request()->input('search');
            $category = Category::when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })->paginate($entries);

            // $category = Category::paginate(5);
            return view('page.category.index')->with([
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return view('error.index');
            echo "<script>console.error('PHP Error: " .
                addslashes($e->getMessage()) . "');</script>";
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $data = Category::all();
        // return response()->json([
        //     'data' => $data,
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('category.index')
                ->withErrors($validator)
                ->withInput()
                ->with('error_message', $validator->errors()->first());
        }

        try {
            Category::create($request->all());
            return redirect()
                ->route('category.index')
                ->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()
                ->route('category.index')
                ->with('error_message', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:categories,name,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect()->route('category.index')
                ->withErrors($validator)
                ->withInput()
                ->with('error_message', $validator->errors()->first());
        }

        try {
            $category = Category::findOrFail($id);
            $category->update($request->all());
            return redirect()
                ->route('category.index')
                ->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->route('category.index')
                ->with('error_message', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return redirect()
                ->route('category.index')
                ->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('category.index')
                ->with('error_message', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Check if a category name exists.
     */
    public function checkName($name)
    {
        $exists = Category::where('name', $name)->exists();
        return response()->json(['exists' => $exists]);
    }
}
