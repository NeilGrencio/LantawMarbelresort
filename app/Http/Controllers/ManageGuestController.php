<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Http\Request;

use App\Models\GuestTable;
use App\Models\UserTable;

class ManageGuestController extends Controller
{
    public function guestList(){
        $guest = GuestTable::paginate(10);
        

        return view('manager/guest_list', compact('guest'));
    }

    public function addGuest(){
        return view('manager/add_guest');
    }

    public function submitGuest(Request $request) {
        // First validate the common fields
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

        // Extend rules based on role
        if ($request->input('role') === 'Guest') {
            $roleRules = [
                'username' => 'required|min:5|max:20|unique:Users,username',
                'password' => [
                    'required', 'min:8', 'max:20',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/'
                ],
                'cpassword' => 'required|same:password',
                'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ];
        } else { // Day Tour Guest
            $roleRules = [
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ];
        }

        $validatedData = $request->validate(array_merge($commonRules, $roleRules));

        // OCR Verification
        if ($request->hasFile('validID')) {
            $imagePath = $request->file('validID')->getRealPath();
            $ocrText = (new TesseractOCR($imagePath))->lang('eng')->run();

            $requiredHeaders1 = [
                'REPUBLIKA NG PILIPINAS',
                'Republic of the Philippines',
                'PAMBANSANG PAGKAKAKILANLAN',
                'Philippine Identification'
            ];
            $requiredHeaders2 = [
                'REPUBLIKA NG PILIPINAS',
                'Republic of the Philippines',
                'PAMBANSANG PAGKAKAKILANLAN',
                'Philippine Identification Card'
            ];
            $allPossibleHeaders = array_merge($requiredHeaders1, $requiredHeaders2);

            $headerFound = true;
            foreach ($allPossibleHeaders as $header) {
                if (stripos($ocrText, $header) === false) {
                    $headerFound = false;
                    break;
                }
            }

            $pcnFound = preg_match('/\s?\d{4}-\d{4}-\d{4}-\d{4}/', $ocrText);

            if (!$headerFound || !$pcnFound) {
                return redirect('manager/add_user')
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
            // Create user only if role is Guest
            if ($request->input('role') === 'Guest') {
                $user = new UserTable();
                $user->username = $validatedData['username'];
                $user->password = Hash::make($validatedData['password']);
                $user->role = $validatedData['role'];
                $user->save();
                $userID = $user->userID;
            } else {
                // Day Tour Guests may not have a user account
                $userID = null;
            }

            // Create Guest Record
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

            return redirect()->route('manager.add_user')->with('success', 'Guest registered successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
        }
    }

    public function viewGuest($guestID){
        $guest = GuestTable::where('guestID', $guestID)->first();
        $user = $guest->Users;

        return view('manager/view_guest', compact('guest', 'user'));
    }

    public function viewGuest($guestID){
        $guest = GuestTable::where('guestID', $guestID)->first();
        $user = $guest->Users;

        return view('manager/view_guest', compact('guest', 'user'));
    }

    public function guestListReceptionist()
    {
        $guest = GuestTable::paginate(10);
        return view('receptionist.guest_list_receptionist', compact('guest'));
    }

    public function viewGuestReceptionist($guestID){
        $guest = GuestTable::where('guestID', $guestID)->first();
        $user = $guest->Users;

        return view('receptionist/view_guest', compact('guest', 'user'));
    }

}
