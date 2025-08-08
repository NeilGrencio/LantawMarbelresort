<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        // This will run before every method in controllers that extend this class
        $this->middleware(function ($request, $next) {
            if (!session()->has('user_id') || !session('user_id')) {
                return redirect()->route('userlogin')->with('error', 'Please login to access this page.');
            }
            return $next($request);
        });
    }
}