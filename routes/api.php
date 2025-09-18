<?php

use App\Http\Controllers\Api\AmenityController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\RoomMobile;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CottageController;
use App\Http\Controllers\Api\PaymentController;

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

// Mobile API routes
Route::get('/rooms', [RoomMobile::class, 'roomList']);
Route::get('/amenities', [AmenityController::class, 'index']);
Route::get('/menus', [MenuController::class, 'index']);
Route::get('/cottages', [CottageController::class, 'index']);
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
