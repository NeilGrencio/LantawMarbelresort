<?php

namespace App\Http\Controllers;

use App\Models\SessionLogTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\StaffTable;
use App\Models\GuestTable;
use Jenssegers\Agent\Agent;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('userlogin');
        }

        if ($request->isMethod('post')) {
            $credentials = $request->validate([
                'username' => 'required|min:5|max:20',
                'password' => 'required',
            ]);

            $user = User::where('username', $credentials['username'])->first();
            $guest = GuestTable::where('userID', $user->userID)->first();
            $staff = StaffTable::where('userID', $user->userID)->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                logger('Login failed: invalid credentials');
                return back()->withErrors([
                    'username' => 'Invalid username or password.',
                ])->onlyInput('username');
            }

            // Manual session management
            if ($staff->role === 'Guest') {
                return back()->withErrors([
                    'username' => 'Log In is only authorized for staff members.',
                ])->onlyInput('username');
            }
            else if ($staff->role === 'Manager'){
                // Store data in session
                $request->session()->put('logged_in', true);
                $request->session()->put('user_id', $user->userID);
                $request->session()->put('username', $user->username);
                $request->session()->put('role', $staff->role);     
                $request->session()->put('avatar', $staff->avatar);

                $agent = new Agent();

                $browser = $agent->browser();
                $browserVersion = $agent->version($browser);
                $platform = $agent->platform();
                $platformVersion = $agent->version($platform);

                $session = new SessionLogTable();
                $session->useragent = "$browser $browserVersion on $platform $platformVersion";
                $session->loginstatus = 'Logged-in';
                $session->sessioncreated = now();
                $session->sessionexpired = now()->addDays(30);
                $session->userID =  session()->get('user_id');
                $session->save();

                return redirect()->intended('manager/dashboard')->with('success', 'Welcome, ' . $user->username);
            }

            if ($user->status !== 'Active') {
                return back()->withErrors([
                    'username' => 'Account is no longer available.',
                ])->onlyInput('username');
            }

            if (!$staff) {
                return back()->withErrors([
                    'username' => 'Staff information not found.',
                ])->onlyInput('username');
            }

            // Store data in session
            $request->session()->put('logged_in', true);
            $request->session()->put('user_id', $user->userID);
            $request->session()->put('username', $user->username);
            $request->session()->put('role', $staff->role);     
            $request->session()->put('avatar', $staff->avatar);

            return redirect()->intended('manager/dashboard')->with('success', 'Welcome, ' . $user->username);
        }
    }

    public function logout(Request $request)
    {
        $userID = session()->get('user_id');

        $session = SessionLogTable::where('userID', $userID)
                    ->latest('sessioncreated')
                    ->first();

        if ($session && now()->greaterThanOrEqualTo($session->sessionexpired)) {
            $request->session()->flush();
            return redirect('/login')->with('error', 'Session expired. You have been logged out.');
        }

        if ($session) {
            $session->loginstatus = 'Logged-out';
            $session->save();
        }

        $request->session()->flush();
        return redirect('/login')->with('success', 'Logged out successfully.');
    }

}
