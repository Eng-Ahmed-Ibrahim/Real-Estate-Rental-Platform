<?php

namespace App\Services;

use DateTime;
use App\Models\User;
use App\Models\Subscription;
use App\Models\PackageFeatures;
use App\Notifications\PushNotification;

class  PackageServices
{
    public function set_package_to_provider($provider_id, $package, $paid, bool $notify_admin, $payment_method = null, $attachment = null, $payment_method_id = null)
    {
        $subscripe = null;
        if ($paid == "paid") {

            $this->end_pervious_subscriptions($provider_id);
            $subscripe = $this->add_package_paid($provider_id, $package);
            if ($notify_admin) {
                $this->notify_admins();
            }
        } elseif ($paid == "pending") {
            $subscripe = $this->add_package_pending($provider_id, $package, $payment_method, $attachment, $payment_method_id);
        }
        return  $subscripe;
    }
    protected function add_package_paid($provider_id, $package)
    {
        $startSubscribe = now();
        $endSubscribe = $startSubscribe->copy()->addMonths($package->duration);
        $startSubscribeFormatted = $startSubscribe->format('Y-m-d');
        $endSubscribeFormatted = $endSubscribe->format('Y-m-d');
        $subscribe = Subscription::create([
            "provider_id" => $provider_id,
            "package_id" => $package->id,
            "payment_method" => "wallet",
            "package_duration" => $package->duration,
            "package_amount" => $package->price,
            "service_limit" => $package->service_limit,
            "paid" => "paid", // pending,paid
            "status" => 1, // 1 ,0
            "start_subscribe" => $startSubscribeFormatted,
            "end_subscribe" => $endSubscribeFormatted,
        ]);
        return $subscribe;
    }
    protected function add_package_pending($provider_id, $package, $payment_method, $attachment, $payment_method_id)
    {
        $subscribe = Subscription::create([
            "provider_id" => $provider_id,
            "package_id" => $package->id,
            "payment_method" => $payment_method,
            "package_duration" => $package->duration,
            "package_amount" => $package->price,
            "service_limit" => $package->service_limit,
            "paid" => "pending",
            "attachment" => $attachment,
            "status" => 0,
            "payment_method_id" => $payment_method_id,
        ]);
        return $subscribe;
    }
    protected function end_pervious_subscriptions($provider_id)
    {
        $subscribers = Subscription::where("provider_id", $provider_id)->get();
        foreach ($subscribers as $subscribe) {
            $subscribe->update(["status" => 0]);
        }
    }
    protected function notify_admins()
    {
        $admins = User::where("power", "admin")->get();
        foreach ($admins as $admin) {
            $data = [
                "title_en" => "Package subscription",
                "title_ar" => " اشتراك باقه",
                "body_en" => "A new subscription or renewal  package  has been requested by the owner",
                "body_ar" => "تم طلب اشتراك او تجديد باقه  من قبل مزود خدمات",
                "url" => route('admin.packages.subscribers'),
            ];
            $admin->notify(new PushNotification($data));
        }
    }


    
    public function provider_packages($package){
        $data=$this->features($package->package->features);
        $features=$data['features'];
        $un_features=$data['un_features'];
        $package=$this->package_format($package,$features,$un_features);
        return $package;
    }
    protected function features($features)
    {
        $featuresIds = [];
        $features = [];

        foreach ($features as $feature) {
            $featuresIds[] = $feature->feature_id;
            $features[] = [
                "feature" => $feature->feature->feature,
                "feature_ar" => $feature->feature->feature_ar,
            ];
        }
        $un_features = PackageFeatures::whereNotIn("id", $featuresIds)->get();
        return ["features" => $features, "un_features" => $un_features];
    }
    protected function package_format($package, $features, $un_features)
    {
        $endDate = new DateTime($package->end_subscribe);
        $today = new DateTime();

        // Calculate the difference between the current date and the end date
        $remaining_days = $today->diff($endDate)->days;
        return [
            "id" => $package->id,
            "package_duration" => $package->package_duration,
            "package_amount" => $package->package_amount,
            "payment_method" => $package->payment_method,
            "start_subscribe" => $package->start_subscribe,
            "end_subscribe" => $package->end_subscribe,
            "remaining_days" => $remaining_days,
            "paid" => $package->paid,
            "status" => $package->status,
            "name" => $package->package->name,
            "name_ar" => $package->package->name_ar,
            "service_limit" => $package->package->service_limit,
            "image" => $package->package->image,
            "attachment" => $package->attachment,
            "features" => $features,
            "un_features" => $un_features,
        ];
    }
}
