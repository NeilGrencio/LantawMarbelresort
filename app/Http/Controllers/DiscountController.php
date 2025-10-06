<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SessionLogTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\DiscountTable;

class DiscountController extends Controller
{
    public function viewDiscounts(Request $request){
        $discount = DiscountTable::query()->paginate(10);
        foreach($discount as $d){
            $d->percentage = $d->amount * 100;
        }
        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Viewed Discounts',
                'date'     => now(),
            ]);
        }

        return view('manager/discount', ['discount' => $discount]);
    }

    public function deactivateDiscount(Request $request, $discountID){
        $discount = DiscountTable::find(  $discountID);

        if($discount){
            $discount->status = 'Unavailable';
            $discount->save();

            // Get the userID from the session
            $userID = $request->session()->get('user_id');

            // Log the session activity
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Deactivated a Discount: ' . $discount->name,
                    'date'     => now(),
                ]);
            }

            return redirect('manager/discount')->with('success', 'Discount status updated!');
        } else {
            return redirect('manager/discount')->with('error', 'Discount status failed to update!');
        }
    }

    public function activateDiscount(Request $request, $discountID){
        $discount = DiscountTable::find(  $discountID);

        if($discount){
            $discount->status = 'Available';
            $discount->save();

            // Get the userID from the session
            $userID = $request->session()->get('user_id');

            // Log the session activity
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Activated a Discount: ' . $discount->name,
                    'date'     => now(),
                ]);
            }

            return redirect('manager/discount')->with('success', 'Discount status updated!');
        } else {
            return redirect('manager/discount')->with('error', 'Discount status failed to update!');
        }
    }

    public function addDiscount(Request $request){
        if($request->isMethod('get')){
            return view('manager/add_discount');
        }

        if($request->isMethod('post')){
            $validatedData = $request->validate([
                'name' => 'required',
                'amount' => 'required|numeric|min:0|max:1',
                'status' => 'required',
            ]);

            DB::beginTransaction();

            try{
                $discount = new DiscountTable();
                $discount->name = $validatedData['name'];
                $discount->amount = $validatedData['amount'];
                $discount->status = $validatedData['status'];
                $discount->save();

                // Get the userID from the session
                $userID = $request->session()->get('user_id');

                // Log the session activity
                if ($userID) {
                    SessionLogTable::create([
                        'userID'   => $userID,
                        'activity' => 'User Created a Discount: ' . $discount->name,
                        'date'     => now(),
                    ]);
                }

                DB::commit();
                return redirect('manager/discount')->with('success', 'Discount successfully added!');
            } catch (\Exception $e){
                DB::rollback();
                return redirect('manager/add_discount')->withInput()->with('error', 'Discount was not added!');
            }
        }
    }

    public function updateDiscount(Request $request, $discountID){
        $discount = DiscountTable::where('discountID', $discountID)->first();

        if($request->isMethod('get')){
            return view('manager.edit_discount', compact('discount'));
        }

        if($request->isMethod('post')){
            $validatedData = $request->validate([
                'name' => 'required',
                'amount' => 'required|numeric|min:0|max:1',
                'status' => 'required',
            ]);

            $hasChanges = false;
                if (
                    $discount->name != $validatedData['name'] || 
                    $discount->amounnt != $validatedData['amount'] ||
                    $discount->status != $validatedData['status']
                ) {
                    $hasChanges = true;
                }

                if (!$hasChanges) {
                    return redirect()->route('manager.edit_discount', ['discountID' => $discountID])->with('error', 'No changes detected.');
                }

            DB::beginTransaction();

            try{
                $update = [
                    'name' => $validatedData['name'],
                    'amount' => $validatedData['amount'],
                    'status' => $validatedData['status'],
                ];

                DB::table('discount')
                ->where('discountID', $discountID)
                ->update($update);

                // Get the userID from the session
                $userID = $request->session()->get('user_id');

                // Log the session activity
                if ($userID) {
                    SessionLogTable::create([
                        'userID'   => $userID,
                        'activity' => 'User Updated a Discount: ' . $discount->name,
                        'date'     => now(),
                    ]);
                }

                DB::commit();
                return redirect('manager/discount')->with('success', 'The discount was updated!');
            } catch(\Exception $e) {
                DB::rollback();

                return redirect()->route('manager.edit_discount', ['discountID' => $discountID])->withInput()->with('error', 'Update discount failed!');
            }

        }
    }
}
