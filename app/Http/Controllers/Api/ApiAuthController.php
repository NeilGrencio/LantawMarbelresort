<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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
                'required', 'min:8', 'max:20',
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

            // Handle avatar upload
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('guest_images', 'public');
            }

            // Handle valid ID upload
            $idPath = null;
            if ($request->hasFile('validID')) {
                $idPath = $request->file('validID')->store('guestid_images', 'public');
            }

            // Create Guest record
            $guest = GuestTable::create([
                'firstname' => $validatedData['firstname'],
                'lastname'  => $validatedData['lastname'],
                'mobilenum' => $validatedData['mobilenum'] ?? null,
                'email'     => $validatedData['email'] ?? null,
                'gender'    => $validatedData['gender'],
                'birthday'  => $validatedData['birthday'] ?? null,
                'validID'   => $idPath,
                'avatar'    => $avatarPath,
                'role'      => 'Guest',
                'userID'    => $user->userID,
            ]);

            DB::commit();

            // Return URLs for uploaded files
            $avatarUrl = $avatarPath ? route('guest.image', basename($avatarPath)) : null;
            $validIDUrl = $idPath ? route('guestid.image', basename($idPath)) : null;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'user'    => $user,
                'guest'   => array_merge($guest->toArray(), [
                    'avatar_url' => $avatarUrl,
                    'validID_url' => $validIDUrl
                ])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Signup failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to register user: ' . $e->getMessage()
            ], 500);
        }
    }

    // Login
    public function login(Request $request)
    {
        Log::info('Login attempt received');

        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();

        $user = User::with(['guest', 'staff'])->where('username', $validatedData['username'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $role = null;
        $profile = null;

        if ($user->guest) {
            $role = 'guest';
            $profile = [
                'guestID'   => $user->guest->guestID,
                'firstname' => $user->guest->firstname,
                'lastname'  => $user->guest->lastname,
                'email'     => $user->guest->email,
                'avatar_url'=> $user->guest->avatar ? route('guest.image', basename($user->guest->avatar)) : null,
                'validID_url'=> $user->guest->validID ? route('guestid.image', basename($user->guest->validID)) : null
            ];
        } elseif ($user->staff) {
            $role = 'staff';
            $profile = [
                'staffID'   => $user->staff->staffID,
                'firstname' => $user->staff->firstname,
                'lastname'  => $user->staff->lastname,
                'email'     => $user->staff->email,
                'role'      => $user->staff->role,
                'avatar_url'=> $user->staff->avatar ? route('staff.image', basename($user->staff->avatar)) : null
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user'    => [
                'id'       => $user->userID,
                'username' => $user->username,
                'role'     => $role,
            ],
            'profile' => $profile
        ], 200);
    }
}
