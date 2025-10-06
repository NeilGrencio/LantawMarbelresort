<?php

namespace App\Http\Controllers;

use App\Models\SessionLogTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\StaffTable;
use Jenssegers\Agent\Agent;
use Illuminate\Validation\ValidationException;
use App\Mail\smtpSender;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Termwind\Components\Raw;

class LoginController extends Controller
{

    // ------------------- SEND OTP -------------------
    public function sendOTP(Request $request)
    {
        $request->validate(['username' => 'required|string']);

        // Get user and staff
        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Username not found.']);
        }

        $staff = StaffTable::where('userID', $user->userID)->first();
        if (!$staff || !$staff->email) {
            return response()->json(['success' => false, 'message' => 'Email not found.']);
        }

        // Disallow guests
        if ($staff->role === 'guest') {
            return response()->json(['success' => false, 'message' => 'Guests cannot request OTP.']);
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store OTP in session for 5 minutes
        session([
            'otp_'.$staff->staffID => $otp,
            'otp_expiration_'.$staff->staffID => now()->addMinutes(5)
        ]);

        try {
            Mail::to($staff->email)->send(new smtpSender($staff->username, $otp));

            return response()->json(['success' => true, 'message' => 'OTP sent to your email.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send OTP: '.$e->getMessage()]);
        }
    }

    // ------------------- VERIFY OTP -------------------
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'otp' => 'required|numeric'
        ]);

        $user = User::where('username', $request->username)->first();
        if (!$user) return response()->json(['success' => false, 'message' => 'Username not found.']);

        $staff = StaffTable::where('userID', $user->userID)->first();
        if (!$staff) return response()->json(['success' => false, 'message' => 'Staff not found.']);

        $cachedOtp = session('otp_'.$staff->staffID);
        $expiration = session('otp_expiration_'.$staff->staffID);

        if (!$cachedOtp || !$expiration || now()->greaterThan($expiration)) {
            return response()->json(['success' => false, 'message' => 'OTP expired.']);
        }

        if ($cachedOtp != $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP.']);
        }

        // OTP verified → keep it in session for reset, optionally
        return response()->json(['success' => true, 'message' => 'OTP verified.']);
    }

    // ------------------- RESET PASSWORD -------------------
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'otp' => 'required|numeric',
            'password' => 'required|string|min:8|confirmed', // password_confirmation required
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $user = User::where('username', $request->username)->first();
        if (!$user) return response()->json(['success' => false, 'message' => 'Username not found.']);

        $staff = StaffTable::where('userID', $user->userID)->first();
        if (!$staff) return response()->json(['success' => false, 'message' => 'Staff not found.']);

        if ($staff->role === 'guest') {
            return response()->json(['success' => false, 'message' => 'Guests cannot reset passwords.']);
        }

        // Verify OTP from session
        $cachedOtp = session('otp_'.$staff->staffID);
        $expiration = session('otp_expiration_'.$staff->staffID);

        if (!$cachedOtp || !$expiration || now()->greaterThan($expiration)) {
            return response()->json(['success' => false, 'message' => 'OTP expired.']);
        }

        if ($cachedOtp != $request->otp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP.']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Remove OTP from session
        session()->forget(['otp_'.$staff->staffID, 'otp_expiration_'.$staff->staffID]);

        $userlogs = new SessionLogTable();
        $userlogs->userID = $user->userID;
        $userlogs->activity = 'Password Reset';
        $userlogs->date = now();
        $userlogs->save();

        return response()->json(['success' => true, 'message' => 'Password has been reset successfully.']);
    }

    public function showLogin(Request $request)
    {
        if ($request->session()->get('logged_in')) {
            $role = $request->session()->get('role');

            switch ($role) {
                case 'Manager':
                    return redirect('manager/dashboard');
                case 'Receptionist':
                    return redirect('receptionist/dashboard');
                default:
                    Auth::logout();
                    $request->session()->invalidate();
                    return redirect()->route('login')->withErrors([
                        'username' => 'Unauthorized role detected, please login again.'
                    ]);
            }
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return $this->showLogin($request);
        }

        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'username' => 'required|min:5|max:20',
                'password' => 'required',
            ]);

            $user = User::where('username', $credentials['username'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return back()->withErrors([
                    'username' => 'Invalid username or password.',
                ])->onlyInput('username');
            }

            if ($user->status !== 'Active') {
                return back()->withErrors([
                    'username' => 'Account is no longer available.',
                ])->onlyInput('username');
            }

            $staff = StaffTable::where('userID', $user->userID)->first();

            if (!$staff) {
                return back()->withErrors([
                    'username' => 'Staff information not found.',
                ])->onlyInput('username');
            }

            if ($staff->role === 'Guest') {
                return back()->withErrors([
                    'username' => 'Log In is only authorized for staff members.',
                ])->onlyInput('username');
            }

            // Set session
            $request->session()->put('logged_in', true);
            $request->session()->put('user_id', $user->userID);
            $request->session()->put('username', $user->username);
            $request->session()->put('role', $staff->role);
            $request->session()->put('avatar', $staff->avatar);
            $request->session()->regenerate();

            // Log session
            $userlogs = new SessionLogTable();
            $userlogs->userID = $user->userID;
            $userlogs->activity = 'User Logged In';
            $userlogs->date = now();
            $userlogs->save();

            Auth::login($user);

            // Redirect by role
            if ($staff->role === 'Manager') {
                return redirect('manager/dashboard')->with('success', 'Welcome, ' . $user->username);
            }
            if ($staff->role === 'Receptionist') {
                return redirect('receptionist/dashboard')->with('success', 'Welcome, ' . $user->username);
            }
            if ($staff->role === 'Kitchen Staff') {
                return redirect('kitchen/dashbaord')->with('success', 'Welcome, ' . $user->username);
            }

            return back()->withErrors([
                'username' => 'Unauthorized role: ' . $staff->role,
            ])->onlyInput('username');
        }
    }

    public function logout(Request $request)
    {
        $userID = session()->get('user_id');

        // Log session
        SessionLogTable::create([
            'userID' => $userID,
            'activity' => 'User Loged Out',
            'date' => now(),
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flush();

        return redirect('auth/login')->with('success', 'Logged out successfully.');
    }


}