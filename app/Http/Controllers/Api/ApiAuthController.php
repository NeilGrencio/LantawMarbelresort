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

class ApiAuthController extends Controller
{
    // Signup
    public function signup(Request $request)
    {
        $validatedData = $request->validate([
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
            'mobilenum' => 'nullable|digits:10',
            'email'     => 'nullable|email|unique:guest,email',
            'gender'    => 'required|string|max:255',
            'birthday'  => 'nullable|date',
            'validID'   => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
            'avatar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'password.regex' => 'Password must include uppercase, lowercase, number, and special character.',
        ]);

        DB::beginTransaction();

        try {
            // Create User
            $user = User::create([
                'username' => $validatedData['username'],
                'password' => Hash::make($validatedData['password']),
                'status'   => 'Active',
            ]);
            Log::info('New user created', ['user_id' => $user->id]);

            // Save avatar file if uploaded
            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                Log::info('Avatar uploaded', ['path' => $avatarPath]);
            }

            // Save validID file if uploaded
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
                'validID'   => $idPath,
                'avatar'    => $avatarPath,
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

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            Log::warning('Login failed', ['username' => $request->username]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Optional: generate API token
        $token = $user->createToken('api_token')->plainTextToken;
        Log::info('Login successful', ['user_id' => $user->id]);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
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
