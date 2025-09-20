<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\MenuTable;
use App\Models\OrderTable;
use Illuminate\Http\Request;
use PDO;

class OrderController extends Controller
{
    public function viewMenu() {
        $menu = MenuTable::where("status", 'Available')->get();
        $uniqueMenuTypes = $menu->pluck('itemtype')->unique();
        return view('receptionist.order', compact('menu', 'uniqueMenuTypes'));
    }

    public function submitOrder(Request $request){
        $validated = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'bookingType' => 'in:room,cottage,amenity|required',
            'roomNumber' => 'sometimes|int',
            'cottge'
        ]);
    }
}
