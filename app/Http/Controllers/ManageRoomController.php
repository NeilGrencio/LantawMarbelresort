<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AmenityTable;
use App\Models\DiscountTable;
use App\Models\MenuTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\RoomTable;
use App\Models\InclusionTable;
use App\Models\SessionLogTable;
use App\Models\DisableReasonTable;
use App\Models\RoomTypeTable;
use App\Models\User;
use App\Models\StaffTable;

class ManageRoomController extends Controller
{
    // Display all rooms (for web)
    public function roomList(Request $request)
    {
        $viewType = $request->query('view', 'rooms'); // default: show rooms
        $discount = DiscountTable::where('type', 'Promo')->get();

        if ($viewType === 'roomtypes') {
            // Show room types
            $roomtypes = RoomTypeTable::leftJoin('discount', 'room_type.discountID', '=', 'discount.discountID')
                ->select('room_type.*', 'discount.name as discount_name', 'discount.flatamount')
                ->get();

            foreach ($roomtypes as $type) {
                $type->image_url = $type->image
                    ? route('room.image', ['filename' => basename($type->image)])
                    : null;
            }

            $userID = $request->session()->get('user_id');
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Viewed Room Type List',
                    'date'     => now(),
                ]);
            }

            return view('manager.room_list', [
                'viewType' => $viewType,
                'roomtypes' => $roomtypes,
                'discount' => $discount,
                'rooms' => collect(), // empty
            ]);
        }

        // Otherwise, show individual rooms
        $rooms = RoomTable::where('status', 'Available')->get();

        foreach ($rooms as $room) {
            $room->image_url = $room->image
                ? route('room.image', ['filename' => basename($room->image)])
                : null;
        }

        $userID = $request->session()->get('user_id');
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Viewed Room List',
                'date'     => now(),
            ]);
        }

        //dd($rooms);

        return view('manager.room_list', [
            'viewType' => $viewType,
            'rooms' => $rooms,
            'discount' => $discount,
            'roomtypes' => collect(), // empty
        ]);
    }

    public function deactivatedroomList(Request $request)
    {
        $rooms = RoomTable::where('status', '!=', 'Available')->get();
        $discount = DiscountTable::where('type', 'Promo')->get();
        Log::info('Fetched room list', ['count' => $rooms->count()]);

        // Prepare image URLs via route
        foreach ($rooms as $room) {
            $room->image_url = $room->image
                ? route('room.image', ['filename' => basename($room->image)])
                : null;
        }

        // Get the userID from the session
        $userID = $request->session()->get('user_id');

        // Log the session activity
        if ($userID) {
            SessionLogTable::create([
                'userID'   => $userID,
                'activity' => 'User Viewed Room List',
                'date'     => now(),
            ]);
        }

        return view('manager.deactivated_room_list', compact('rooms', 'discount'));
    }

    public function addRoomType(Request $request)
    {
        if ($request->isMethod('get')) {
            // Fetch required data for the form
            $amenities = AmenityTable::where('status', 'Available')
                ->where('type', 'Facility')
                ->get();

            $menu = MenuTable::where('itemtype', 'Breakfast')->get();
            $discounts = DiscountTable::where('type', 'Promo')->get();

            // Add image URLs
            foreach ($amenities as $amenity) {
                $amenity->image_url = $amenity->image
                    ? route('amenity.image', ['filename' => basename($amenity->image)])
                    : null;
            }

            foreach ($menu as $item) {
                $item->image_url = $item->image
                    ? route('menu.image', ['filename' => basename($item->image)])
                    : null;
            }

            return view('manager.add_roomtype', compact('amenities', 'menu', 'discounts'));
        }

        // =======================
        // Handle POST submission
        // =======================
        $validated = $request->validate([
            'description' => 'required|string',
            'basecapacity' => 'required|numeric|min:1',
            'maxcapacity' => 'required|numeric|min:1',
            'roomtype' => 'required|string',
            'price' => 'required|numeric|min:0',
            'extra' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpg,jpeg,webp,png',
            'amenities' => 'array',
            'menu' => 'array',
            'discountID' => 'nullable|exists:discount,discountID',
        ]);

        DB::beginTransaction();
        try {
            // Create room type
            $roomType = new RoomTypeTable();
            $roomType->description = $validated['description'];
            $roomType->basecapacity = $validated['basecapacity'];
            $roomType->maxcapacity = $validated['maxcapacity'];
            $roomType->roomtype = $validated['roomtype'];
            $roomType->price = $validated['price'];
            $roomType->extra = $validated['extra'];

            // Handle image upload
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('room_images', 'public');
                $roomType->image = $path;
                Log::info('Room type image uploaded', ['image' => $roomType->image]);
            }

            // Attach discount if selected
            if (!empty($validated['discountID'])) {
                $roomType->discountID = $validated['discountID'];
            }

            $roomType->save();

            // Save inclusions (amenities and menu)
            if (!empty($validated['amenities'])) {
                foreach ($validated['amenities'] as $amenityID) {
                    InclusionTable::create([
                        'roomtypeID' => $roomType->roomtypeID, // ✅ fixed
                        'amenityID' => $amenityID,
                        'menuID' => null,
                    ]);
                }
            }

            if (!empty($validated['menu'])) {
                foreach ($validated['menu'] as $menuID) {
                    InclusionTable::create([
                        'roomtypeID' => $roomType->roomtypeID, // ✅ fixed
                        'amenityID' => null,
                        'menuID' => $menuID,
                    ]);
                }
            }

            // Log user action
            $userID = $request->session()->get('user_id');
            if ($userID) {
                SessionLogTable::create([
                    'userID' => $userID,
                    'activity' => 'Created Room Type: ' . $roomType->roomtype,
                    'date' => now(),
                ]);
            }

            DB::commit();

            Log::info('Room type created successfully', ['roomtypeID' => $roomType->roomtypeID]);
            return redirect()->route('manager.room_list')
                ->with('success', 'Room type successfully added!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create room type', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create room type: ' . $e->getMessage());
        }
    }

    // Show add room form
    public function addRoom()
    {
        $amenities = AmenityTable::where('status', 'Available')
            ->where('type', 'Facility')
            ->get();

        $roomType = RoomTypeTable::leftJoin('discount', 'room_type.discountID', '=', 'discount.discountID')
            ->select(
                'room_type.roomtypeID',
                'room_type.roomtype',
                'room_type.basecapacity',
                'room_type.maxcapacity',
                'room_type.price',
                'room_type.extra',
                'room_type.description',
                'room_type.image',
                'discount.name as discount_name',
                'discount.flatamount as discount_amount'
            )
            ->get();

        $menu = MenuTable::where('itemtype', 'Breakfast')->get();
        $discounts = DiscountTable::where('type', 'Promo')->get();

        foreach ($roomType as $rtype) {
            $rtype->image_url = $rtype->image
                ? route('room.image', ['filename' => basename($rtype->image)])
                : null;
        }

        foreach ($amenities as $amenity) {
            $amenity->image_url = $amenity->image
                ? route('amenity.image', ['filename' => basename($amenity->image)])
                : null;
        }

        foreach ($menu as $item) {
            $item->image_url = $item->image
                ? route('menu.image', ['filename' => basename($item->image)])
                : null;
        }

        return view('manager.add_room', compact('amenities', 'menu', 'discounts', 'roomType'));
    }

    public function roomTypes()
    {
        $roomtypes = RoomTypeTable::leftJoin('discount', 'room_type.discountID', '=', 'discount.discountID')
            ->select(
                'room_type.roomtypeID',
                'room_type.roomtype',
                'room_type.basecapacity',
                'room_type.maxcapacity',
                'room_type.price',
                'room_type.extra',
                'room_type.description',
                'room_type.discountID',
                'discount.name as discount_name',
                'discount.flatamount as discount_amount'
            )
            ->get();

        return response()->json($roomtypes);
    }

    // Save new room
    public function saveRoom(Request $request)
    {
        $validatedData = $request->validate([
            'roomnum' => 'required|numeric|unique:rooms,roomnum',
            'roomtypeID' => 'required|numeric',
            'status' => 'required|string',
        ]);

        DB::beginTransaction();
        try {

            $room = new RoomTable();
            $room->roomnum = $validatedData['roomnum'];
            $room->status = $validatedData['status'];
            $room->roomtypeID = $validatedData['roomtypeID'];

            $room->save();

            $userID = $request->session()->get('user_id');
            if ($userID) {
                SessionLogTable::create([
                    'userID' => $userID,
                    'activity' => 'User Created a Room: ' . $room->roomnum,
                    'date' => now(),
                ]);
            }

            DB::commit();
            Log::info('Room created successfully', ['roomID' => $room->roomID]);
            return redirect()->route('manager.room_list')->with('success', 'Room successfully added!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create room', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Failed to create room: ' . $e->getMessage());
        }
    }

    // Edit room
    public function editRoom(Request $request, $roomID)
    {
        $room = RoomTable::findOrFail($roomID);
        $amenities = AmenityTable::where('status', 'Available')
            ->where('type', 'Facility')
            ->get();

        $menu = MenuTable::where('itemtype', 'Breakfast')->get();
        $discounts = DiscountTable::where('type', 'Promo')->get();

        foreach ($amenities as $amenity) {
            $amenity->image_url = $amenity->image
                ? route('amenity.image', ['filename' => basename($amenity->image)])
                : null;
        }

        foreach ($menu as $item) {
            $item->image_url = $item->image
                ? route('menu.image', ['filename' => basename($item->image)])
                : null;
        }

        return view('manager.add_room', compact('amenities', 'menu', 'discounts'));

        // Current inclusions
        $selectedAmenities = InclusionTable::where('roomID', $roomID)
            ->pluck('amenityID')
            ->toArray();

        $selectedMenu = InclusionTable::where('roomID', $roomID)
            ->pluck('menuID')
            ->toArray();

        if ($request->isMethod('get')) {
            return view('manager.edit_room', compact('room', 'amenities', 'menu', 'selectedAmenities', 'selectedMenu', 'discounts'));
        }

        // Validate form data
        $validatedData = $request->validate([
            'roomnum'     => 'required|numeric',
            'description' => 'required|string',
            'roomtype'    => 'required|string',
            'status'      => 'required|string|in:Available,Unavailable,Maintenance',
            'price'       => 'required|numeric|min:0',
            'extra'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp',
            'amenities'   => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,amenityID',
            'menu'        => 'nullable|array',
            'menu.*'      => 'integer|exists:menu,menuID',
            'discountID'  => 'nullable|integer|exists:discount,discountID',
        ]);


        DB::beginTransaction();
        try {
            $hasChanges = false;

            // Only update changed fields
            $updateFields = [];
            foreach (['roomnum', 'description', 'roomtype', 'status', 'price', 'extra'] as $field) {
                if ($room->$field != $validatedData[$field]) {
                    $updateFields[$field] = $validatedData[$field];
                }
            }

            // Handle image change
            if ($request->hasFile('image')) {
                if ($room->image && Storage::disk('public')->exists($room->image)) {
                    Storage::disk('public')->delete($room->image);
                }
                $updateFields['image'] = $request->file('image')->store('room_images', 'public');
            }

            $discountID = $validatedData['discountID'] ?? null;

            if ($room->discountID != $discountID) {
                $updateFields['discountID'] = $discountID;
                $hasChanges = true;
            }

            if (!empty($updateFields)) {
                $room->update($updateFields);
                $hasChanges = true;
            }

            // Handle amenities
            $newAmenities = $validatedData['amenities'] ?? [];
            sort($newAmenities);
            sort($selectedAmenities);

            if ($newAmenities !== $selectedAmenities) {
                InclusionTable::where('roomID', $room->roomID)->whereNotNull('amenityID')->delete();
                foreach ($newAmenities as $amenityID) {
                    InclusionTable::create([
                        'roomID' => $room->roomID,
                        'amenityID' => $amenityID,
                        'menuID' => null
                    ]);
                }
                $hasChanges = true;
            }

            // Handle menu
            $newMenu = $validatedData['menu'] ?? [];
            sort($newMenu);
            sort($selectedMenu);

            if ($newMenu !== $selectedMenu) {
                InclusionTable::where('roomID', $room->roomID)->whereNotNull('menuID')->delete();
                foreach ($newMenu as $menuID) {
                    InclusionTable::create([
                        'roomID' => $room->roomID,
                        'amenityID' => null,
                        'menuID' => $menuID
                    ]);
                }
                $hasChanges = true;
            }

            // Log session activity
            $userID = $request->session()->get('user_id');
            if ($userID && $hasChanges) {
                SessionLogTable::create([
                    'userID' => $userID,
                    'activity' => 'User Updated Room: ' . $room->roomnum,
                    'date' => now(),
                ]);
            }

            DB::commit();

            if ($hasChanges) {
                return redirect()->route('manager.room_list')->with('success', 'Room updated successfully.');
            } else {
                return redirect()->route('manager.edit_room', ['roomID' => $roomID])
                                ->with('info', 'No changes were made.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update room', ['roomID' => $roomID, 'error' => $e->getMessage()]);
            return redirect()->route('manager.edit_room', ['roomID' => $roomID])
                            ->withInput()
                            ->with('error', 'Failed to update room: ' . $e->getMessage());
        }
    }
    
    public function deactivateRoom($roomID, Request $request){
        $room = RoomTable::findOrFail($roomID);
        $reasonText = $request->query('reason'); // reason from modal

        DB::transaction(function() use ($room, $request, $reasonText) {
            $room->status = 'Unavailable';
            $room->save();

            $userID = $request->session()->get('user_id');
            $staffID = $userID ? StaffTable::where('userID', $userID)->value('staffID') : null;

            if ($reasonText && $staffID) {
                DisableReasonTable::create([
                    'roomID'        => $room->roomID,
                    'reason'        => $reasonText,
                    'reported_by'   => $staffID,
                    'reported_date' => now()
                ]);
            }

            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Deactivated a Room: ' . $room->roomnum,
                    'date'     => now(),
                ]);
            }
        });

        return redirect()->route('manager.room_list')->with('success', 'Room status updated successfully.');
    }

    public function maintenanceRoom($roomID, Request $request){
        $room = RoomTable::findOrFail($roomID);
        $reasonText = $request->query('reason');

        DB::transaction(function() use ($room, $request, $reasonText) {
            $room->status = 'Maintenance';
            $room->save();

            $userID = $request->session()->get('user_id');
            $staffID = $userID ? StaffTable::where('userID', $userID)->value('staffID') : null;

            if ($reasonText && $staffID) {
                DisableReasonTable::create([
                    'roomID'        => $room->roomID,
                    'reason'        => $reasonText,
                    'reported_by'   => $staffID,
                    'reported_date' => now()
                ]);
            }

            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Set Room to Maintenance: ' . $room->roomnum,
                    'date'     => now(),
                ]);
            }
        });

        return redirect()->route('manager.room_list')->with('success', 'Room status updated successfully.');
    }

    public function activateRoom($roomID, Request $request){
        $room = RoomTable::findOrFail($roomID);

        DB::transaction(function() use ($room, $request) {
            $room->status = 'Available';
            $room->save();

            // Remove any disable/maintenance reason records for this room
           // DisableReasonTable::where('roomID', $room->roomID)->delete();

            $userID = $request->session()->get('user_id');
            if ($userID) {
                SessionLogTable::create([
                    'userID'   => $userID,
                    'activity' => 'User Activated Room: ' . $room->roomnum,
                    'date'     => now(),
                ]);
            }
        });

        return redirect()->route('manager.room_list')->with('success', 'Room status updated successfully.');
    }
    // Update room status
    public function updateRoomStatus($roomID, $status)
    {
        $room = RoomTable::find($roomID);
        if (!$room) {
            Log::warning('Room not found for status update', ['roomID' => $roomID]);
            return redirect()->route('manager.room_list')->with('error', 'Room not found.');
        }

        $room->status = $status;
        $room->save();

        Log::info('Room status updated', ['roomID' => $roomID, 'status' => $status]);
        return redirect()->route('manager.room_list')->with('success', 'Room status updated successfully.');
    }
}
