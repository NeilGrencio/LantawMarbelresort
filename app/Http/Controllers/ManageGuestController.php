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
use App\Services\OCRService;

use App\Models\GuestTable;
use App\Models\UserTable;
use App\Models\StaffTable;
use App\Models\User;
use App\Models\SessionLogTable;

class ManageGuestController extends Controller
{
    public function guestList(Request $request)
    {
        $guest = GuestTable::orderBy('guestID', 'desc')->paginate(10);

        $userID = $request->session()->get('user_id');

        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Viwed Guest List',
                'date'     => now(),
            ]);
        }

        return view('manager.guest_list', compact('guest'));
    }

    public function addGuest()
    {
        return view('manager.add_guest');
    }

    public function submitGuest(Request $request, OCRService $ocrService)
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

        $validIDPath = null;
        $avatarPath = null;

        if ($request->hasFile('validID')) {
            $file = $request->file('validID');

            $validIDPath = $file->storeAs(
                'valid_ids',
                uniqid() . '.' . $file->getClientOriginalExtension(),
                'public'
            );

            $absolutePath = storage_path('app/public/' . $validIDPath);

            $ocrResult = $ocrService->verify($absolutePath);

            if (!$ocrResult['isValid']) {
                return back()
                    ->withErrors(['validID' => 'The uploaded ID is not a valid Philippine National ID.'])
                    ->withInput()
                    ->with('ocrtext', $ocrResult['ocrText']);
            }
        }

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        DB::beginTransaction();
        try {
            $userID = null;

            if ($request->input('role') === 'Guest') {
                $user = new UserTable();
                $user->username = $validatedData['username'];
                $user->password = Hash::make($validatedData['password']);
                $user->status = 'Active';
                $user->save();

                $userID = $user->userID;
            }

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

            $userID = $request->session()->get('user_id');

            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Created a Guest',
                    'date'     => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('manager.guest_list')->with('success', 'Guest registered successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again. ' . $e->getMessage());
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
            $user = new UserTable();
            $user->username = $validated['username'];
            $user->password = Hash::make($validated['password']);
            $user->status = 'Active';
            $user->save();

            $userID = $user->userID;

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
            $staff->userID = $userID;
            $staff->save();

            DB::commit();

            return redirect()->route('manager.add_user.form')->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('manager.add_user.form')->withInput()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

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

        $rules = [
            'firstname'  => 'required|string',
            'lastname'   => 'required|string',
            'contactnum' => 'required|digits:10',
            'email'      => 'required|email',
            'gender'     => 'required',
            'birthday'   => 'required|date',
            'role'       => 'required|in:Guest,Day Tour Guest',
            'validID'    => 'sometimes|nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        if ($request->input('role') === 'Guest') {
            if ($user) {
                $rules['username'] = [
                    'required', 'min:5', 'max:20',
                    Rule::unique('users', 'username')->ignore($user->userID, 'userID')
                ];
                if ($request->filled('password')) {
                    $rules['password'] = [
                        'nullable', 'min:8', 'max:20',
                        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/'
                    ];
                    $rules['cpassword'] = 'nullable|same:password';
                }
            } else {
                $rules['username'] = ['required', 'min:5', 'max:20', 'unique:users,username'];
                $rules['password'] = [
                    'required', 'min:8', 'max:20',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/'
                ];
                $rules['cpassword'] = 'required|same:password';
            }

            $rules['avatar'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048';
        } else {
            $rules['avatar'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
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

            if ($validated['role'] === 'Guest') {
                if ($user) {
                    $user->username = $validated['username'];
                    if (!empty($validated['password'])) {
                        $user->password = Hash::make($validated['password']);
                    }
                    $user->status = 'Active';
                    $user->save();

                    $guest->userID = $user->userID;
                } else {
                    $newUser = new UserTable();
                    $newUser->username = $validated['username'];
                    $newUser->password = Hash::make($validated['password']);
                    $newUser->status = 'Active';
                    $newUser->save();

                    $guest->userID = $newUser->userID;
                }
            } else {
                if ($user) {
                    $user->status = 'Inactive';
                    $user->save();
                    $guest->userID = null;
                }
            }

            $guest->firstname  = $validated['firstname'];
            $guest->lastname   = $validated['lastname'];
            $guest->mobilenum  = $validated['contactnum'];
            $guest->email      = $validated['email'];
            $guest->gender     = $validated['gender'];
            $guest->birthday   = $validated['birthday'];
            $guest->role       = $validated['role'];

            $guest->save();

            $userID = $request->session()->get('user_id');

            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Updated a Guest',
                    'date'     => now(),
                ]);
            }

            DB::commit();

            return redirect('manager/manage_user')->with('success', 'Guest updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Update failed. ' . $e->getMessage());
        }
    }

    public function guestListReceptionist(Request $request)
    {
        $today = now()->toDateString();

        $guest = GuestTable::leftJoin('booking', 'guest.guestID', '=', 'booking.guestID')
            ->leftJoin('qrcodes', 'guest.guestID', '=', 'qrcodes.guestID')
            ->select('guest.*')
            ->selectRaw('
                CASE
                    WHEN booking.guestID IS NOT NULL AND booking.bookingstart <= ? AND booking.bookingend >= ? THEN "Booking"
                    WHEN qrcodes.guestID IS NOT NULL AND DATE(qrcodes.accessdate) = ? THEN "Day Tour"
                    ELSE "Inactive"
                END AS guestType
            ', [$today, $today, $today])
            ->distinct()
            ->orderBy('guest.guestID', 'desc')
            ->paginate(10);

        $userID = $request->session()->get('user_id');

        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Viewed a User',
                'date'     => now(),
            ]);
        }

        return view('receptionist.guest_list_receptionist', compact('guest'));
    }

    public function viewGuestReceptionist($guestID)
    {
        $guest = GuestTable::findOrFail($guestID);
        $user = $guest->Users ?? null;
        return view('receptionist.view_guest', compact('guest', 'user'));
    }

    public function addGuestReceptionist(Request $request, OCRService $ocrService)
    {
        if($request->isMethod('get')) {
            return view('receptionist.add_guest');
        }
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

        $validIDPath = null;
        $avatarPath = null;

        if ($request->hasFile('validID')) {
            $file = $request->file('validID');

            $validIDPath = $file->storeAs(
                'valid_ids',
                uniqid() . '.' . $file->getClientOriginalExtension(),
                'public'
            );

            $absolutePath = storage_path('app/public/' . $validIDPath);

            $ocrResult = $ocrService->verify($absolutePath);

            if (!$ocrResult['isValid']) {
                return back()
                    ->withErrors(['validID' => 'The uploaded ID is not a valid Philippine National ID.'])
                    ->withInput()
                    ->with('ocrtext', $ocrResult['ocrText']);
            }
        }

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        DB::beginTransaction();
        try {
            $userID = null;

            if ($request->input('role') === 'Guest') {
                $user = new UserTable();
                $user->username = $validatedData['username'];
                $user->password = Hash::make($validatedData['password']);
                $user->status = 'Active';
                $user->save();

                $userID = $user->userID;
            }

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

            $userID = $request->session()->get('user_id');

            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Created a Guest',
                    'date'     => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('receptionist.guest_list_receptionist')->with('success', 'Guest registered successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again. ' . $e->getMessage());
        }
        
    }

    public function editGuestManager(Request $request, $guestID){
        $guest = GuestTable::findOrFail($guestID);
        $user = $guest->userID ? UserTable::find($guest->userID) : null;

        $rules = [
            'firstname'  => 'required|string',
            'lastname'   => 'required|string',
            'contactnum' => 'required|digits:10',
            'email'      => 'required|email',
            'gender'     => 'sometimes',
            'birthday'   => 'required|date',
            'role'       => 'required',
            'validID'    => 'sometimes|nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        if ($request->input('role') === 'Guest') {
            if ($user) {
                $rules['username'] = [
                    'required', 'min:5', 'max:20',
                    Rule::unique('users', 'username')->ignore($user->userID, 'userID')
                ];
                if ($request->filled('password')) {
                    $rules['password'] = [
                        'nullable', 'min:8', 'max:20',
                        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/'
                    ];
                    $rules['cpassword'] = 'nullable|same:password';
                }
            } else {
                $rules['username'] = ['required', 'min:5', 'max:20', 'unique:users,username'];
                $rules['password'] = [
                    'required', 'min:8', 'max:20',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/'
                ];
                $rules['cpassword'] = 'required|same:password';
            }

            $rules['avatar'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048';
        } else {
            $rules['avatar'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
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

            if ($validated['role'] === 'Guest') {
                if ($user) {
                    $user->username = $validated['username'];
                    if (!empty($validated['password'])) {
                        $user->password = Hash::make($validated['password']);
                    }
                    $user->status = 'Active';
                    $user->save();

                    $guest->userID = $user->userID;
                } else {
                    $newUser = new UserTable();
                    $newUser->username = $validated['username'];
                    $newUser->password = Hash::make($validated['password']);
                    $newUser->status = 'Active';
                    $newUser->save();

                    $guest->userID = $newUser->userID;
                }
            } else {
                if ($user) {
                    $user->status = 'Inactive';
                    $user->save();
                    $guest->userID = null;
                }
            }

            $guest->firstname  = $validated['firstname'];
            $guest->lastname   = $validated['lastname'];
            $guest->mobilenum  = $validated['contactnum'];
            $guest->email      = $validated['email'];
            $guest->gender     = $validated['gender'];
            $guest->birthday   = $validated['birthday'];
            $guest->role       = $validated['role'] ?? 'Daytour Guest';

            $guest->save();

            $userID = $request->session()->get('user_id');

            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Updated a Guest',
                    'date'     => now(),
                ]);
            }

            DB::commit();

            return redirect('manager/guest_list')->with('success', 'Guest updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Update failed. ' . $e->getMessage());
        }
    }

    public function editGuest(Request $request, $guestID){
        $guest = GuestTable::findOrFail($guestID);
        $user = $guest->userID ? UserTable::find($guest->userID) : null;

        $rules = [
            'firstname'  => 'required|string',
            'lastname'   => 'required|string',
            'contactnum' => 'required|digits:10',
            'email'      => 'required|email',
            'gender'     => 'sometimes',
            'birthday'   => 'required|date',
            'role'       => 'required',
            'validID'    => 'sometimes|nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        if ($request->input('role') === 'Guest') {
            if ($user) {
                $rules['username'] = [
                    'required', 'min:5', 'max:20',
                    Rule::unique('users', 'username')->ignore($user->userID, 'userID')
                ];
                if ($request->filled('password')) {
                    $rules['password'] = [
                        'nullable', 'min:8', 'max:20',
                        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/'
                    ];
                    $rules['cpassword'] = 'nullable|same:password';
                }
            } else {
                $rules['username'] = ['required', 'min:5', 'max:20', 'unique:users,username'];
                $rules['password'] = [
                    'required', 'min:8', 'max:20',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).{8,}$/'
                ];
                $rules['cpassword'] = 'required|same:password';
            }

            $rules['avatar'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048';
        } else {
            $rules['avatar'] = 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
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

            if ($validated['role'] === 'Guest') {
                if ($user) {
                    $user->username = $validated['username'];
                    if (!empty($validated['password'])) {
                        $user->password = Hash::make($validated['password']);
                    }
                    $user->status = 'Active';
                    $user->save();

                    $guest->userID = $user->userID;
                } else {
                    $newUser = new UserTable();
                    $newUser->username = $validated['username'];
                    $newUser->password = Hash::make($validated['password']);
                    $newUser->status = 'Active';
                    $newUser->save();

                    $guest->userID = $newUser->userID;
                }
            } else {
                if ($user) {
                    $user->status = 'Inactive';
                    $user->save();
                    $guest->userID = null;
                }
            }

            $guest->firstname  = $validated['firstname'];
            $guest->lastname   = $validated['lastname'];
            $guest->mobilenum  = $validated['contactnum'];
            $guest->email      = $validated['email'];
            $guest->gender     = $validated['gender'];
            $guest->birthday   = $validated['birthday'];
            $guest->role       = $validated['role'] ?? 'Daytour Guest';

            $guest->save();

            $userID = $request->session()->get('user_id');

            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Updated a Guest',
                    'date'     => now(),
                ]);
            }

            DB::commit();

            return redirect('receptionist/guest_list')->with('success', 'Guest updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Update failed. ' . $e->getMessage());
        }
    }
}
