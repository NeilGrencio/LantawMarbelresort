<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends BaseAuthController
{
    /**
     * Manager Dashboard
     */
    public function managerDashboard()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has Manager role
        $roleCheck = $this->checkRole('Manager');
        if ($roleCheck) {
            return $roleCheck;
        }

        return view('manager.dashboard');
    }

    /**
     * Receptionist Dashboard
     */
    public function receptionistDashboard()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has Receptionist role
        $roleCheck = $this->checkRole('Receptionist');
        if ($roleCheck) {
            return $roleCheck;
        }

        // Get booking dashboard data and return view
        return app(BookingController::class)->bookingDashboard();
    }
}