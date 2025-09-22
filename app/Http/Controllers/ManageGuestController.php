<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use thiagoalessio\TesseractOCR\TesseractOCR;

use App\Models\GuestTable;
use App\Models\UserTable;

class ManageGuestController extends Controller
{
    // List all guests
    public function guestList()
    {
        $guest = GuestTable::paginate(10);
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
        if ($request->hasFile('validID')) {
            $ocrText = (new TesseractOCR($request->file('validID')->getRealPath()))
                ->lang('eng')
                ->run();

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
                $user->role = $validatedData['role'];
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

            Log::info('Guest registered', ['guestID' => $guest->guestID, 'userID' => $userID]);
            return redirect()->route('manager.add_user')->with('success', 'Guest registered successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to register guest', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
        }
    }

    // View guest details (Manager)
    public function viewGuest($guestID)
    {
        $guest = GuestTable::findOrFail($guestID);
        $user = $guest->Users ?? null;
        return view('manager.view_guest', compact('guest', 'user'));
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
