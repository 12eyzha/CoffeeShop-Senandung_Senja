<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    /**
     * 🔹 API: Ambil semua menu yang tersedia (untuk transaksi, dengan filter & pagination)
     */
    public function getMenusApi(Request $request)
    {
        try {
            $query = Menu::with('category')->where('is_available', true);

            // 🔍 Filter kategori
            if ($request->has('category') && $request->category !== 'all') {
                $query->where('category_id', $request->category);
            }

            // 🔎 Pencarian nama menu
            if ($request->has('search') && $request->search !== '') {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // 📄 Pagination (default 5 per halaman)
            $menus = $query->orderBy('name')->paginate(5);

            return response()->json($menus);
        } catch (\Exception $e) {
            Log::error('Database Error in getMenusApi: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * 🔹 Tampilkan daftar menu (Data Master)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $menus = Menu::with('category')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('menus.index', compact('menus', 'search'));
    }

    /**
     * 🔹 Form tambah menu
     */
    public function create()
    {
        $categories = Category::all();
        return view('menus.create', compact('categories'));
    }

    /**
     * 🔹 Simpan menu baru
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_available' => 'nullable|boolean',
        ]);

        $data['is_available'] = $request->has('is_available');

        Menu::create($data);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    /**
     * 🔹 Form edit menu
     */
    public function edit(Menu $menu)
    {
        $categories = Category::all();
        return view('menus.edit', compact('menu', 'categories'));
    }

    /**
     * 🔹 Update data menu
     */
    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_available' => 'nullable|boolean',
        ]);

        $data['is_available'] = $request->has('is_available');

        $menu->update($data);

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    /**
     * 🔹 Hapus menu
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * 🔹 API: Ambil semua kategori (untuk filter transaksi)
     */
    public function getCategoriesApi()
    {
        try {
            $categories = Category::orderBy('name')->get();
            return response()->json($categories);
        } catch (\Exception $e) {
            Log::error('Database Error in getCategoriesApi: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil kategori.'], 500);
        }
    }
}
