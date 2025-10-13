<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\GuestTable;
use App\Models\AmenityTable;
use App\Models\BillingTable;
use App\Models\DiscountTable;
use App\Models\QRTable;
use App\Models\SessionLogTable;
use App\Models\PaymentTable;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DayTourController extends Controller
{
    public function viewDayTour(){
        $amenities = AmenityTable::where('status', 'Available')->get();
        $discount = DiscountTable::where('status', 'Available')->get();
        $guest = GuestTable::get();

        return view('receptionist/daytour', compact('amenities', 'discount', 'guest'));
    }

    public function guestSuggestions(Request $request)
    {
        $search = $request->query('q');
        $guests = GuestTable::where('firstname', 'LIKE', "%{$search}%")
            ->orWhere('lastname', 'LIKE', "%{$search}%")
            ->limit(5)
            ->get(['guestID', 'firstname', 'lastname']);

        return response()->json($guests);
    }
    
    /*
    public function checkValidID($validID){
        if ($validID) {
            $imagePath = $validID->getRealPath();
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
        $validIDPath = $validID->store('valid_ids', 'public');

        return $validIDPath;
    }
    */

    public function createDayTour(Request $request)
    {
        $validated = $request->validate([
            'amenity' => 'nullable|array|min:1',
            'amenity.*' => 'exists:amenities,amenityID',
            'firstname' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/',
            'lastname' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\'\.]+$/',
            'contactnum' => 'sometimes|unique:guest,mobilenum',
            'email' => 'sometimes|max:255|unique:guest,email',
            'gender' => 'sometimes',
            'birthday' => 'sometimes',
            'validID' => 'sometimes',
            'guestamount' => 'required|integer|min:1|max:50',
            'amenity_adult_guest' => 'required|integer|min:0|max:50',
            'amenity_child_guest' => 'required|integer|min:0|max:50',
            'payment' => 'required|in:cash,gcash',
            'cashamount' => 'required_if:payment,cash|numeric|min:0',
            'discount' => 'nullable',
        ]);

        try {
            $validIDPath = null;
            if ($request->hasFile('validID')) {
                $validIDPath = $request->file('validID')->store('valid_ids', 'public');
            }

            $defaultAvatar = 'images/profile.jpg';

            $guest = GuestTable::firstOrCreate(
                [
                    'firstname' => $validated['firstname'],
                    'lastname' => $validated['lastname'],
                ],
                [
                    'mobilenum' => $validated['contactnum'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'gender' => $validated['gender'] ?? null,
                    'birthday' => $validated['birthday'] ?? null,
                    'validID' => $validIDPath,
                    'role' => 'Daytour Guest',
                    'avatar' => $defaultAvatar,
                ]
            );

            $adultcount = $validated['amenity_adult_guest'] ?? 0;
            $childcount = $validated['amenity_child_guest'] ?? 0;

            foreach ($validated['amenity'] ?? [] as $selectedAmenity) {
                $amenity = AmenityTable::where('amenityID', $selectedAmenity)->firstOrFail();
                $totalAdult = $amenity->adultprice * $adultcount;
                $totalChild = $amenity->childprice * $childcount;
                $totalAmount = $totalAdult + $totalChild;
                $discountID = !empty($validated['discount']) && $validated['discount'] != 0
                    ? $validated['discount']
                    : null;

                if ($discountID) {
                    $discountAmount = DiscountTable::find($discountID)?->amount ?? 0;
                    $totalAmount -= ($totalAmount * ($discountAmount / 100));
                }

                $bill = BillingTable::create([
                    'totalamount' => $totalAmount,
                    'datebilled' => Carbon::now()->toDateString(),
                    'status' => 'Unpaid',
                    'amenityID' => $amenity->amenityID,
                    'discountID' => $discountID,
                    'guestID' => $guest->guestID,
                ]);

                $amountPaid = $validated['cashamount'] ?? 0;
                $change = $amountPaid - $totalAmount;

                PaymentTable::create([
                    'totaltender' => $amountPaid,
                    'totalchange' => $change < 0 ? 0 : $change,
                    'datepayment' => Carbon::now()->toDateString(),
                    'guestID' => $guest->guestID,
                    'billingID' => $bill->billingID,
                ]);

                if ($amountPaid >= $totalAmount) {
                    $bill->update(['status' => 'Paid']);
                } elseif ($amountPaid > 0 && $amountPaid < $totalAmount) {
                    $bill->update(['status' => 'Unpaid']);
                } else {
                    $bill->update(['status' => 'Unpaid']);
                }

                $text = "Guest: {$guest->firstname} {$guest->lastname}, Amenity: {$amenity->amenityname}";
                $qrFilename = 'qrcodes/qrcode_' . $guest->guestID . '_' . $amenity->amenityID . '.svg';
                $qrSvg = QrCode::format('svg')->size(300)->generate($text);

                if (!Storage::disk('public')->exists($qrFilename)) {
                    Storage::disk('public')->put($qrFilename, $qrSvg);
                }

                $qrPath = Storage::url($qrFilename);

                QRTable::create([
                    'qrcode' => $qrPath,
                    'accessdate' => Carbon::now()->toDateString(),
                    'amenityID' => $amenity->amenityID,
                    'guestID' => $guest->guestID,
                ]);
            }

            $userID = $request->session()->get('user_id');

            if ($userID) {
                SessionLogTable::create([
                    'userID' => $userID,
                    'activity' => 'User Created a Day Tour',
                    'date' => now(),
                ]);
            }

            return redirect()
                ->route('receptionist.daytour_dashboard')
                ->with('success', 'Daytour successfully created!');
        } catch (\Exception $e) {
            return back()->with('error', 'Daytour failed to create: ' . $e->getMessage());
        }
    }

    public function daytourDashboard()
    {
        $today = Carbon::now()->toDateString();
        $qrcode = QRTable::with(['guest', 'amenity'])->orderBy('qrID', 'desc')->get();
        $amenity = AmenityTable::select('amenityID', 'amenityname', 'capacity', 'status')->get();

        $recent = QRTable::with(['guest', 'amenity'])
            ->whereDate('accessdate', $today)
            ->orderBy('qrID', 'desc')
            ->get();

        $usedCounts = QRTable::whereDate('accessdate', $today)
            ->select('amenityID', DB::raw('COUNT(*) as used_count'))
            ->groupBy('amenityID')
            ->pluck('used_count', 'amenityID');

        $amenitiesData = $amenity->map(function ($a) use ($usedCounts) {
            $used = $usedCounts[$a->amenityID] ?? 0;
            $available = max($a->capacity - $used, 0);

            return [
                'amenityID' => $a->amenityID,
                'amenityname' => $a->amenityname,
                'capacity' => $a->capacity,
                'used' => $used,
                'available' => $available,
            ];
        });

        $differentAmenities = $amenity->where('status', 'Available')
            ->pluck('amenityname')
            ->unique()
            ->values();

        return view('receptionist/daytourDashboard', compact('amenity', 'recent', 'qrcode', 'differentAmenities', 'amenitiesData'));
    }

}
