<?php

use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\SearchUserController;
use App\Http\Controllers\ManageRoomController;
use App\Http\Controllers\ManageAmenityController;
use App\Http\Controllers\ManageGuestController;
use App\Http\Controllers\ManageCottageController;
use App\Http\Controllers\ManageMenuController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\SessionLogController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\RoomMobile;
use App\Http\Controllers\DayTourController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\Api\AmenityController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FeedbackController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingpage');
})->name('landingpage');

Route::get('/room-image/{filename}', function ($filename) {
    $path = storage_path('app/public/room_images/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    $mimeType = mime_content_type($path);
    return response()->file($path, [
        'Content-Type' => $mimeType
    ]);
})->name('room.image');

Route::get('/menu-image/{filename}', function ($filename) {
    $path = storage_path('app/public/menu_images/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    $mimeType = mime_content_type($path);
    return response()->file($path, [
        'Content-Type' => $mimeType
    ]);
})->name('menu.image');
Route::get('/cottage-image/{filename}', function ($filename) {
    $path = storage_path('app/public/cottage_image/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    $mimeType = mime_content_type($path);
    return response()->file($path, [
        'Content-Type' => $mimeType
    ]);
})->name('cottage.image');
Route::get('/amenity-image/{filename}', function ($filename) {
    $path = storage_path('app/public/amenity_images/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    $mimeType = mime_content_type($path);
    return response()->file($path, [
        'Content-Type' => $mimeType
    ]);
})->name('amenity.image');
Route::get('/guest-image/{filename}', function ($filename) {
    $path = storage_path('app/public/guest_images/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    $mimeType = mime_content_type($path);
    return response()->file($path, [
        'Content-Type' => $mimeType
    ]);
})->name('guest.image');
Route::get('/staff-image/{filename}', function ($filename) {
    $path = storage_path('app/public/staff_images/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    $mimeType = mime_content_type($path);
    return response()->file($path, [
        'Content-Type' => $mimeType
    ]);
})->name('staff.image');
Route::get('/guestid-image/{filename}', function ($filename) {
    $path = storage_path('app/public/guestid_images/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    $mimeType = mime_content_type($path);
    return response()->file($path, [
        'Content-Type' => $mimeType
    ]);
})->name('guestid.image');


Route::match(['get', 'post'], 'auth/login', [LoginController::class, 'login'])->name('login');
Route::post('auth/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('auth/send_OTP', [LoginController::class, 'sendOTP']);
Route::post('auth/forgot_password', [LoginController::class, 'verifyOTP']);
Route::post('auth/reset_password', [LoginController::class, 'resetPassword']);

Route::get('manager/dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');

// call to send otp
Route::post('/send-otp', [ManageUserController::class, 'validateNumber'])->name('send.otp');


// User management list
Route::get('manager/manage_user', [ManageUserController::class, 'userList'])->name('manager.manage_user');

//Search user
Route::get('manager/search_user', [SearchUserController::class, 'search'])->name('manager.search_user');
Route::get('manager/search_guest', [SearchUserController::class, 'searchGuest'])->name('manager.search_guest');
Route::get('manager/search_discount', [SearchUserController::class, 'searchDiscount'])->name('manager.search_discount');
Route::get('manager/search_room', [SearchUserController::class, 'searchRoom'])->name('manager.search_room');
Route::get('manager/search_amenity', [SearchUserController::class, 'searchAmenity'])->name('manager.search_amenity');
Route::get('manager/search_cottage', [SearchUserController::class, 'searchCottage'])->name('manager.search_cottage');
Route::get('manager/search_menu', [SearchUserController::class, 'searchMenu'])->name('manager.search_menu');
Route::get('manager/search_logs', [SearchUserController::class, 'searchLogs'])->name('manager.search_logs');
Route::get('manager/search_feedback', [SearchUserController::class, 'searchFeedback'])->name('manager.search_feedback');

// Display add user form
Route::get('manager/add_user', [ManageUserController::class, 'showForm'])->name('manager.add_user.form');

// Final user submission
Route::post('manager/add_user', [ManageUserController::class, 'addUser'])->name('manager.add_user.submit');

// Edit User
Route::match(['get', 'post'], 'manager/edit_user/{userID}', [ManageUserController::class, 'editUser'])->name('manager.edit_user');

// Send OTP to mobile Number
Route::post('manaager/send_otp', [ManageUserController::class, 'validateNumber'])->name('send_otp');

// Deactivate User
Route::match(['get', 'post'], 'manager/deactivate_user/{userID}', [ManageUserController::class, 'deactivateUser'])->name('manager.deactivate_user');

// Activate User
Route::match(['get', 'post'], 'manager/activate_user/{userID}', [ManageUserController::class, 'activateUser'])->name('manager.activate_user');

// Display Guest List
Route::get('manager/guest_list', [ManageGuestController::class, 'guestList'])->name('manager.guest_list');

// Display Add User Form
Route::get('manager/add_guest', [ManageGuestController::class, 'addGuest'])->name('manager.add_guest');

// Add a Room
Route::post('manager/save_room', [ManageRoomController::class, 'saveRoom'])->name('manager.save_room');

// Display Form
Route::get('manager/add_room', [ManageRoomController::class, 'addRoom'])->name('manager.add_room');

// Room List
Route::get('manager/room_list', [ManageRoomController::class, 'roomList'])->name('manager.room_list');

// Edit Room
Route::match(['get', 'post'], 'manager/edit_room/{roomID}', [ManageRoomController::class, 'editRoom'])->name('manager.edit_room');

// Deaactive Room
Route::match(['get', 'post'], 'manager/deactivate_room/{roomID}', [ManageRoomController::class, 'deactivateRoom'])->name('manager.deactivate_room');

// Activate Room
Route::match(['get', 'post'], 'manager/activate_room/{roomID}', [ManageRoomController::class, 'activateRoom'])->name('manager.activate_room');

// Maintenance Room
Route::match(['get', 'post'], 'manager/maintenance_room/{roomID}', [ManageRoomController::class, 'maintenanceRoom'])->name('manager.maintenance_room');

// Book Room
Route::match(['get', 'post'], 'manager/book_room/{roomID}', [ManageRoomController::class, 'bookRoom'])->name('manager.book_room');

// Amenity List
Route::get('manager/amenity_list', [ManageAmenityController::class, 'amenityList'])->name('manager.amenity_list');

// Display Form
Route::get('manager/add_amenity', [ManageAmenityController::class, 'addAmenity'])->name('manager.add_amenity');

// Add Amenity
Route::post('manager/add_amenity', [ManageAmenityController::class, 'saveAmenity'])->name('manager.add_amenity.submit');
Route::match(['get', 'post'], 'manager/activate_amenity/{amenityID}', [ManageAmenityController::class, 'activateAmenity'])->name('manager.activate_amenity');
Route::match(['get', 'post'], 'manager/deactivate_amenity/{amenityID}', [ManageAmenityController::class, 'deactivateAmenity'])->name('manager.deactivate_amenity');
Route::match(['get', 'post'], 'manager/maintenance_amenity/{amenityID}', [ManageAmenityController::class, 'maintenanceAmenity'])->name('manager.maintenance_amenity');
Route::match(['get', 'post'], 'manager/book_amenity/{amenityID}', [ManageAmenityController::class, 'bookAmenity'])->name('manager.book_amenity');

// Edit Amenity
Route::match(['get', 'post'], 'manager/edit_amenity/{amenityID}', [ManageAmenityController::class, 'editAmenity'])->name('manager.edit_amenity');

//View Guest
Route::get('manager/view_guest/{guestID}', [ManageGuestController::class, 'viewGuest'])->name('manager.view_guest');

// Cottage List
Route::get('manager/cottage_list', [ManageCottageController::class, 'cottageList'])->name('manager.cottage_list');

// Show Cottage Form
Route::get('manager/add_cottages', [ManageCottageController::class, 'addCottage'])->name('manager.add_cottage');

// Submit Cottage Form
Route::post('manager/add_cottages', [ManageCottageController::class, 'submitCottage'])->name('manager.submit_cottage');

// Update Cottage
Route::match(['get', 'post'], 'manager/edit_cottage/{cottageID}', [ManageCottageController::class, 'editCottage'])->name('manager.edit_cottage');

// Deactivate Cottage
Route::match(['get', 'post'], 'manager/deactivate_cottage/{cottageID}', [ManageCottageController::class, 'deactivateCottage'])->name('manager.deactivate_cottage');

// Activate Cottage
Route::match(['get', 'post'], 'manager/activate_cottage/{cottageID}', [ManageCottageController::class, 'activateCottage'])->name('manager.activate_cottage');

// Maintenance Cottage
Route::match(['get', 'post'], 'manager/maintenance_cottage/{cottageID}', [ManageCottageController::class, 'maintenanceCottage'])->name('manager.mainenance_cottage');

// Menu List
Route::get('manager/menu_list', [ManageMenuController::class, 'menuList'])->name('manager.menu_list');

// Show add menu form
Route::get('manager/add_menu', [ManageMenuController::class, 'addMenu'])->name('manager.add_menu_form');

// Submit menu form
Route::post('manager/add_menu', [ManageMenuController::class, 'submitMenu'])->name('manager.submit_menu');

// Update menu
Route::match(['get', 'post'], 'manager/edit_menu/{menuID}', [ManageMenuController::class, 'editMenu'])->name('manager.edit_menu');

// Activate menu
Route::match(['get', 'post'], 'manager/activate_menu/{menuID}', [ManageMenuController::class, 'activateMenu'])->name('manager/activate_menu');

// Deactivate menu
Route::match(['get', 'post'], 'manager/deactivate_menu/{menuID}', [ManageMenuController::class, 'deactivateMenu'])->name('manager/deactivate_menu');

Route::get('manager/feedback', [FeedbackController::class, 'viewFeedback'])->name('manager.feedback');

// View Chats
Route::get( 'manager/chat', [ChatController::class, 'viewChats'])->name('manager.chat_logs');

// Send Reply
Route::post( 'manager/chat', [ChatController::class, 'sendChat'])->name('manager.send_reply');

// View Discounts
Route::get('manager/discount', [DiscountController::class, 'viewDiscounts'])->name('manager.view_discounts');

// Deactivate Discounts
Route::match(['get', 'post'], 'manager/deactivate_discount/{discountID}', [DiscountController::class, 'deactivateDiscount'])->name('manager.deactivate_discount');

// Activate Discounts
Route::match(['get', 'post'], 'manager/activate_discount/{discountID}', [DiscountController::class, 'activateDiscount'])->name('manager.activate_discounts');

// Add Discount
Route::match(['post', 'get'], 'manager/add_discount', [DiscountController::class, 'addDiscount'])->name('manager.add_discount');

// Update Discount
Route::match(['post', 'get'], 'manager/edit_discount/{discountID}', [DiscountController::class, 'updateDiscount'])->name('manager.update_discount');

// View Session Logs
Route::get('manager/session_logs', [SessionLogController::class, 'viewSessions'])->name('manager.view_sessions');
// Export PDF
Route::get('manager/session_logs/export_pdf', [SessionLogController::class, 'exportPDF'])->name('manager.session_logs_pdf');

// View Reports Dashboard
Route::get('manager/report', [ReportController::class, 'viewReport'])->name('manager.report_dashboard');

// Booking Report
Route::get('manager/booking_report', [ReportController::class, 'bookingReport'])->name('manager.booking_report');

// Check Report
Route::get('manager/check_report', [ReportController::class, 'checkReport'])->name('manager.check_report');

// Revenue Report
Route::get('manager/revenue_report', [ReportController::class, 'revenueReport'])->name('manager.revenue_report');

// Guest Report
Route::get('manager/guest_report', [ReportController::class, 'guestReport'])->name('manager.guest_report');

// Export pdf
Route::get('manager/export_pdf', [ReportController::class, 'exportPDF'])->name('report.exportPDF');
Route::get('manager/export_checkpdf', [ReportController::class, 'exportCheckPDF'])->name('report.exportPDF');
Route::get('manager/export_guestpdf', [ReportController::class, 'exportGuestPDF'])->name('report.exportPDF');
Route::get('manager/export_revenuepdf', [ReportController::class, 'exportRevenuePDF'])->name('report.exportPDF');

Route::get('manager/services_list', [ManageMenuController::class, 'serviceList']);
Route::match(['get', 'post'], 'manager/add_service', [ManageMenuController::class, 'addService']);
Route::match(['get', 'post'], 'manager/edit_service/{menuID}', [ManageMenuController::class, 'editService'])->name('manager.edit_service');

// Receptionist
Route::get('receptionist/dashboard', [DashboardController::class, 'receptionistDashboard'])->name('receptionist.dashboard');


/// Search Function
Route::get('receptionist/search_booking', [SearchUserController::class, 'searchBooking'])->name('receptionist.search_booking');
Route::get('receptionist/search_daytour', [SearchUserController::class, 'searchDaytour'])->name('receptionist.search_daytour');
Route::get('receptionist/search_guest', [SearchUserController::class, 'searchGuestReceptionist'])->name('receptionist.search_guest');
Route::get('receptionist/search_billing', [SearchUserController::class, 'searchBilling'])->name('receptionist.search_billing');
Route::get('receptionist/search_menu', [SearchUserController::class, 'searchMenuReceptionist'])->name('receptionist.search_menu');


// Booking Routes

Route::get('receptionist/booking', [BookingController::class, 'bookingList'])->name('receptionist.booking');
Route::get('receptionist/booking_list', [BookingController::class, 'bookingListView'])->name('receptionist.booking_list');
Route::get('receptionist/create_booking', [BookingController::class, 'createBooking'])->name('receptionist.create_booking');

Route::post('receptionist/submit_booking', [BookingController::class, 'submitBooking'])->name('receptionist.submit_booking');

Route::get('receptionist/receipt_booking/{sessionID}', [BookingController::class, 'receiptBooking'])->name('receptionist.booking_receipt');
Route::get('receptionist/view_booking/{bookingID}', [BookingController::class, 'viewBooking'])->name('receptionist.view_booking');

Route::match(['post', 'get'], 'receptionist/update_booking/{bookingID}', [BookingController::class, 'updateBooking'])->name('receptionist.update_booking');
Route::post('receptionist/approve_booking/{bookingID}', [BookingController::class, 'approveBooking'])
    ->name('receptionist.approve_booking');

Route::post('receptionist/decline_booking/{bookingID}', [BookingController::class, 'declineBooking'])
    ->name('receptionist.decline_booking');

Route::post('receptionist/cancel_booking/{bookingID}', [BookingController::class, 'cancelBooking'])->name('receptionist.cancel_booking');
Route::post('receptionist/confirm_booking/{bookingID}', [BookingController::class, 'confirmBooking'])->name('receptionist.confirm_booking');

Route::match(['post', 'get'], 'receptionist/walk-booking', [BookingController::class, 'walkinBooking'])->name('receptionist.walkin_booking');
Route::get('receptionist/chat', [ChatController::class, 'viewChatsReceptionist'])
    ->name('receptionist.chat_logs');

// Send new chat (POST)
Route::post('receptionist/chat', [ChatController::class, 'sendChat'])
    ->name('receptionist.chat_send');
// Route::get('receptionist/chat', [ChatController::class, 'viewChats'])->name('receptionist.chat_logs');
// Fill calendar data
Route::get('receptionist/events', [BookingController::class, 'events'])->name('receptionist.events');
Route::get('receptionist/checkEvents', [BookingController::class, 'checkEvents'])->name('receptionist.checkEvents');

Route::get('receptionist/daytourDashboard', [DayTourController::class, 'daytourDashboard'])->name('receptionist.daytour_dashboard');

Route::get('receptionist/daytour', [DayTourController::class, 'viewDayTour'])->name('receptionist.daytour');

Route::post('receptionist/daytour', [DayTourController::class, 'createDayTour'])->name('receptionist.createDayTour');

Route::get('receptionist/check-in-out', [BookingController::class, 'viewCheckIn'])->name('receptionist.view_check');

Route::get('receptionist/bill/{bookingID}/{type}', [BookingController::class, 'getBillDetails']);

Route::match(['POST', 'GET'], 'receptionist/checkin/{bookingID}', [BookingController::class, 'checkInBooking'])->name('receptionist.checkin');

Route::match(['POST', 'GET'], 'receptionist/checkout/{bookingID}', [BookingController::class, 'checkOutBooking'])->name('receptionist.checkout');
Route::get('receptionist/billing', [BillingController::class, 'billingList'])->name('receptionist.billing_list');
Route::get('receptionist/sendsmtp', [ManageUserController::class, 'send']);

Route::get('receptionist/guest_list', [ManageGuestController::class, 'guestListReceptionist'])->name('receptionist.guest_list');
Route::get('receptionist/view_guest/{guestID}', [ManageGuestController::class, 'viewGuestReceptionist'])->name('receptionist.view_guest');
Route::get('receptionist/add_guest', [ManageGuestController::class, 'addGuestReceptionist'])->name('receptionist.add_guest');
Route::post('receptionist/add_guest', [ManageGuestController::class, 'submitGuest'])->name('receptionist.submit_guest');

Route::get('receptionist/order', [OrderController::class, 'viewMenu'])->name('receptionist.order');
Route::post('receptionist/order', [OrderController::class, 'submitOrder'])->name('receptionist.submitorder');

//Kitchen
Route::get('kitchen/dashboard', [DashboardController::class, 'kitchenDashboard'])->name('kitchen.dashboard');
Route::post('/orders/{order}/prepare', [OrderController::class, 'prepareOrder'])->name('orders.prepare');
Route::get('receptionist/edit_booking/{bookingID}', [BookingController::class, 'edit'])->name('booking.edit');

// Update booking
Route::post('receptionist/update_booking/{bookingID}', [BookingController::class, 'update'])->name('booking.update');

//For Mobile
// Route::get('mobile/rooms', [RoomMobile::class, 'roomList']);

use App\Http\Controllers\Api\ApiAuthController;

// Route::post('mobile/signup', [ApiAuthController::class, 'signup']);
// Route::post('mobile/login', [ApiAuthController::class, 'login']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('mobile/logout', [ApiAuthController::class, 'logout']);
// });

require __DIR__ . '/auth.php';
