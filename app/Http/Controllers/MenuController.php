<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function getMenus()
    {
        $menus = Menu::whereNull('parent_id')
            ->with('children') // Eager load children
            ->orderBy('order')
            ->get();

        return response()->json($menus,200);
    }
}
