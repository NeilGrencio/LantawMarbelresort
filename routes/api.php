<?php

use App\Http\Controllers\Api\AmenityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\RoomMobile;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CottageController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ChatController;
use App\Models\User;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\QRCodeController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/notifications/{id}/read', function ($id) {
    $notification = DB::table('notifications')->where('id', $id)->first();

    if (!$notification) {
        return response()->json(['error' => 'Notification not found'], 404);
    }

    DB::table('notifications')
        ->where('id', $id)
        ->update(['read_at' => now()]);

    return response()->json(['success' => true]);
});
Route::get('/notifications/{user_id}', function ($user_id) {
    $user = User::find($user_id);

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $notifications = $user->notifications()
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->data['title'] ?? '',
                'body' => $notification->data['body'] ?? '',
                'extra' => $notification->data['extra'] ?? [],
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at->toDateTimeString(),
            ];
        });

    return response()->json($notifications);
});
// Mobile API routes
Route::get('/rooms', [RoomMobile::class, 'roomList']);
Route::get('/rooms/available', [RoomMobile::class, 'availableRoomsByDate']);
Route::get('/amenities', [AmenityController::class, 'index']);
Route::get('/menus', [MenuController::class, 'index']);
Route::get('/cottages', [CottageController::class, 'index']);
Route::get('/cottages/available', [CottageController::class, 'availableCottagesByDate']);
Route::post('/signup', [ApiAuthController::class, 'signup']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/gcash/create-payment', [PaymentController::class, 'createGcashPayment']);
Route::post('/webhook/paymongo', [PaymentController::class, 'handleWebhook']);
Route::prefix('bookings')->group(function () {
    Route::get('/guest/{guestID}', [BookingController::class, 'getByGuest']);
    Route::get('/{id}', [BookingController::class, 'show']);
    Route::post('/', [BookingController::class, 'store']);
    Route::put('/{id}', [BookingController::class, 'update']);
});
Route::apiResource('chats', ChatController::class);
// routes/api.php
Route::post('/save-fcm-token', [ApiAuthController::class, 'saveFcmToken']);

Route::get('chats/guest/{guestID}', [ChatController::class, 'getByGuest']);
Route::post('/feedback', [FeedbackController::class, 'store']); // Add feedback
Route::get('/feedback/{guestID}', [FeedbackController::class, 'index']);
use App\Http\Controllers\QRController;

// Get all QR codes for a specific guest
Route::get('/qrcodes/{guestID}', [QRCodeController::class, 'index']);

// Get a single QR code by ID
Route::get('/qrcode/{id}', [QRCodeController::class, 'show']);
Route::get('/qrcodeByGuest/{id}', [QRCodeController::class, 'showbyGuest']);
// Create a new QR code
Route::post('/qrcode', [QRCodeController::class, 'store']);
Route::get('/bookingsForEdit/{id}', [BookingController::class, 'showForEdit']);
// (Optional) Delete a QR code
Route::delete('/qrcode/{id}', [QRCodeController::class, 'destroy']);
