<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\FCM;
use App\CPU\Helpers;
use App\Models\Notification;
use App\Models\Subscription;
use Illuminate\Console\Command;

class NotifyExpirePackages extends Command
{
    protected $signature = 'app:notify-expire-packages';
    protected $description = 'Command description';

    public function handle()
    {

        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
    
    
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        
        $subscriptions = Subscription::where(function ($query) use ($today, $tomorrow) {
                $query->whereDate('end_subscribe', $today)
                      ->orWhereDate('end_subscribe', $tomorrow);
            })
            ->where('notify', '!=', 2)
            ->with('provider')
            ->get();
        foreach ($subscriptions as $subscription) {
            $endDate = Carbon::parse($subscription->end_subscribe);
    
            if ($endDate->isSameDay($today)) {
                if($subscription->notify == 1){
  
                    $subscription->notify = 2;
                    $subscription->save();
                    
                    $notification_title_en = "Package subscription  ";
                    $notification_title_ar = " اشتراك الباقة ";
                    $notification_description_en = "Your package subscription ends today";
                    $notification_description_ar = "تنتهي اشتراك الباقة اليوم";
                }   
            } elseif ($endDate->isSameDay($tomorrow)) {
                if($subscription->notify == 0){
                    $subscription->notify = 1;
                    $subscription->save();
        
                    $notification_title_en = "Package subscription  ";
                    $notification_title_ar = " اشتراك الباقة ";
                    $notification_description_en = "Your package subscription ends Tomorrow";
                    $notification_description_ar = "تنتهي اشتراك الباقةغدآ ";
                }
            }
            
            $notification_data = [];
    
            $fcms = FCM::where("user_id", $subscription->provider_id)->get();
    
    
            $lang = $subscription->provider->lang;
            foreach ($fcms as $fcm) {
                $notification_data = [
                    "title" => $lang == 'en' ? $notification_title_en : $notification_title_ar,
                    "description" =>  $lang == 'en' ? $notification_description_en : $notification_description_ar,
                    'fcm' => $fcm->fcm_token,
                    'model_id' => $subscription->id,
                    'model_type' => "package_subscription",
                    "fcm" => $fcm->fcm_token,
                ];
                Helpers::push_notification_owner($notification_data);
            }
    
            $notification_data["title_ar"] = $notification_title_ar;
            $notification_data["title_en"] = $notification_title_en;
            $notification_data["description_en"] = $notification_description_en;
            $notification_data["description_ar"] = $notification_description_ar;
            $notification_data['model_type'] = 2;
            $notification_data['model_id'] = $subscription->id;
            $notification_data['user_id'] = $subscription->provider_id;
            Helpers::push_notification_list($notification_data);
        }
    }
}
