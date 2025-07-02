<?php

namespace App\Services;

use Ramsey\Uuid\Uuid;
use App\Models\Notification;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    public  function push_notification_user($data)
    {

        $fcm = $data['fcm'];
        $title = $data['title'];
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
    public  function push_notification_owner($data)
    {
        $fcm = $data['fcm'];
        $title = $data['title'];
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

}
