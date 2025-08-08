<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StaffTable;

class BaseAuthController extends Controller
{
    /**
     * Check if the current user has the required role
     */
    protected function checkRole($requiredRole)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user is still active
        if ($user->status !== 'Active') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'username' => 'Account is no longer available.',
            ]);
        }

        $staff = StaffTable::where('userID', $user->userID)->first();
        
        if (!$staff) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'username' => 'Staff information not found.',
            ]);
        }

        // If user doesn't have the required role, redirect to appropriate dashboard
        if ($staff->role !== $requiredRole) {
            switch ($staff->role) {
                case 'Manager':
                    return redirect()->route('manager.dashboard');
                case 'Receptionist':
                    return redirect()->route('receptionist.dashboard');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors([
                        'username' => 'Unauthorized access.',
                    ]);
            }
        }

        return null; // No redirect needed, user has correct role
    }

    /**
     * Get current user's role
     */
    protected function getUserRole()
    {
        if (!Auth::check()) {
            return null;
        }

        $staff = StaffTable::where('userID', Auth::user()->userID)->first();
        return $staff ? $staff->role : null;
    }
}