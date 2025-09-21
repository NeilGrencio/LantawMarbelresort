<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\RoomTable;

class ManageRoomController extends Controller
{
    // Display the list of rooms
    public function roomList()
    {
        // Fetch all rooms from the database
        $rooms = RoomTable::all();
        // Pass the rooms to the view

        return view('manager.room_list', compact('rooms'));
    }

    public function editRoom(Request $request, $roomID)
    {
        // Fetch the room details by roomID
        $room = RoomTable::where('roomID', $roomID)->first();

        // Pass the room details to the view
        if ($request->isMethod('get')){
            return view('manager.edit_room', compact('room'));
        }

        // Handle the form submission for editing the room
        if ($request->isMethod('post')){
            $validatedData = $request->validate([
                'roomnum' => 'required|numeric',
                'description' => 'required',
                'roomtype' => 'required',
                'status' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp',
                'price' => 'required|decimal:0,2'
            ]);

            // Check if any field has changed
            $hasChanges = false;
            if (
                $room->roomnum != $validatedData['roomnum'] ||
                $room->description != $validatedData['description'] ||
                $room->roomtype != $validatedData['roomtype'] ||
                $room->status != $validatedData['status'] ||
                $room->price != $validatedData['price'] ||
                $request->hasFile('image')
            ) {
                $hasChanges = true;
            }

            if (!$hasChanges) {
                return redirect()->route('manager.edit_room', ['roomID' => $roomID])->with('error', 'No changes detected.');
            }
            
            DB::beginTransaction();

            try{
                if ($request->hasFile('image')) {
                    $roomImagePath = $request->file('image')->store('rooms_images', 'public');
                    $room->image = $roomImagePath;
                }
                // Update Room
                $room->roomnum = $validatedData['roomnum'];
                $room->description = $validatedData['description'];
                $room->roomtype = $validatedData['roomtype'];
                $room->status = $validatedData['status'];
                $room->price = $validatedData['price'];
                
                $room->save();

                DB::commit();               
                return redirect('manager/room_list')->with('success', 'Room was updated successfully.');
            }   catch (\Exception $e) {

                DB::rollBack();
                return redirect()->route('manager.edit_room', ['roomID' => $roomID])->withInput()->with('error', 'Failed to update room: ' . $e->getMessage());
            }

        }

    }

    public function deactivateRoom($roomID){
        $room = RoomTable::find($roomID);
        if($room){
            $room->status = 'Unavailable';
            $room->save();
            return redirect('manager/room_list')->with('success', 'Room status updated successfully.');
        } else {
            return redirect('manager/room_list')->with('error', 'Room status update failed.'); 
        }

    }

    public function activateRoom($roomID){
        $room = RoomTable::find($roomID);
        if($room){
            $room->status = 'Available';
            $room->save();
            return redirect('manager/room_list')->with('success', 'Room status updated successfully.');
        } else {
            return redirect('manager/room_list')->with('error', 'Room status update failed.'); 
        }

    }

    public function maintenanceRoom($roomID){
        $room = RoomTable::find($roomID);
        if($room){
            $room->status = 'Maintenance';
            $room->save();
            return redirect('manager/room_list')->with('success', 'Room status updated successfully.');
        } else {
            return redirect('manager/room_list')->with('error', 'Room status update failed.'); 
        }

    }

    public function bookRoom($roomID){
        $room = RoomTable::find($roomID);
        if($room){
            $room->status = 'Booked';
            $room->save();
            return redirect('manager/room_list')->with('success', 'Room status updated successfully.');
        } else {
            return redirect('manager/room_list')->with('error', 'Room status update failed.'); 
        }

    }

    public function saveRoom(Request $request){
        $validatedData = $request->validate([
            'roomnum' => 'required|numeric',
            'description' => 'required',
            'roomcapacity'=> 'required|numeric|min:1',
            'roomtype' => 'required',
            'price' => 'required|numeric|decimal:0,2',
            'image' => 'required|image|mimes:jpg,jpeg,webp,png',
            'status' => 'required'
        ]);

        $roomImagePath = $request->file('image')->store('room_images', 'public');

        DB::beginTransaction();

        try{
            $room = new RoomTable();
            $room->roomnum = $validatedData['roomnum'];
            $room->description = $validatedData['description'];
            $room->price = $validatedData['price'];
            $room->roomcapacity = $validatedData['roomcapacity'];
            $room->roomtype = $validatedData['roomtype'];
            $room->status = $validatedData['status'];
            $room->image = $roomImagePath;
            $room->save();

            DB::commit();

            return redirect('manager/room_list')->with('success', 'Room was successfully added!');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->withInput()->with('error', 'Failed to create room!' . $e->getMessage());
        }
    }

    public function addRoom(){
        return view('manager/add_room');
    }

    
}
