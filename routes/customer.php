<?php

use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\API\Customer\UserAPIController;
use App\Http\Controllers\API\Customer\HomeController;
use App\Http\Controllers\API\Customer\ServiceController;
use App\Http\Controllers\API\Customer\BookingController;
use App\Http\Controllers\API\Customer\BrandController;
use App\Http\Controllers\API\Customer\CouponController as CustomerCouponController;
use App\Http\Controllers\Api\Customer\FavoriteController;
use App\Http\Controllers\API\Customer\PaymentController;
use App\Http\Controllers\API\Customer\ServiceConversationController;
use App\Http\Controllers\API\Customer\ServiceReviewsController;
use App\Http\Controllers\API\Customer\SupportConversationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::post('register',[UserAPIController::class,'register']);
Route::post('login',[UserAPIController::class,'login']);


Route::post('check-phone', [UserAPIController::class,'check_phone']);
Route::post('reset-password', [UserAPIController::class,'reset_password']);

Route::get('payment-methods', [PaymentController::class,'paymentMethods']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('logout',[UserAPIController::class,'logout']);
    Route::get('re-login',[UserAPIController::class,'re_login']);
    Route::post('update-token', [UserAPIController::class,'update_token']);

    Route::get('home', [HomeController::class,'index']);

    Route::get('search-info', [BrandController::class,'index']);

    Route::get('service', [ServiceController::class,'index']);
    Route::get('service/{id}/show', [ServiceController::class,'show']);
    Route::post('service-search', [ServiceController::class,'search']);
    Route::post('service-review', [ServiceReviewsController::class,'store']);
    Route::get('my-services', [BookingController::class,'myServices']);

    Route::get('booking', [BookingController::class,'index']);
    Route::post('booking', [BookingController::class,'store']);
    Route::post('booking-update/{booking}', [BookingController::class,'update']);
    Route::post('booking-delete/{booking}', [BookingController::class,'destroy']);
    Route::get('booking-notification', [BookingController::class,'booking_notification']);
    Route::get('booking/{id}/show', [BookingController::class,'show']);
    Route::post('create-payment', [PaymentController::class,'store']);

    Route::post('edit-profile', [UserAPIController::class,'edit_profile']);
    Route::post('change-password', [UserAPIController::class,'change_password']);

    Route::post('upload-license',[UserAPIController::class,'upload_license']);
    Route::get('service-license', [UserAPIController::class,'get_license']);

    Route::get('favorites',[FavoriteController::class,'index']);
    Route::get('add-remove-fav/{id}',[FavoriteController::class,'add']);

    Route::get('terms', [UserAPIController::class,'terms']);
    // Chat Customer - Admin
    Route::get('support-conversions', [SupportConversationController::class,'index']);
    Route::post('send-message-to-support', [SupportConversationController::class,'store']);
    // Chat Customer - Provider
    Route::get('service-conversions', [ServiceConversationController::class,'index']);
    Route::post('show-service-conversions', [ServiceConversationController::class,'show']);
    Route::post('send-message-to-provider', [ServiceConversationController::class,'store']);

    Route::post('check-coupon-code', [CustomerCouponController::class,'check_coupon']);

});
