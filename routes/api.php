<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\OtpController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PointsController;
use App\Http\Controllers\API\WalletController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ReviewsController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\SupportController;
use App\Http\Controllers\API\BookingsController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\PackagesController;
use App\Http\Controllers\API\SettingsController;
use App\Http\Controllers\API\WithdrawalController;
use App\Http\Controllers\API\NotificationController;

Route::get('/social-media', [SettingsController::class, 'social_media']);
Route::post('/check-email', [AuthController::class, 'check_email']);

Route::post('/reset-password', [AuthController::class, 'reset_password']);

Route::get('/guest-user', [ApiController::class, 'guest_user']);

Route::get('/home-sliders', [ApiController::class, 'sliders']);

Route::get('/service', [ApiController::class, 'service']);

Route::get("/property-types",[ApiController::class,"property_types"]);
Route::get('/categories', [ApiController::class, 'categories']);
Route::get('/max-min-price', [ApiController::class, 'max_min_price']);
Route::get('/get-service-information', [ServiceController::class, 'gallery_features_event_days']);
Route::get('/distance', [ApiController::class, 'distance']);
Route::get('/features', [ServiceController::class, 'features']);
Route::get("/get-prices-details", [BookingsController::class, 'get_price_of_days']);
Route::get('/service-form', [ServiceController::class, 'index']);



Route::get('/terms-policy', [SettingsController::class, 'terms_policy']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post("/send-otp", [OtpController::class, 'send_otp']);
Route::post("/verify-eamil", [OtpController::class, 'verify_otp']);
Route::post("/resend-otp", [OtpController::class, 'resend_otp']);



Route::group(['middleware' => ['custom-auth','lang_apis']], function () {
        Route::post('/support', [SupportController::class, 'store']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [ProfileController::class, 'profile']);


});
Route::group(['middleware' => ['custom-auth','lang_apis', 'checkIfBlocked']], function () {
    Broadcast::routes();
    Route::get('/home', [ApiController::class, 'home']);
    Route::get('/search', [ApiController::class, 'home']);
    
    Route::get("check-service-limit",[ServiceController::class,'check_service_limit']);
    // messages
    Route::get("/rooms", [MessageController::class, 'getAllRooms']);
    Route::get("/messages", [MessageController::class, 'messages']);
    Route::post("/send-message", [MessageController::class, 'StoreMessage']);
    Route::post("/delete-message", [MessageController::class, 'DeleteMessage']);
    Route::post("/update-message", [MessageController::class, 'UpdateMessage']);



    Route::get('/provider-packages', [PackagesController::class, 'provider_packages']);
    Route::get('/packages', [PackagesController::class, 'index']);
    Route::post('/subscribe-package', [PackagesController::class, 'subscribe']);





    Route::post('/change-user-language', [SettingsController::class, 'change_local_language']);

    Route::get('/settings', [SettingsController::class, 'index']);
    Route::get('/notifications', [NotificationController::class, 'notifications']);
    Route::get('/notifications-count', [NotificationController::class, 'num_un_seen_notification']);


    Route::post('/update-profile', [ProfileController::class, 'update_profile']);
    Route::post('/delete-image', [ProfileController::class, 'delete_image']);

    Route::get("/booking", [BookingsController::class, 'index']);
    Route::get("/booking/details", [BookingsController::class, 'show']);
    Route::get("/provider-services", [BookingsController::class, 'ProviderServices']);
    Route::post('/add-booking', [BookingsController::class, 'save']);
    Route::post('/pay-wallet-booking', [BookingsController::class, 'pay_by_wallet']);
    Route::post('/change-payment-status', [BookingsController::class, 'changePaymentStatus']);

    Route::post('/change-booking-status', [BookingsController::class, 'changeBookingStatus']);
    Route::post('/upload-booking-attachment', [BookingsController::class, 'upload_attachment_to_booking']);
    Route::post('/cancel-booking', [BookingsController::class, 'cancel_booking']);

    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post("/add-to-favorites", [FavoriteController::class, 'save']);
    Route::post("/remove-from-favorites", [FavoriteController::class, 'delete']);

    Route::post('/add-service', [ServiceController::class, 'store']);
    Route::post('/update-service', [ServiceController::class, 'update']);
    Route::get('/service-details', [ServiceController::class, 'service']);
    Route::post('/add-event-day', [ServiceController::class, 'add_event_day']);
    Route::post('/delete-event-day', [ServiceController::class, 'delete_event_day']);
    Route::post('/service/add-feature', [ServiceController::class, "add_feature"]);
    Route::post('/service/delete-feature', [ServiceController::class, "delete_feature"]);
    Route::post('/service/add-gallery', [ServiceController::class, "add_gallery"]);
    Route::post('/service/delete-gallery', [ServiceController::class, "delete_image_gallery"]);



    Route::get('/reviews', [ReviewsController::class, 'reviews']);
    Route::post('/add-review', [ReviewsController::class, 'add_review']);

    Route::post('set-location', [ApiController::class, 'setLocation']);

    Route::get('/payment-methods', [PaymentController::class, 'payment_methods']);

    Route::match(['get', 'post'], '/delete-user', [ProfileController::class, 'block_user']);


    Route::get('/wallet', [WithdrawalController::class, 'wallet']);
    Route::get('/withdrawal', [WithdrawalController::class, 'withdrawal']);
    Route::get('/withdraw-details', [WithdrawalController::class, 'withdraw_details']);
    Route::post('/sent-withdrawal', [WithdrawalController::class, 'sent_withdrawal']);
    
    
    Route::get('/transactions', [WalletController::class, 'index']);
    Route::get('/balance', [WalletController::class, 'balance']);
    Route::post('/add-to-wallet', [WalletController::class, 'add_wallet']);

    Route::get("/points", [PointsController::class, 'points']);
    Route::post('/convert-to-balance', [PointsController::class, 'convert_to_balance']);
});
