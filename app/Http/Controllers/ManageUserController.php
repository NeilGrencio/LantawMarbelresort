<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\UserTable;
use App\Models\User;
use App\Models\GuestTable;
use App\Models\StaffTable;
use App\Models\SessionLogTable;

class ManageUserController extends Controller
{
    // Show add user form
    public function showForm()
    {
        return view('manager.add_user');
    }

    // List all users with pagination
    public function userList(Request $request)
    {
        $users = UserTable::query()
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
            ->orderBy('userID', 'desc')
            ->paginate(10);

            // Get the userID from the session
            $userID = $request->session()->get('user_id');

            // Log the session activity
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Viewed User List',
                    'date'     => now(),
                ]);
            }

        return view('manager.user_list', compact('users'));
    }

    // Add new user
    public function addUser(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'contactnum' => 'required|digits:10',
            'email' => 'required|email|max:255|unique:staff,email',
            'gender' => 'required',
            'username' => 'required|min:5|max:20|unique:users,username',
            'password' => [
                'required', 'min:8', 'max:20',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/'
            ],
            'cpassword' => 'required|same:password',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ], [
            'password.regex' => 'Password must be 8 characters, include at least one uppercase, one lowercase, one number, and one special character'
        ]);

        $avatarPath = $request->file('avatar')->store('avatars', 'public');

        DB::beginTransaction();
        try {
            $user = new UserTable();
            $user->username = $validatedData['username'];
            $user->password = Hash::make($validatedData['password']);
            $user->status = 'Active';
            $user->save();

            $staff = new StaffTable();
            $staff->firstname = $validatedData['firstname'];
            $staff->lastname = $validatedData['lastname'];
            $staff->mobilenum = $validatedData['contactnum'];
            $staff->email = $validatedData['email'];
            $staff->gender = $validatedData['gender'];
            $staff->role = $request->input('role');
            $staff->avatar = $avatarPath;
            $staff->userID = $user->userID;
            $staff->save();

            DB::commit();

            // Get the userID from the session
            $userID = $request->session()->get('user_id');

            // Log the session activity
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Created a User: ' . $user->username,
                    'date'     => now(),
                ]);
            }

            Log::info('Staff user added', ['userID' => $user->userID]);
            return redirect()->route('manager.manage_user')->with('success', 'Staff user added successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to add staff user', ['error' => $e->getMessage()]);
            return redirect('manager/add_user')->withInput()->with('error', 'Failed to add staff user: ' . $e->getMessage());
        }
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'firstname'   => 'required|string|max:100',
            'lastname'    => 'required|string|max:100',
            'contactnum'  => 'required|digits:10',
            'email'       => 'required|email|unique:guests,email',
            'gender'      => 'required|string',
            'birthday'    => 'nullable|date',
            'validID'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'username'    => 'required|string|unique:users,username',
            'password'    => 'required|string|min:8|confirmed',
            'cpassword'   => 'required|matches:password',
            'avatar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'role'        => 'required|string',
        ]);

        // Save User first
        $user = new User();
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->save();

        // Save Guest details
        $guest = new GuestTable();
        $guest->firstname  = $request->firstname;
        $guest->lastname   = $request->lastname;
        $guest->mobilenum  = $request->contactnum;
        $guest->email      = $request->email;
        $guest->gender     = $request->gender;
        $guest->birthday   = $request->birthday;
        $guest->role       = $request->role;
        $guest->user_id    = $user->id;

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $guest->avatar = $path;
        }

        if ($request->hasFile('validID')) {
            $path = $request->file('validID')->store('valid_ids', 'public');
            $guest->validID = $path;
        }

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Created a User: ' . $guest->firstname . ' ' . $user->username,
                'date'     => now(),
            ]);
        }

        $guest->save();

        return redirect()->route('manager.manage_user')->with('success', 'Guest created successfully!');

    }

    // Edit user
    public function editUser(Request $request, $userID)
    {
        $guest = GuestTable::where('userID', $userID)->first();
        $staff = StaffTable::where('userID', $userID)->first();
        $user = UserTable::findOrFail($userID);

        if ($request->isMethod('get')) {
            return view('manager.edit_user', compact('guest', 'staff', 'user'));
        }

        $role = $request->input('role') ?? ($guest ? 'Guest' : 'Staff');

        $commonRules = [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'mobilenum' => 'required|digits:10',
            'gender' => 'required',
            'username' => 'required|min:5|max:20|unique:users,username,' . $userID . ',userID',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ];

        if (strtolower($role) === 'guest') {
            $validatedData = $request->validate(array_merge($commonRules, [
                'birthday' => 'required|date',
                'email' => 'required|email|max:255|unique:guest,email,' . $userID . ',userID'
            ]));

            DB::beginTransaction();
            try {
                $user->username = $validatedData['username'];
                if ($request->filled('password')) {
                    $request->validate([
                        'password' => [
                            'min:8', 'max:20',
                            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/'
                        ],
                        'cpassword' => 'same:password'
                    ]);
                    $user->password = Hash::make($request->password);
                }
                $user->save();

                if (!$guest) {
                    $guest = new GuestTable();
                    $guest->userID = $user->userID;
                }

                $guest->fill($validatedData);
                if ($request->hasFile('avatar')) {
                    $guest->avatar = $request->file('avatar')->store('avatars', 'public');
                }
                $guest->save();

                // Get the userID from the session
                $userID = $request->session()->get('user_id');

                // Log the session activity
                if ($userID) {
                    SessionLogTable::create([
                        'userID'   => $userID,
                        'activity' => 'User Edited a User: ' . $user->username,
                        'date'     => now(),
                    ]);
                }

                DB::commit();
                Log::info('Guest user updated', ['userID' => $userID]);
                return redirect()->route('manager.manage_user')->with('success', 'Guest user updated successfully');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to update guest user', ['userID' => $userID, 'error' => $e->getMessage()]);
                return redirect()->route('manager.edit_user', ['userID' => $userID])->withInput()->with('error', 'Failed to update guest user');
            }

        } else {
            $validatedData = $request->validate(array_merge($commonRules, [
                'email' => 'required|email|max:255|unique:staff,email,' . $userID . ',userID'
            ]));

            DB::beginTransaction();
            try {
                $user->username = $validatedData['username'];
                if ($request->filled('password')) {
                    $request->validate([
                        'password' => [
                            'min:8', 'max:20',
                            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/'
                        ],
                        'cpassword' => 'same:password'
                    ]);
                    $user->password = Hash::make($request->password);
                }
                $user->save();

                if (!$staff) {
                    $staff = new StaffTable();
                    $staff->userID = $user->userID;
                }

                $staff->fill($validatedData);
                if ($request->hasFile('avatar')) {
                    $staff->avatar = $request->file('avatar')->store('avatars', 'public');
                }
                $staff->save();

                // Get the userID from the session
                $userID = $request->session()->get('user_id');

                // Log the session activity
                if ($userID) {
                    SessionLogTable::create([
                        'userID'   => $userID,
                        'activity' => 'User Edited a User List: ' . $user->username,
                        'date'     => now(),
                    ]);
                }

                DB::commit();
                Log::info('Staff user updated', ['userID' => $userID]);
                return redirect()->route('manager.manage_user')->with('success', 'Staff user updated successfully');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to update staff user', ['userID' => $userID, 'error' => $e->getMessage()]);
                return redirect()->route('manager.edit_user', ['userID' => $userID])->withInput()->with('error', 'Failed to update staff user');
            }
        }
    }

    // Deactivate user
    public function deactivateUser($userID, Request $request)
    {
        $user = UserTable::find($userID);
        if ($user) {
            $user->status = 'Deactivated';
            $user->save();

            // Get the userID from the session
            $userID = $request->session()->get('user_id');

            // Log the session activity
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Deactivated a User: ' . $user->username,
                    'date'     => now(),
                ]);
            }

            Log::info('User deactivated', ['userID' => $userID]);
            return redirect()->back()->with('success', 'User deactivated successfully');
        }
        return redirect()->back()->with('error', 'User not found');
    }

    // Activate user
    public function activateUser($userID, Request $request)
    {
        $user = UserTable::find($userID);
        if ($user) {
            $user->status = 'Active';
            $user->save();
            
            // Get the userID from the session
            $userID = $request->session()->get('user_id');

            // Log the session activity
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Activated a User: ' . $user->username,
                    'date'     => now(),
                ]);
            }

            Log::info('User activated', ['userID' => $userID]);
            return redirect()->back()->with('success', 'User activated successfully');
        }
        return redirect()->back()->with('error', 'User not found');
    }
}
