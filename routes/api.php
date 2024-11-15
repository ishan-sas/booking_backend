<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\API\StoresController;
use App\Http\Controllers\API\BookingsController;
use App\Http\Controllers\API\TimeslotsController;
use App\Http\Controllers\API\StoreUsersController;
use App\Http\Controllers\API\StoreSchoolsController;
use App\Http\Controllers\API\BookingStatusController;
use App\Http\Controllers\API\StoreUnavailableDatesController;
use App\Http\Controllers\API\StoreUnavailableSlotsController;

Route::post('register', [AuthController::class, 'store']);
Route::post('login', [AuthController::class, 'authenticate']);

Route::get('get-stores', [StoresController::class, 'index']);
Route::get('stores/{slug}', [StoresController::class, 'show']);
Route::get('get-slots/{slug}/{day}/{noofchild}', [TimeslotsController::class, 'index']);
Route::get('get-unavailable-dates/{slug}/{day}', [StoreUnavailableDatesController::class, 'getUnavailableDates']);
Route::get('get-schools/{slug}', [StoreSchoolsController::class, 'index']);

Route::post('register-with-booking', [BookingsController::class, 'regWithBooking']);
Route::post('login-with-booking', [BookingsController::class, 'loginWithBooking']);

Route::get('download-pdf/{store_id}/{date}', [BookingsController::class, 'downloadPDF']);

Route::middleware(['auth:sanctum'])->group(function() {
    Route::get('/user', function () {
        return response()->json([
            'status' => 200,
            'message' => 'You are in'
        ], 200);
    });

    Route::get('get-time-label', [TimeslotsController::class, 'getTimeLabel']);    
    Route::get('get-customers', [AuthController::class, 'getCustomers']);
    Route::post('store-timeslots/{id}', [TimeslotsController::class, 'store']);
    Route::get('edit-timeslots/{id}', [TimeslotsController::class, 'edit']);
    Route::delete('remove-timeslot/{id}', [TimeslotsController::class, 'destroy']);
    Route::get('edit-account/{id}', [StoresController::class, 'edit']);
    Route::put('update-account/{id}', [StoresController::class, 'update']);
    Route::post('store-schools/{id}', [StoreSchoolsController::class, 'store']); 
    Route::get('edit-storeschools/{id}', [StoreSchoolsController::class, 'edit']);
    Route::delete('remove-schools/{id}', [StoreSchoolsController::class, 'destroy']);
    Route::post('store-unavailable-dates/{store}', [StoreUnavailableDatesController::class, 'store']);
    Route::get('edit-unavailable-dates/{id}', [StoreUnavailableDatesController::class, 'edit']);
    Route::delete('remove-unavaidates/{id}', [StoreUnavailableDatesController::class, 'destroy']);
    Route::get('get-unavailable-slots/{store}/{date}', [StoreUnavailableSlotsController::class, 'index']);
    Route::get('get-unavailable-date-slots/{store}/{date}', [StoreUnavailableSlotsController::class, 'getUnavailableSlots']);
    Route::post('store-unavailable-slots/{id}', [StoreUnavailableSlotsController::class, 'store']);  

    Route::post('store-register', [AuthController::class, 'storeRegister']);
    Route::get('get-all-stores', [StoresController::class, 'getAllStoreForAdmin']);
    Route::put('update-store-status/{id}', [StoresController::class, 'updateStoreStatus']);
    Route::get('get-storeid/{id}', [StoreUsersController::class, 'getStoreIdByUser']);

    Route::get('bookings-by-store/{slug}', [BookingsController::class, 'appointmentsByStore']);
    Route::get('bookings-by-date/{id}/{date}', [BookingsController::class, 'getBookingsByDate']);  
    Route::get('bookings-by-string/{id}/{date}', [BookingsController::class, 'bookingsByString']);

    Route::post('update-status', [BookingStatusController::class, 'store']);
    Route::get('get-status-summery/{id}', [BookingStatusController::class, 'show']);
    Route::get('get-booking-info/{id}', [BookingsController::class, 'show']);
    //edit-store-account

});


Route::middleware(['auth:sanctum'])->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('forget-password', [AuthController::class, 'submitForgetPasswordForm']);
Route::post('reset-password/{token}', [AuthController::class, 'submitPasswordReset']);


{/* Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post'); 
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post'); */}

// Route::get('/forgot-password', function () {
//     return view('auth.forgot-password');
// })->middleware('guest')->name('password.request');