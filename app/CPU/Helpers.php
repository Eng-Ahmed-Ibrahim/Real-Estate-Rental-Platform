<?php

namespace App\CPU;

use DateTime;
use Exception;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use App\Mail\OtpMail;
use Ramsey\Uuid\Uuid;
use App\Models\Cities;
use App\Models\Booking;
use App\Models\Feature;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Sliders;
use App\Models\Packages;
use App\Models\Categories;
use App\Models\Notification;
use Google\Client as GoogleClient;
use App\Models\ServiceEventDays;
use Google\Service\Adsense\Cell;
use Google\Service\Docs\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\API\ResponseTrait;

class Helpers
{
    use ResponseTrait;
    public static function upload_files($file)
    {

        $originalName = $file->getClientOriginalName();
        $name = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName);
        $file->move(public_path("files/"), $name);
        return "files/$name";
    }
    public static function delete_file($path)
    {

        $file = str_replace("files/", "", $path);

        File::delete(public_path("files/$file"));


        return;
    }
    public static function check_if_dates_are_booked($service_id, $start_at, $end_at, $booking_id)
    {
        $startDate = $start_at ? Carbon::parse($start_at)->format('Y-m-d') : null;
        $endDate = $end_at ? Carbon::parse($end_at)->format('Y-m-d') : null;

        $conflictingBooking = Booking::where('service_id', $service_id)
            ->where('id', '!=', $booking_id)
            ->whereIn('payment_status_id', [3, 4])
            ->where("booking_status_id", 3)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where(DB::raw("STR_TO_DATE(start_at, '%m/%d/%Y')"), '<', $endDate)
                        ->where(DB::raw("STR_TO_DATE(end_at, '%m/%d/%Y')"), '>', $startDate);
                });
            })
            ->exists();

        return $conflictingBooking;
    }

    public static function get_booking_total_price(DateTime  $start_at, DateTime  $end_at, $service_id)
    {
        $service = Service::find($service_id);
        $startDate = $start_at;
        $endDate = $end_at;



        $start = $startDate;
        $end = $endDate;

        // $end->modify('+1 day');

        $interval = new DateInterval('P1D');

        $datePeriod = new DatePeriod($start, $interval, $end);

        $dates = [];
        foreach ($datePeriod as $date) {
            $dates[] = $date->format('m/d/Y');
        }

        $eventDays = ServiceEventDays::where("service_id", $service_id)->get();
        $event_days = [];
        $event_days_prices = 0;
        if (count($eventDays) > 0) {
            foreach ($eventDays as $eventDay) {
                // Convert the event day to the desired format

                // Check if the event date is in the dates array
                if (($key = array_search($eventDay->day, $dates)) !== false) {
                    $event_days_prices += $eventDay->price;
                    $event_days[] = [
                        "date" => $eventDay->day,
                        "price" => $eventDay->price,
                    ];
                    unset($dates[$key]);
                }
            }
        }
        $prices_normal_day = $service->regular_price * count($dates);
        $total = $event_days_prices + $prices_normal_day;

        return $total;
    }

    public static function push_notification_owner($data)
    {
        $fcm = $data['fcm'];
        $title = $data['title'];
        if (empty($fcm) || empty($title) || empty($data['model_type']) || empty($data['model_id']) || empty($data['description'])) {
            return;
        }
        $model_type = $data['model_type'];
        $model_id = $data['model_id'];
        $description = $data['description'];
        $credentialsFilePath = Http::get(asset('json/ren2go-owner-2427d8fae841.json')); // in server
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        $access_token = $token['access_token'];

        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];

        $data = [
            "message" => [
                "token" => $fcm,
                "notification" => [
                    "title" => (string) $title,
                    "body" => (string) $description,
                ],
                "data" => [
                    "title" => (string) $title,
                    "body" => (string) $description,
                    "model_id" => (string) $model_id,
                    "model_type" => (string) $model_type,
                ],
            ]
        ];
        $payload = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/ren2go-owner/messages:send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return response()->json([
                'message' => 'Curl Error: ' . $err
            ], 500);
        } else {
            return response()->json([
                'message' => 'Notification has been sent',
                'response' => json_decode($response, true)
            ]);
        }
    }
    public static function push_notification_user($data)
    {

        $fcm = $data['fcm'];
        $title = $data['title'];
        if (empty($fcm) || empty($title) || empty($data['model_type']) || empty($data['model_id']) || empty($data['description'])) {
            return;
        }
        $description = $data['description'];
        $model_type = $data['model_type'];
        $model_id = $data['model_id'];
        $credentialsFilePath = Http::get(asset('json/ren2go-user-5f5939ed56a5.json')); // in server
        $client = new GoogleClient();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();

        $access_token = $token['access_token'];

        $headers = [
            "Authorization: Bearer $access_token",
            'Content-Type: application/json'
        ];

        $data = [
            "message" => [
                "token" => $fcm,
                "notification" => [
                    "title" => (string) $title,
                    "body" => (string) $description,
                ],
                "data" => [
                    "title" => (string) $title,
                    "body" => (string) $description,
                    "model_id" => (string) $model_id,
                    "model_type" => (string) $model_type,
                ],
            ]
        ];
        $payload = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/ren2go-user/messages:send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return response()->json([
                'message' => 'Curl Error: ' . $err
            ], 500);
        } else {
            return response()->json([
                'message' => 'Notification has been sent',
                'response' => json_decode($response, true)
            ]);
        }
    }
    public static function push_notification_list($data)
    {
        $title_ar = $data['title_ar'];
        $title_en = $data['title_en'];
        $model_type = $data['model_type'] ?? null;
        $model_id = $data['model_id'] ?? null;
        $description_ar = $data['description_ar'];
        $description_en = $data['description_en'];
        $uuid = Uuid::uuid4()->toString(); // Generate a version 4 (random) UUID and convert it to string

        $notification = Notification::create([
            "user_id" => $data['user_id'],
            "title" => $title_en,
            "title_ar" => $title_ar,
            "body" => $description_en,
            "body_ar" => $description_ar,
            "seen" => 0,
            'model_type' => $model_type,
            'model_id' => $model_id,
            "id" => $uuid,
        ]);
        return $notification;
    }
    public static function settings()
    {
        $setting = Setting::find(1);
        $data = [
            "owner_name" => $setting->owner_name,
            "contact_number" => $setting->phone,
            "whatsapp_phone" => $setting->whatsapp_phone,
            "contact_email" => $setting->contact_email,
        ];
        return $data;
    }

    public static function distance_between_two_locations($data)
    {
        $degToRad = pi() / 180;
        $user_lat = $data['user_lat'] * $degToRad;
        $user_lng = $data['user_lng'] * $degToRad;

        $service_lat = $data['service_lat'] * $degToRad;
        $service_lng = $data['service_lng'] * $degToRad;


        $earthRadius = 6371; // Radius of Earth in kilometers

        $deltaLat = $service_lat - $user_lat;
        $deltaLong = $service_lng - $user_lng;

        $a = sin($deltaLat / 2) ** 2 +
            cos($user_lat) * cos($service_lat) *
            sin($deltaLong / 2) ** 2;

        $c = 2 * asin(sqrt($a));

        // $distance_m=ceil(($earthRadius * $c) * 1000) ;
        // $distance = ($distance_m > 1000 ? ($distance_m /1000) . " km" : $distance_m ." m"); 
        $distance_m = ceil(($earthRadius * $c));
        $distance = ($distance_m / 1000) . " km";
        return $distance;
    }
    public static function delete_days_after_booking($booking_id)
    {
        $booking = Booking::find($booking_id);
        $days = json_decode(Service::find($booking->service_id)->days, true);

        // Start and end dates
        $startDate = new DateTime($booking->start_at);
        $endDate = new DateTime($booking->end_at);
        $requestEndAt = new DateTime($booking->end_at);

        // Fixed time
        $fixedTime = '09:00 AM';

        // Initialize the date range array
        $dateRange = [];

        // Loop through the date range
        while ($startDate <= $endDate) {
            if ($startDate == $requestEndAt) {
                // Skip adding the current date if it matches $requestEndAt
                $startDate->modify('+1 day');
                continue;
            }

            // Set the fixed time for the current date
            $currentDate = $startDate->format('m/d/Y') . ' ' . $fixedTime;

            // Add the current date with fixed time to the array
            $dateRange[] = $currentDate;

            // Move to the next day
            $startDate->modify('+1 day');
        }

        // Convert $days array to match the format of $dateRange
        $daysFormatted = array_map(function ($day) use ($fixedTime) {
            return date('m/d/Y', strtotime($day)) . ' ' . $fixedTime;
        }, $days);


        // Remove dates from $daysFormatted that exist in $dateRange
        $filteredDays = array_values(array_diff($daysFormatted, $dateRange));

        // Convert $filteredDays back to original format if needed
        $filteredDaysOriginalFormat = array_map(function ($day) {
            return date('m/d/Y h:i A', strtotime($day));
        }, $filteredDays);

        Service::find($booking->service_id)->update(['days' => json_encode($filteredDaysOriginalFormat)]);
    }
    public static function add_days_after_cancel_request($booking_id)
    {
        $booking = Booking::find($booking_id);
        $service = Service::find($booking->service_id);
        $days = json_decode($service->days, true) ?? []; // Ensure it's an array


        // Start and end dates
        $startDate = strtotime($booking->start_at);
        $endDate = strtotime($booking->end_at);

        $currentDate = $startDate;
        $dateRange = [];

        // Generate date range (only dates, ignoring time)
        while ($currentDate < $endDate) {
            $dateRange[] = date('m/d/Y h:i A', $currentDate); // Format as YYYY-MM-DD
            $currentDate = strtotime('+1 day', $currentDate);
        }


        // Merge, remove duplicates, and sort by date
        $mergedDays = array_unique(array_merge($days, $dateRange));
        sort($mergedDays); // Sort in ascending order





        // Update the service with the sorted days
        $service->update(['days' => json_encode($mergedDays)]);
    }
    public static function send_otp($user)
    {
        if ($user->last_otp_sent && now()->diffInSeconds($user->last_otp_sent) < 60) {
            $secondsLeft = 60 - now()->diffInSeconds($user->last_otp_sent);
            return response()->json([
                "message" => __('messages.otp_wait_message', ['seconds' => $secondsLeft])
            ], 422);
        }
        $otp = rand(10000, 99999);
        $email = $user->email;
        Mail::to($email)->send(new OtpMail(['otp' => $otp]));

        $user->update([
            "otp" => $otp,
            "last_otp_sent" => Carbon::now(),
        ]);
    }
    public static function cacheSliders()
    {
        Cache::forget('sliders');
        Cache::rememberForever('sliders', function () {
            return Sliders::orderBy("id", "DESC")->get();
        });
    }
    public static function cacheCategories()
    {
        Cache::forget('categories');
        Cache::rememberForever('categories', function () {
            return Categories::all();
        });
    }
    public static function cacheFeatures()
    {
        Cache::forget("features");
        Cache::rememberForever('features', function () {
            return Feature::select('id', 'feature_name', 'feature_name_ar')->orderBy('id', 'DESC')->get();
        });
    }
    public static function cacheCities()
    {
        Cache::forget("cities");
        Cache::rememberForever("cities", function () {
            return Cities::orderBy("id", "DESC")->get();
        });
    }
    public static function cachePackages()
    {
        Cache::forget("packages");
        Cache::rememberForever("packages", function () {
            return Packages::orderBy("id", "DESC")->get();
        });
    }
}
