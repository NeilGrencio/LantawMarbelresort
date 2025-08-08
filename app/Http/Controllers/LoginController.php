<?php

namespace App\Http\Controllers;

use App\Models\SessionLogTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\StaffTable;
use Jenssegers\Agent\Agent;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('auth.login'); // Shows the login form
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
            

            // User Agent Info
            $agent = new \Jenssegers\Agent\Agent();
            $browser = $agent->browser();
            $browserVersion = $agent->version($browser);
            $platform = $agent->platform();
            $platformVersion = $agent->version($platform);

            // Log session
            $session = new SessionLogTable();
            $session->useragent = "$browser $browserVersion on $platform $platformVersion";
            $session->loginstatus = 'Logged-in';
            $session->sessioncreated = now();
            $session->sessionexpired = now()->addDays(30);
            $session->userID = $user->userID;
            $session->save();

            // Log in the user
            Auth::login($user);
            $request->session()->regenerate();

            // Redirect by role
            if ($staff->role === 'Manager') {
                return redirect('manager/dashboard')->with('success', 'Welcome, ' . $user->username);
            }

            if ($staff->role === 'Receptionist') {
                return redirect('receptionist/dashboard')->with('success', 'Welcome, ' . $user->username);
            }

            return back()->withErrors([
                'username' => 'Unauthorized role: ' . $staff->role,
            ])->onlyInput('username');
        }
    }

    public function logout(Request $request)
    {
        $userID = session()->get('user_id');

        $latestSession = SessionLogTable::where('userID', $userID)
                            ->latest('sessioncreated')
                            ->first();

        if ($latestSession && now()->greaterThanOrEqualTo($latestSession->sessionexpired)) {
            $request->session()->flush();
            return redirect('auth/login')->with('error', 'Session expired. You have been logged out.');
        }

        $agent = new \Jenssegers\Agent\Agent();
        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);
        $platform = $agent->platform();
        $platformVersion = $agent->version($platform);
        
        SessionLogTable::create([
            'userID' => $userID,
            'sessioncreated' => now(),
            'sessionexpired' => now(),
            'loginstatus' => 'Logged-out',
            'useragent' => "$browser $browserVersion on $platform $platformVersion",
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flush();

        return redirect('auth/login')->with('success', 'Logged out successfully.');
    }


}
