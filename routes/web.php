<?php

use Carbon\Carbon;
use App\Models\FCM;
use App\CPU\Helpers;
use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use App\Events\ChatEvent;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Events\testWebsockets;
use Google\Client as GoogleClient;
use App\Events\TestReverbEvent;
use App\Models\ServiceEventDays;
use App\Events\PublicMessageSent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Notifications\PushNotification;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\Admin\LogsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\AssignController;
use App\Http\Controllers\Admin\CitiesController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\EarningController;
use App\Http\Controllers\Admin\ExportsController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\ReviewsController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\testWebsocketController;
use App\Http\Controllers\Admin\FeaturesController;
use App\Http\Controllers\Admin\PackagesController;
use App\Http\Controllers\Admin\ReminderController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\TransactionsController;
use App\Http\Controllers\Admin\PaymentMethodsController;
use App\Http\Controllers\Admin\WithdrawRequestsController;

Route::get('/test-cache',function(){
    return Cache::get('dashboard_data');
});
Route::middleware(['guest'])->group(function(){
    
    Route::view('/login','auth.login')->name('login');
    Route::view('/','auth.login');
    Route::post('/login',[AuthController::class,'login']);
    Route::get('/forgot-password',[AuthController::class,'forget_password'])->name('forget_password');
    Route::post('/send-otp',[AuthController::class,'send_otp'])->name('send_otp');
    Route::post('/verify-otp', [AuthController::class, 'verify_otp'])->name('verify_otp');
    Route::post('/reset-password', [AuthController::class, 'reset_password'])->name('reset_password');
});


Route::prefix('admin')->middleware(['lang','auth'])->name('admin.')->group(function(){
    Route::get('change-lang/{locale}',[LangController::class,'Localization'])->name('change.lang');
    
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');

    Route::get('/',[DashboardController::class,'index'])->name('dashboard');
    Route::get('/categories',[CategoriesController::class,'index'])->middleware('permission:show category')->name('categories');
    Route::post('/add-category',[CategoriesController::class,'store'])->middleware('permission:add category')->name('add_category');
    Route::post('/delete-category',[CategoriesController::class,'destroy'])->middleware('permission:delete category')->name('delete_category');
    Route::post('/update-category',[CategoriesController::class,'update'])->middleware('permission:edit category')->name('update_category');

    Route::get('/cities',[CitiesController::class,'index'])->name('cities');
    Route::post('/add-city',[CitiesController::class,'store'])->name('add_city');
    Route::post('/delete-city',[CitiesController::class,'destroy'])->name('delete_city');
    Route::post('/update-city',[CitiesController::class,'update'])->name('update_city');

    Route::get('/commissions',[CommissionController::class,'index'])->name('commissions');
    Route::post('/add-commission',[CommissionController::class,'store'])->name('add_commission');
    Route::post('/delete-commission',[CommissionController::class,'destroy'])->name('delete_commission');
    Route::post('/update-commission',[CommissionController::class,'update'])->name('update_commission');

    Route::get('/features',[FeaturesController::class,'index'])->middleware('permission:show feature')->name('features');
    Route::post('/add-feature',[FeaturesController::class,'store'])->middleware('permission:add feature')->name('add_feature');
    Route::post('/delete-feature',[FeaturesController::class,'destroy'])->middleware('permission:delete feature')->name('delete_feature');
    Route::post('/update-feature',[FeaturesController::class,'update'])->middleware('permission:edit feature')->name('update_feature');
    
    Route::get('/packages',[PackagesController::class,'index'])->name('packages');
    Route::post('/add-package',[PackagesController::class,'store'])->name('add_package');
    Route::post('/delete-package',[PackagesController::class,'destroy'])->name('delete_package');
    Route::post('/update-package',[PackagesController::class,'update'])->name('update_package');

    Route::get('/packages/features',[PackagesController::class,'features'])->name('packages.features');
    Route::post('/packages/add-feature',[PackagesController::class,'add_feature'])->name('packages.add.feature');
    Route::post('/packages/update-feature',[PackagesController::class,'update_feature'])->name('packages.update.feature');
    Route::post('/packages/delete-feature',[PackagesController::class,'delete_feature'])->name('packages.delete.feature');

    Route::get('/packages/subscribers',[PackagesController::class,'subscribers'])->name('packages.subscribers');
    Route::post('/packages/change-subscribe-packgage-status',[PackagesController::class,'change_subscriber_status'])->name('packages.subscribers.status');
    Route::get('/subscribers/export', [PackagesController::class, 'export'])->name('subscribers.export');

    Route::resource('services',"App\Http\Controllers\Admin\ServicesController");
    Route::post('/service/event-days',[ServicesController::class,"add_event"])->name('service.event_day');
    Route::post('/service/delete/event-day',[ServicesController::class,"delete_event"])->name('service.delete_event_day');

    Route::post('/service/add-feature',[ServicesController::class,"add_feature"])->name('service.add_feature');
    Route::post('/service/delete-feature',[ServicesController::class,"delete_feature"])->name('service.delete_feature');
    Route::get('/service/change-available-status/{service_id}',[ServicesController::class,'change_available'])->name('service.change_available_status');
    Route::get('/service/change-accept-status/{service_id}',[ServicesController::class,'change_accept'])->name('service.change_accept_status');
    Route::get('/service/disabled-service/{service_id}',[ServicesController::class,'disabled_service'])->name('service.disabled_service');
    Route::get('/service/best-deal/{service_id}',[ServicesController::class,'best_deal'])->name('service.best_deal');
    Route::get('/service/delete-gellery-image/{id}',[ServicesController::class,"delete_gallery_image"])->name('service.delete_gallery_image');
    Route::get("/reviews",[ReviewsController::class,"index"])->name("reviews");
    Route::post("/delete/review",[ReviewsController::class,"delete"])->name("reviews.delete");

    Route::get('/booking',[BookingController::class,'index'])->middleware('permission:show booking')->name('booking');
    Route::get('/booking/details/{id}',[BookingController::class,'show'])->middleware('permission:show booking')->name('booking.show');
    Route::post('/change-booking-status',[BookingController::class,'ChangeBookingStatus'])->middleware('permission:change booking status')->name('booking.change_booking_status');
    Route::post('/change-payment-status',[BookingController::class,'ChangePaymentStatus'])->middleware('permission:change payment status')->name('booking.change_payment_status');
    Route::post('/confrim-cancellation',[BookingController::class,'confrim_cancellation'])->middleware('permission:change payment status')->name('booking.confrim_cancellation');
    Route::post('/add-booking',[BookingController::class,'save'])->name('booking.store');

    Route::get('/coupons',[CouponsController::class,'index'])->middleware('permission:show coupon')->name('coupons');
    Route::post('/add-coupon',[CouponsController::class,'store'])->middleware('permission:add coupon')->name('add_coupon');
    Route::post('/delete-coupon',[CouponsController::class,'destroy'])->middleware('permission:delete coupon')->name('delete_coupon');
    Route::post('/update-coupon',[CouponsController::class,'update'])->middleware('permission:edit coupon')->name('update_coupon');



    Route::get('/users',[UsersController::class,'index'])->name('users');
    Route::post('/add-user',[UsersController::class,'store'])->middleware('permission:add new user')->name('add_user');
    Route::post('/delete-user',[UsersController::class,'destroy'])->name('delete_user');
    Route::post('/update-user',[UsersController::class,'update'])->name('update_user');
    Route::post('/notification-reed',[UsersController::class,'make_notification_asread'])->name('notifications.markAsSeen');

    Route::get("/assing-users",[AssignController::class,'index'])->name("assign_users");
    Route::post("/assign-employee-to-provider",[AssignController::class,'store'])->name("assign_employee_to_provider");
    Route::post("/delete-assign",[AssignController::class,'delete'])->name("delete_assign");

    // Route::get('/payment-list',[PaymentController::class,'list'])->middleware('permission:show payments list')->name('payment_list');

    Route::get('/change-payment-methods-status/{id}',[PaymentMethodsController::class,'change_status'])->name('change_payment_method_status');


    Route::get('/payment-methods',[PaymentMethodsController::class,'index'])->middleware('permission:show payment method')->name('payment_methods');
    Route::post('/add-method',[PaymentMethodsController::class,'store'])->middleware('permission:add payment method')->name('add_method');
    Route::post('/delete-method',[PaymentMethodsController::class,'destroy'])->middleware('permission:delete payment method')->name('delete_method');
    Route::post('/update-method',[PaymentMethodsController::class,'update'])->middleware('permission:edit payment method')->name('update_method');




    Route::get('/earning',[EarningController::class,'index'])->middleware('permission:show earning')->name('earning');

    Route::get('/profile/{id}',[ProfileController::class,'index'])->name('profile');
    Route::get('/edit/profile/{id}',[ProfileController::class,'edit'])->name('profile.edit');
    Route::post('/block-user/{user_id}',[ProfileController::class,'block_user'])->name('block_user');
    Route::post('/update-overview-time/{user_id}',[ProfileController::class,'update_overview_time'])->name('update_overview_time');
    Route::post('/profile/update/{user_id}',[ProfileController::class,'update_profile'])->name('profile.update');
    Route::post('/profile/add-commission-to-provider/{user_id}',[ProfileController::class,'add_commissin_to_provider'])->name('profile.add_commissin_to_provider');
    Route::post('/profile/withdraw/{user_id}',[WithdrawRequestsController::class,'withdraw'])->name('profile.withdraw');
    Route::post('/profile/deposit{user_id}',[WithdrawRequestsController::class,'deposit'])->name('profile.deposit');
    Route::post('/profile/add-package',[ProfileController::class,'add_packge_to_provider'])->name("profile.add_package");
    Route::post('/profile/add-balance',[ProfileController::class,'add_balance'])->name("profile.add_balance");
    
    Route::get('reports/payment-reports',[ReportsController::class,'payment_reports'])->middleware('permission:show reports')->name('payment_reports');
    Route::get('reports/view/{invoice_id}',[ReportsController::class,'show'])->middleware('permission:show reports')->name('payment_reports.show');
    Route::get('reports/download/{invoice_id}',[ReportsController::class,'generate_pdf'])->name('payment_reports.download_pdf');
    Route::get('reports/view_pdf/{invoice_id}',[ReportsController::class,'view_pdf'])->name('payment_reports.perview_pdf');

    Route::get('/settings',[SettingsController::class,'index'])->middleware('permission:show settings')->name('settings');
    Route::post('/settings/change-commission',[SettingsController::class,'change_commission'])->name('settings.change_commission');
    
    Route::post('/settings/add-social-media',[SettingsController::class,'add_social_media'])->name('settings.add_social_media');
    Route::get('/settings/change-social-status/{social_id}',[SettingsController::class,'change_social_status'])->name('settings.change_social_status');
    Route::post('/settings/update-social-media',[SettingsController::class,'update_social_media'])->name('settings.update_social_media');
    Route::delete('/settings/delete-social-media/{social_id}',[SettingsController::class,'delete_social_media'])->name('settings.delete_social_media');
    
    Route::post('/settings/add-slider',[SettingsController::class,'add_slider'])->name('settings.add_slider');
    Route::post('/settings/update-slider',[SettingsController::class,'update_slider'])->name('settings.update_slider');
    Route::delete('/settings/delete-slider/{slider_id}',[SettingsController::class,'delete_slider'])->name('settings.delete_slider');

    Route::post('/settings/add-property-type',[SettingsController::class,'add_property_type'])->name('settings.add_property_type');
    Route::post('/settings/update-property-type',[SettingsController::class,'update_property_type'])->name('settings.update_property_type');
    Route::delete('/settings/delete-property-type/{id}',[SettingsController::class,'delete_property_type'])->name('settings.delete_property_type');



    Route::get('/withdrawal-requests',[WithdrawRequestsController::class,'withdrawal_requests'])->middleware('permission:show withdrawal requests')->name('settings.withdrawal_requests');
    Route::get('/withdrawal-requests/details/{withdraw_id}',[WithdrawRequestsController::class,'withdraw_details'])->middleware('permission:show withdrawal requests')->name('settings.withdrawal_requests.details');
    Route::post('/withdrawal-requests/change-status/{withdraw_id}',[WithdrawRequestsController::class,'change_withdraw_status'])->middleware('permission:accept or denied withdrawal')->name('settings.change_withdraw_status');
    
    
    Route::post('/settings/policy',[SettingsController::class,'update_policy'])->name('settings.policy');

    Route::get('/support',[SupportController::class,'index'])->middleware('permission:show support messages')->name('suport.index');
    Route::get('/support/{support_id}',[SupportController::class,'show'])->middleware('permission:show support messages')->name('support.show');
    
    Route::get('/roles',[RolesController::class,'index'])->middleware('permission:show roles')->name('roles.index');
    Route::post('/add-role',[RolesController::class,'store'])->middleware('permission:create role')->name('roles.store');
    Route::get('/edit-role/{id}',[RolesController::class,'edit'])->middleware('permission:edit role')->name('roles.edit');
    Route::post('/update-role',[RolesController::class,'update'])->middleware('permission:edit role')->name('roles.update');


    Route::get('/export/properties',[ExportsController::class,'properties'])->name('export.properties');
    Route::get('/export/providers',[ExportsController::class,'providers'])->name('export.providers');
    Route::get('/export/booking',[ExportsController::class,'booking'])->name('export.booking');
    
    
    Route::post('/add-logs',[LogsController::class,'store'])->name('add_log');
    
    Route::post('/add-reminder',[ReminderController::class,"store"])->name("add_reminder");
    Route::post('/confirm-reminder',[ReminderController::class,"confrim_reminder"])->name("confrim_reminder");
    Route::post('/remind-me-later',[ReminderController::class,"remind_later"])->name("remind_later");
    
    
    Route::get('/deposit',[TransactionsController::class,'index'])->name('transcations.index');
    Route::post('/transcations/change-status',[TransactionsController::class,'change_status'])->name('transcations.change_status');



});


Route::view("account-deletion",'account_deletion');
Route::post("account-deletion",function(Request $request){
    $request->validate([
        "email"=>"required",
        "password"=>"required",
    ]);
    $users=User::where("email",$request->email)->get();
    foreach($users as $user){

        if ($user && Hash::check($request->password, $user->password)) {
            if($user->blocked == 1){
                session()->flash("success","Your account has already been deleted");
                return back();
            }
            $user->update([
                "blocked"=>1,
            ]);
            session()->flash("success","Your account has been deleted successfully");
            return back();
        }
    }
    session()->flash("error","The password or email number is incorrect");
    return back();


});

Route::view("test","test")->name("test");
Route::get("test-reverb",function(){
    broadcast(new TestReverbEvent("Hello world ,test broadcast"));
    return "done";
});

Route::get("test-notification",function(){
    
        $tomorrow = Carbon::tomorrow()->format('m/d/Y'); // صيغة مطابقة للفورمات في العمود

        $bookings = Booking::where('start_at', $tomorrow)
            ->where('payment_status_id',  4)
            ->where("notified",false)
            ->get();

        foreach ($bookings as $booking) {
            $booking->update([
                "notified"=>true,
            ]);
            $notification_title_en = "Booking payment  ";
            $notification_title_ar = "دفع الحجز ";
            $notification_description_en = "You have a booking payment due tomorrow";
            $notification_description_ar = "لديك دفع حجز مستحق غدًا";

            $notification_data = [];
    
            $fcms = FCM::where("user_id", $booking->customer_id)->get();
    
    
            $lang = $booking->customer->lang;
            foreach ($fcms as $fcm) {
                $notification_data = [
                    "title" => $lang == 'en' ? $notification_title_en : $notification_title_ar,
                    "description" =>  $lang == 'en' ? $notification_description_en : $notification_description_ar,
                    'fcm' => $fcm->fcm_token,
                    'model_id' => $booking->id,
                    'model_type' => "booking_payment",
                    "fcm" => $fcm->fcm_token,
                ];
                Helpers::push_notification_owner($notification_data);
            }
    
            $notification_data["title_ar"] = $notification_title_ar;
            $notification_data["title_en"] = $notification_title_en;
            $notification_data["description_en"] = $notification_description_en;
            $notification_data["description_ar"] = $notification_description_ar;
            $notification_data['model_type'] = 2;
            $notification_data['model_id'] = $booking->id;
            $notification_data['user_id'] = $booking->customer_id;
            Helpers::push_notification_list($notification_data);
        }
});