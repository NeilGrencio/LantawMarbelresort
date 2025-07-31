<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserTable;
use Illuminate\Support\Facades\DB;


class SearchUserController extends Controller
{
    public function search(Request $request){
    $search = $request->input('search');

    $users = DB::table('users')
        ->leftJoin('guest', 'users.userID', '=', 'guest.userID')
        ->leftJoin('staff', 'users.userID', '=', 'staff.userID')
        ->select(
            'users.*', 
            'guest.firstname as g_firstname',
            'guest.lastname as g_lastname',
            'guest.role as g_role',
            'guest.avatar as g_avatar', 
            'staff.firstname as s_firstname',
            'staff.lastname as s_lastname',
            'staff.role as s_role',
            'staff.avatar as s_avatar'
        )
        ->when($search, function ($query, $search){
            $query->where('users.username', 'like', "%{$search}%")
                  ->orWhere('users.userID', 'like', "%{$search}%")
                  ->orWhere('staff.firstname', 'like', "%{$search}%")
                  ->orWhere('staff.lastname', 'like', "%{$search}%")
                  ->orWhere('guest.firstname', 'like', "%{$search}%")
                  ->orWhere('guest.lastname', 'like', "%{$search}%");
        })
        ->paginate(10);

    return view('manager.user_list', compact('users'));
}
}
