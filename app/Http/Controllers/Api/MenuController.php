<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuTable;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = MenuTable::where('status','available')->get()->map(function ($menu) {
            $menu->image = url('uploads/' . $menu->image);
            return $menu;
        });
        return response()->json($menus);
    }
}
