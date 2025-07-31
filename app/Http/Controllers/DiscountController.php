<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\DiscountTable;

class DiscountController extends Controller
{
    public function viewDiscounts(){
        $discount = DiscountTable::query()->paginate(10);
        foreach($discount as $d){
            $d->percentage = $d->amount * 100;
        }

        return view('manager/discount', ['discount' => $discount]);
    }

    public function deactivateDiscount($discountID){
        $discount = DiscountTable::find(  $discountID);

        if($discount){
            $discount->status = 'Unavailable';
            $discount->save();
            return redirect('manager/discount')->with('success', 'Discount status updated!');
        } else {
            return redirect('manager/discount')->with('error', 'Discount status failed to update!');
        }
    }

    public function activateDiscount($discountID){
        $discount = DiscountTable::find(  $discountID);

        if($discount){
            $discount->status = 'Available';
            $discount->save();
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

                DB::commit();
                return redirect('manager/discount')->with('success', 'The discount was updated!');
            } catch(\Exception $e) {
                DB::rollback();

                return redirect()->route('manager.edit_discount', ['discountID' => $discountID])->withInput()->with('error', 'Update discount failed!');
            }

        }
    }
}
