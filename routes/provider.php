<?php

use App\Http\Controllers\API\Provider\BookingController;
use App\Http\Controllers\API\Provider\ProviderAPIController;
use App\Http\Controllers\API\Provider\HomeController;
use App\Http\Controllers\API\Provider\ServiceController;
use App\Http\Controllers\API\Provider\ServiceConversationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::post('register',[ProviderAPIController::class,'register']);
Route::post('login',[ProviderAPIController::class,'login']);

Route::post('check-phone', [ProviderAPIController::class,'check_phone']);
Route::post('reset-password', [ProviderAPIController::class,'reset_password']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('logout',[ProviderAPIController::class,'logout']);
    Route::post('update-token', [ProviderAPIController::class,'update_token']);

    Route::get('home', [HomeController::class,'index']);
    Route::resource('services', ServiceController::class);
    Route::get('service/{id}/show', [ServiceController::class,'show']);
    Route::post('search', [ServiceController::class,'search']);
    Route::get('edit', [ServiceController::class,'edit']);
    Route::post('update-service', [ServiceController::class,'update']);
    Route::post('delete-service', [ServiceController::class,'destroy']);
    Route::post('edit_profile',[ProviderAPIController::class,'edit_profile']);

    Route::post('upload-license',[ProviderAPIController::class,'upload_license']);
    Route::get('driver-license', [ProviderAPIController::class,'get_license']);
    Route::get('booking/{id}/show', [BookingController::class,'show']);
    Route::get('booking', [BookingController::class,'index']);
    Route::get('booking-notification', [BookingController::class,'booking_notification']);

    Route::get('booking/booking-status', [BookingController::class,'booking_status']);

    Route::post('booking/change-status', [BookingController::class,'change_status']);
    // Chat Customer - Provider
    Route::get('service-conversions', [ServiceConversationController::class,'index']);
    Route::post('show-service-conversions', [ServiceConversationController::class,'show']);
    Route::post('send-message-to-customer', [ServiceConversationController::class,'store']);
});
