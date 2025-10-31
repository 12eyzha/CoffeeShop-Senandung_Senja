<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;



class MenuController extends Controller


{
    public function getMenusApi()
    {
        try {
            // Mengambil semua menu yang is_available = true, beserta data category-nya
            $menus = Menu::with('category')->where('is_available', true)->get();
            return response()->json($menus);
        } catch (\Exception $e) {
            // Catat error ke file laravel.log
            \Log::error('Database Error in getMenusApi: ' . $e->getMessage());
            // Berikan response 500 ke frontend
            return response()->json(['error' => 'Gagal mengambil data: ' . $e->getMessage()], 500);
        }
    }
    // ... (Fungsi getCategoriesApi)


}