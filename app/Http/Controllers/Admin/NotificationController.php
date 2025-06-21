<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{

    static public function notify($title, $body, $device_key) {
        $url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = "YOUR_SERVER_KEY";  // Replace with your actual server key
    
        $dataArr = [
            "click_action" => "FLUTTER_NOTIFICATION_CLICK",
            "status" => "done",
        ];
    
        $data = [
            "registration_ids" => [$device_key],
            "notification" => [
                "title" => $title,
                "body" => $body,
                "sound" => "default",
            ],
            "data" => $dataArr,
            "priority" => "high",
        ];
    
        $encoding = json_encode($data);
    
        $headers = [
            "Authorization: key=$serverKey",
            "Content-Type: application/json",
        ];
    
        $response = Http::withHeaders($headers)->post($url, $data);
    
        return $response->json();
    }
    
}
