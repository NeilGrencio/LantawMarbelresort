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
        $user = User::where('username', $validatedData['username'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            Log::warning('Login failed', ['username' => $validatedData['username']]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;
        Log::info('Login successful', ['user_id' => $user->id]);

        return response()->json([
            'success'      => true,
            'message'      => 'Login successful',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
            'guest'        => $user->guest ?? null
        ]);
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
