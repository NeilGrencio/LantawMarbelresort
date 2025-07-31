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
use App\Models\PaymentTable;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class DayTourController extends Controller
{
    public function viewDayTour(){
        $amenities = AmenityTable::where('status', 'Available')->get();
        $discount = DiscountTable::where('status', 'Available')->get();

        return view('receptionist/daytour', compact('amenities', 'discount'));
    }

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

            foreach ($validated['amenity'] ?? [] as $selectedAmenity) {
                $amenity = AmenityTable::where('amenityID', $selectedAmenity)->firstOrFail();

                $totalAdult = $amenity->adultprice * $validated['amenity_adult_guest'];
                $totalChild = $amenity->childprice * $validated['amenity_child_guest'];
                $totalAmount = $totalAdult + $totalChild;

                $discountID = $validated['discount'] ?? null;

                if (!is_null($discountID)) {
                    $discountAmount = DiscountTable::find($discountID)?->amount ?? 0;
                    $totalAmount -= ($totalAmount * ($discountAmount / 100));
                }

                $bill = BillingTable::create([
                    'totalamount' => $totalAmount,
                    'datebilled' => Carbon::now()->toDateString(),
                    'status' => 'Paid',
                    'amenityID' => $amenity->amenityID,
                    'discount' => $discountID,
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

                $text = "Guest: {$guest->firstname} {$guest->lastname}, Amenity: {$amenity->amenityname}";
                $qrFilename = 'qrcodes/qrcode_' . $guest->guestID . '_' . $amenity->amenityID . '.svg';

                $qrSvg = QrCode::format('svg')->size(300)->generate($text);

                Storage::disk('public')->put($qrFilename, $qrSvg);

                QRTable::create([
                    'qrcode' => $qrFilename,
                    'accessdate' => Carbon::now()->toDateString(),
                    'amenityID' => $amenity->amenityID,
                    'guestID' => $guest->guestID,
                ]);
            }

            return view('receptionist/daytourDashboard', [
                'success' => 'Daytour created successfully.',
                'qrcodes' => QRTable::with(['guest', 'amenity'])
                    ->where('guestID', $guest->guestID)
                    ->whereDate('accessdate', Carbon::today())
                    ->get()
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Daytour failed to create: ' . $e->getMessage());
        }
    }

    public function daytourDashboard(){
        $qrcode = QRTable::select('qrcodes.*')->get();
        return view('receptionist/daytourDashboard', compact('qrcode'));
    }

}
