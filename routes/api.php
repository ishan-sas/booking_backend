<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\StoresController;
use App\Http\Controllers\API\BookingsController;
use App\Http\Controllers\API\TimeslotsController;
use App\Http\Controllers\api\StoreUsersController;
use App\Http\Controllers\api\StoreSchoolsController;
use App\Http\Controllers\api\BookingStatusController;

Route::post('register', [AuthController::class, 'store']);
Route::post('login', [AuthController::class, 'authenticate']);

Route::get('get-stores', [StoresController::class, 'index']);
Route::get('stores/{slug}', [StoresController::class, 'show']);
Route::get('get-slots/{slug}/{day}/{noofchild}', [TimeslotsController::class, 'index']);
Route::get('get-schools/{slug}', [StoreSchoolsController::class, 'index']);

Route::post('register-with-booking', [BookingsController::class, 'regWithBooking']);
Route::post('login-with-booking', [BookingsController::class, 'loginWithBooking']);

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
    Route::get('edit-account/{id}', [StoresController::class, 'edit']);
    Route::put('update-account/{id}', [StoresController::class, 'update']);
    Route::post('store-schools/{id}', [StoreSchoolsController::class, 'store']); 
    Route::get('edit-storeschools/{id}', [StoreSchoolsController::class, 'edit']);

    Route::post('store-register', [AuthController::class, 'storeRegister']);
    Route::get('get-storeid/{id}', [StoreUsersController::class, 'getStoreIdByUser']);

    Route::get('bookings-by-store/{slug}', [BookingsController::class, 'appointmentsByStore']);

    Route::post('update-status', [BookingStatusController::class, 'store']);
    Route::get('get-status-summery/{id}', [BookingStatusController::class, 'show']);

});


Route::middleware(['auth:sanctum'])->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
});