<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Models\CottageTable;
use App\Models\DisableReasonTable;
use App\Models\StaffTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ManageCottageController extends Controller
{
    // List all cottages
    public function cottageList()
    {
        $cottage = CottageTable::all();
        return view('manager.cottage_list', compact('cottage'));
    }

    // Show add cottage form
    public function addCottage()
    {
        return view('manager.add_cottages');
    }

    // Submit new cottage
    public function submitCottage(Request $request)
    {
        $validatedData = $request->validate([
            'cottagename' => 'required|string|max:255',
            'capacity' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:webp,png,jpg,jpeg|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $filePath = $request->file('image')->store('cottage_image', 'public');

            $cottage = new CottageTable();
            $cottage->cottagename = $validatedData['cottagename'];
            $cottage->capacity = $validatedData['capacity'];
            $cottage->price = $validatedData['price'];
            $cottage->image = $filePath;
            $cottage->status = 'Available';
            $cottage->amenityID = 1;
            $cottage->save();

            DB::commit();

            Log::info('Cottage created', ['cottageID' => $cottage->cottageID]);

            return redirect('manager/cottage_list')->with('success', 'The cottage has been successfully added');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create cottage', ['error' => $e->getMessage()]);
            return redirect('manager/add_cottages')->withInput()->with('error', 'Failed to add cottage: ' . $e->getMessage());
        }
    }

    // Edit cottage
    public function editCottage(Request $request, $cottageID)
    {
        $cottage = CottageTable::findOrFail($cottageID);

        if ($request->isMethod('get')) {
            return view('manager.edit_cottage', compact('cottage'));
        }

        $validatedData = $request->validate([
            'cottagename' => 'required|string|max:255',
            'capacity' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:webp,png,jpg,jpeg|max:2048',
            'status' => 'required|string',
        ]);

        $hasChanges = (
            $cottage->cottagename !== $validatedData['cottagename'] ||
            $cottage->capacity != $validatedData['capacity'] ||
            $cottage->price != $validatedData['price'] ||
            $cottage->status !== $validatedData['status'] ||
            $request->hasFile('image')
        );

        if (!$hasChanges) {
            return redirect()->back()->withInput()->with('error', 'No changes detected.');
        }

        DB::beginTransaction();

        try {
            $cottage->cottagename = $validatedData['cottagename'];
            $cottage->capacity = $validatedData['capacity'];
            $cottage->price = $validatedData['price'];
            $cottage->status = $validatedData['status'];

            // Replace image if uploaded
            if ($request->hasFile('image')) {
                if ($cottage->image && Storage::disk('public')->exists($cottage->image)) {
                    Storage::disk('public')->delete($cottage->image);
                }
                $cottage->image = $request->file('image')->store('cottage_image', 'public');
            }

            $cottage->save();

            DB::commit();
            Log::info('Cottage updated', ['cottageID' => $cottage->cottageID]);

            return redirect('manager/cottage_list')->with('success', 'Cottage updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update cottage', ['cottageID' => $cottageID, 'error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Failed to update cottage: ' . $e->getMessage());
        }
    }

    // Update cottage status
    public function updateCottageStatus($cottageID, $status, Request $request)
    {
        $cottage = CottageTable::find($cottageID);

        if (!$cottage) {
            return redirect('manager/cottage_list')->with('error', 'Cottage not found.');
        }

        // Get logged-in user and staff info
        $user = $request->session()->get('user_id');
        $staff = StaffTable::where('userID', $user)->first();

        // Get reason from request (from query param or POST)
        $reason = $request->input('reason', ''); // default to empty if not provided

        // Save reason
        DisableReasonTable::create([
            'cottageID' => $cottageID,
            'reason' => $reason,
            'reported_by' => $staff->staffID ?? null, // in case staff not found
            'reported_date' => \Carbon\Carbon::now(),
        ]);

        // Update status
        $cottage->status = $status;
        $cottage->save();

        Log::info('Cottage status updated', [
            'cottageID' => $cottageID,
            'status' => $status,
            'reason' => $reason,
        ]);

        return redirect('manager/cottage_list')->with('success', "Cottage status set to {$status} successfully.");
    }


    // Convenience methods
    public function deactivateCottage($cottageID, Request $request)
    {
        return $this->updateCottageStatus($cottageID, 'Unavailable', $request);
    }

    public function activateCottage($cottageID, Request $request)
    {
        return $this->updateCottageStatus($cottageID, 'Available', $request);
    }

    public function maintenanceCottage($cottageID, Request $request)
    {
        return $this->updateCottageStatus($cottageID, 'Maintenance', $request);
    }
}
