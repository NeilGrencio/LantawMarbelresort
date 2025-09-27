<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Validation\Rule;            
use Illuminate\Support\Facades\Storage;  

use App\Models\GuestTable;
use App\Models\UserTable;
use App\Models\StaffTable;
use App\Models\User;

class ManageGuestController extends Controller
{
    // List all guests
    public function guestList()
    {
        $guest = GuestTable::orderBy('guestID', 'desc')->paginate(10);
        return view('manager.guest_list', compact('guest'));
    }

    // Show add guest form
    public function addGuest()
    {
        return view('manager.add_guest');
    }

    // Submit guest registration
    public function submitGuest(Request $request)
    {
        $commonRules = [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'contactnum' => 'required|digits:10',
            'email' => 'required|email',
            'gender' => 'required',
            'birthday' => 'required|date',
            'role' => 'required|in:Guest,Day Tour Guest',
            'validID' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        $roleRules = $request->input('role') === 'Guest'
            ? [
                'username' => 'required|min:5|max:20|unique:users,username',
                'password' => [
                    'required', 'min:8', 'max:20',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/'
                ],
                'cpassword' => 'required|same:password',
                'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
              ]
            : [
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
              ];

        $validatedData = $request->validate(array_merge($commonRules, $roleRules));

        // OCR verification for validID
/*        if ($request->hasFile('validID')) {

            $requiredHeaders = [
                'REPUBLIKA NG PILIPINAS',
                'Republic of the Philippines',
                'PAMBANSANG PAGKAKAKILANLAN',
                'Philippine Identification',
                'Philippine Identification Card'
            ];

            $headerFound = false;
            foreach ($requiredHeaders as $header) {
                if (stripos($ocrText, $header) !== false) {
                    $headerFound = true;
                    break;
                }
            }

            $pcnFound = preg_match('/\d{4}-\d{4}-\d{4}-\d{4}/', $ocrText);

            if (!$headerFound || !$pcnFound) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['validID' => 'The uploaded ID is not a valid Philippine National ID.'])
                    ->with('ocrtext', $ocrText);
            }
        }
*/

        // Store files
        $validIDPath = $request->file('validID')->store('valid_ids', 'public');
        $avatarPath = $request->file('avatar') ? $request->file('avatar')->store('avatars', 'public') : null;

        DB::beginTransaction();
        try {
            $userID = null;

            // Create user account only for Guest role
            if ($request->input('role') === 'Guest') {
                $user = new UserTable();
                $user->username = $validatedData['username'];
                $user->password = Hash::make($validatedData['password']);
                $user->status = 'Active';
                $user->save();

                $userID = $user->userID;
            }

            // Create Guest record
            $guest = new GuestTable();
            $guest->userID = $userID;
            $guest->firstname = $validatedData['firstname'];
            $guest->lastname = $validatedData['lastname'];
            $guest->mobilenum = $validatedData['contactnum'];
            $guest->email = $validatedData['email'];
            $guest->gender = $validatedData['gender'];
            $guest->birthday = $validatedData['birthday'];
            $guest->avatar = $avatarPath;
            $guest->validID = $validIDPath;
            $guest->role = $validatedData['role'];
            $guest->save();

            DB::commit();

            return redirect()->route('manager.guest_list')->with('success', 'Guest registered successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.' . $e->getMessage());
        }
    }

    public function createUser(Request $request)
{
    $validated = $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'contactnum' => 'required|digits:10',
        'email' => 'required|email|max:255|unique:staff,email',
        'gender' => 'required',
        'username' => 'required|min:5|max:20|unique:users,username',
        'password' => [
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
        // Create the user without logging them in
        $user = new UserTable();
        $user->username = $validated['username'];
        $user->password = Hash::make($validated['password']);
        $user->status = 'Active';
        $user->save();

        $userID = $user->userID;

        // Save the user without triggering events
        User::withoutEvents(function () use ($user) {
            $user->save();
        });

        $staff = new StaffTable();
        $staff->firstname = $validated['firstname'];
        $staff->lastname = $validated['lastname'];
        $staff->mobilenum = $validated['contactnum'];
        $staff->email = $validated['email'];
        $staff->gender = $validated['gender'];
        $staff->role = $request->input('role');
        $staff->avatar = $avatarPath;
        $staff->userID = $userID->userID;
        $staff->save();

        DB::commit();

        return redirect()->route('manager.add_user.form')->with('success', 'User created successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('manager.add_user.form')->withInput()->with('error', 'Failed to create user: ' . $e->getMessage());
    }
}

    // View guest details (Manager)
    public function viewGuest($guestID)
    {
        $guest = GuestTable::findOrFail($guestID);
        $user = $guest->Users ?? null;
        return view('manager.view_guest', compact('guest', 'user'));
    }
    
    public function update(Request $request, $guestID)
    {
        $guest = GuestTable::findOrFail($guestID);
        $user = $guest->userID ? UserTable::find($guest->userID) : null;

        // Base rules for guest fields
        $rules = [
            'firstname'  => 'required|string',
            'lastname'   => 'required|string',
            'contactnum' => 'required|digits:10',
            'email'      => 'required|email',
            'gender'     => 'required',
            'birthday'   => 'required|date',
            'role'       => 'required|in:Guest,Day Tour Guest',
            // validID optional on edit
            'validID'    => 'sometimes|nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        // Role-specific rules
        if ($request->input('role') === 'Guest') {
            // username required for Guest. If we're editing an existing user, allow same username (ignore uniqueness).
            if ($user) {
                $rules['username'] = [
                    'required', 'min:5', 'max:20',
                    Rule::unique('users', 'username')->ignore($user->userID, 'userID')
                ];
                // password optional when user already exists
                if ($request->filled('password')) {
                    $rules['password'] = [
                        'nullable', 'min:8', 'max:20',
                        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/'
                    ];
                    $rules['cpassword'] = 'nullable|same:password';
                }
            } else {
                // Creating a new user as part of update: require username + password
                $rules['username'] = ['required', 'min:5', 'max:20', 'unique:users,username'];
                $rules['password'] = [
                    'required', 'min:8', 'max:20',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/'
                ];
                $rules['cpassword'] = 'required|same:password';
            }

            // avatar optional on update
            $rules['avatar'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048';
        } else {
            // Day Tour Guest: avatar optional
            $rules['avatar'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // Handle file uploads (if provided). Delete old files when replaced.
            if ($request->hasFile('validID')) {
                $validIDPath = $request->file('validID')->store('valid_ids', 'public');
                if ($guest->validID) {
                    Storage::disk('public')->delete($guest->validID);
                }
                $guest->validID = $validIDPath;
            }

            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                if ($guest->avatar) {
                    Storage::disk('public')->delete($guest->avatar);
                }
                $guest->avatar = $avatarPath;
            }

            // If role is Guest: create or update associated user
            if ($validated['role'] === 'Guest') {
                if ($user) {
                    // update existing user
                    $user->username = $validated['username'];
                    if (!empty($validated['password'])) {
                        $user->password = Hash::make($validated['password']);
                    }
                    $user->status = 'Active';
                    $user->save();

                    $guest->userID = $user->userID;
                } else {
                    // create a new user record
                    $newUser = new UserTable();
                    $newUser->username = $validated['username'];
                    // password is required in this path per validation above
                    $newUser->password = Hash::make($validated['password']);
                    $newUser->status = 'Active';
                    $newUser->save();

                    $guest->userID = $newUser->userID;
                }
            } else {
                // Role changed to Day Tour Guest: if a user exists, detach association and mark user inactive
                if ($user) {
                    $user->status = 'Inactive';
                    $user->save();
                    $guest->userID = null;
                }
            }

            // Update guest fields
            $guest->firstname  = $validated['firstname'];
            $guest->lastname   = $validated['lastname'];
            $guest->mobilenum  = $validated['contactnum'];
            $guest->email      = $validated['email'];
            $guest->gender     = $validated['gender'];
            $guest->birthday   = $validated['birthday'];
            $guest->role       = $validated['role'];

            $guest->save();

            DB::commit();

            // redirect to your manage user list (adjust path/name to your routes)
            return redirect('manager/manage_user')->with('success', 'Guest updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // return back with the exception message for debug — remove message in production
            return redirect()->back()->withInput()->with('error', 'Update failed. ' . $e->getMessage());
        }
    }

    // Receptionist-specific guest list
    public function guestListReceptionist()
    {
        $guest = GuestTable::paginate(10);
        return view('receptionist.guest_list_receptionist', compact('guest'));
    }

    // Receptionist view guest details
    public function viewGuestReceptionist($guestID)
    {
        $guest = GuestTable::findOrFail($guestID);
        $user = $guest->Users ?? null;
        return view('receptionist.view_guest', compact('guest', 'user'));
    }
}
