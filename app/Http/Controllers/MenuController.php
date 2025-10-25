<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function getMenusApi()
    {
        $menus = Menu::with('category')->where('is_available', true)->get();
        return response()->json($menus);
    }

    public function getCategoriesApi()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
}