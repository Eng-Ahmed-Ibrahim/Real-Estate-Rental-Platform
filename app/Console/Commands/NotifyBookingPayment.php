<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use App\Models\FCM;
use App\CPU\Helpers;
use App\Models\Booking;
use Illuminate\Console\Command;

class NotifyBookingPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-booking-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $tomorrow = Carbon::tomorrow()->format('m/d/Y'); 

        // $bookings = Booking::where('start_at', $tomorrow)
        //     ->where('payment_status_id',  4)
        //     ->where("notified",0)
        //     ->get();

        //     foreach ($bookings as $booking) {
        //            $booking->update([
        //         "notified"=>1,
        //     ]);
        //     $notification_title_en = "Booking payment  ";
        //     $notification_title_ar = "دفع الحجز ";
        //     $notification_description_en = "You have a booking payment due tomorrow";
        //     $notification_description_ar = "لديك دفع حجز مستحق غدًا";

        //     $notification_data = [];
    
        //     $fcms = FCM::where("user_id", $booking->customer_id)->get();
    
    
        //     $lang = $booking->customer->lang;
        //     foreach ($fcms as $fcm) {
        //         $notification_data = [
        //             "title" => $lang == 'en' ? $notification_title_en : $notification_title_ar,
        //             "description" =>  $lang == 'en' ? $notification_description_en : $notification_description_ar,
        //             'fcm' => $fcm->fcm_token,
        //             'model_id' => $booking->id,
        //             'model_type' => "booking_payment",
        //             "fcm" => $fcm->fcm_token,
        //         ];
        //         Helpers::push_notification_owner($notification_data);
        //     }
    
        //     $notification_data["title_ar"] = $notification_title_ar;
        //     $notification_data["title_en"] = $notification_title_en;
        //     $notification_data["description_en"] = $notification_description_en;
        //     $notification_data["description_ar"] = $notification_description_ar;
        //     $notification_data['model_type'] = 2;
        //     $notification_data['model_id'] = $booking->id;
        //     $notification_data['user_id'] = $booking->customer_id;
        //     Helpers::push_notification_list($notification_data);
        // }


    }
}
