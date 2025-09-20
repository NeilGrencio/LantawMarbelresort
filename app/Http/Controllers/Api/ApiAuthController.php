<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // <-- Add this
use App\Models\User;
use App\Models\GuestTable;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    // Signup
    public function signup(Request $request)
    {
        Log::info('Signup request received', ['headers' => $request->headers->all()]);
        $validator = Validator::make($request->all(), [
            'username'  => 'required|string|min:5|max:20|unique:users,username',
            'password'  => [
                'required',
                'min:8',
                'max:20',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/'
            ],
            'cpassword' => 'required|same:password',
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'mobilenum' => 'nullable|string|max:11',
            'email'     => 'nullable|email|unique:guest,email',
            'gender'    => 'required|string|max:255',
            'birthday'  => 'nullable|date',
            'validID'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'avatar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();
        DB::beginTransaction();

        try {
            // Create User
            $user = User::create([
                'username' => $validatedData['username'],
                'password' => Hash::make($validatedData['password']),
                'status'   => 'Active',
            ]);
            Log::info('New user created', ['user_id' => $user->id]);

            // Handle uploads
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                Log::info('Avatar uploaded', ['path' => $avatarPath]);
            }

            $idPath = null;
            if ($request->hasFile('validID')) {
                $idPath = $request->file('validID')->store('ids', 'public');
                Log::info('Valid ID uploaded', ['path' => $idPath]);
            }

            // Create Guest record
            $guest = GuestTable::create([
                'firstname' => $validatedData['firstname'],
                'lastname'  => $validatedData['lastname'],
                'mobilenum' => $validatedData['mobilenum'] ?? null,
                'email'     => $validatedData['email'] ?? null,
                'gender'    => $validatedData['gender'],
                'birthday'  => $validatedData['birthday'] ?? null,
                'validID'   => $idPath,       // path in storage/app/public/ids
                'avatar'    => $avatarPath,   // path in storage/app/public/avatars
                'role'      => 'guest',
                'userID'    => $user->id,
            ]);
            Log::info('Guest profile created', ['guest_id' => $guest->guestID]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'user'    => $user,
                'guest'   => $guest
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Signup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to register user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        Log::info('Login attempt received');

        // Validate input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            Log::warning('Login validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        // Load user with guest and staff profile
        $user = User::with(['guest', 'staff'])
            ->where('username', $validatedData['username'])
            ->first();

        if (!$user) {
            Log::warning('Login failed - user not found', ['username' => $validatedData['username']]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Verify password
        if (!Hash::check($validatedData['password'], $user->password)) {
            Log::warning('Login failed - password mismatch', ['username' => $validatedData['username']]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Small helper to safely convert to UTF-8
        $toUtf8 = function ($value) {
            return is_string($value) ? mb_convert_encoding($value, 'UTF-8', 'UTF-8') : $value;
        };

        // Determine role (guest or staff)
        $role = null;
        $profile = null;

        if ($user->guest) {
            $role = 'guest';
            $profile = [
                'guestID'   => $toUtf8($user->guest->guestID),
                'firstname' => $toUtf8($user->guest->firstname),
                'lastname'  => $toUtf8($user->guest->lastname),
                'email'     => $toUtf8($user->guest->email),
            ];
        } elseif ($user->staff) {
            $role = 'staff';
            $profile = [
                'staffID'   => $toUtf8($user->staff->staffID),
                'firstname' => $toUtf8($user->staff->firstname),
                'lastname'  => $toUtf8($user->staff->lastname),
                'email'     => $toUtf8($user->staff->email),
                'role'      => $toUtf8($user->staff->role), // staff level
            ];
        }

        if (!$role) {
            Log::error('Login failed - user has no linked profile', ['user_id' => $user->userID]);
            return response()->json([
                'success' => false,
                'message' => 'User has no linked profile (guest/staff missing)'
            ], 500);
        }

        Log::info('Login successful', ['user_id' => $user->userID, 'role' => $role]);

        // Build safe response
        return response()->json([
            'success'    => true,
            'message'    => 'Login successful',
            'token_type' => 'Bearer',
            'user' => [
                'id'       => $toUtf8($user->userID),
                'username' => $toUtf8($user->username),
                'role'     => $role,
            ],
            'profile' => $profile
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }




    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        Log::info('User logged out', ['user_id' => $request->user()->id]);

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
