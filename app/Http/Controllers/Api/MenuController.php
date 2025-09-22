<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuTable;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = MenuTable::where('status', 'available')
            ->get()
            ->map(function ($menu) {
                $menu->image_url = $menu->image
                    ? route('menu.image', basename($menu->image))
                    : null;
                return $menu;
            });

        return response()->json($menus);
    }
}
