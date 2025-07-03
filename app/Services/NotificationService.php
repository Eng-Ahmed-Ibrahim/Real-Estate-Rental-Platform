<?php
namespace App\Services;

use App\Models\FCM;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Notification;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    public function send($title_en, $title_ar, $description_en, $description_ar, $user_id, $model_id, $model_type)
    {
        $user = User::find($user_id);
        $lang = $user->lang;

        $title = $lang === 'en' ? $title_en : $title_ar;
        $description = $lang === 'en' ? $description_en : $description_ar;

        $fcms = FCM::where('user_id', $user_id)->get();

        foreach ($fcms as $fcm) {
            $notificationData = [
                'fcm' => $fcm->fcm_token,
                'title' => $title,
                'description' => $description,
                'model_id' => $model_id,
                'model_type' => $model_type,
            ];

            $isProvider = $user->power == 'provider'  ?1:0;
            $this->sendToFirebase($notificationData, $isProvider);
        }

        $this->storeNotification($title_en, $title_ar, $description_en, $description_ar, $user_id, $model_id, $model_type);
    }

    private function sendToFirebase($data, $isProvider)
    {
        $credentialsPath = $isProvider
            ? asset('json/ren2go-owner-2427d8fae841.json')
            : asset('json/ren2go-user-5f5939ed56a5.json');

        $url = $isProvider
            ? 'https://fcm.googleapis.com/v1/projects/ren2go-owner/messages:send'
            : 'https://fcm.googleapis.com/v1/projects/ren2go-user/messages:send';

        $client = new GoogleClient();
        $client->setAuthConfig(Http::get($credentialsPath));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken()['access_token'];

        $payload = [
            'message' => [
                'token' => $data['fcm'],
                'notification' => [
                    'title' => $data['title'],
                    'body' => $data['description'],
                ],
                'data' => [
                    'title' => $data['title'],
                    'body' => $data['description'],
                    'model_id' => (string) $data['model_id'],
                    'model_type' => (string) $data['model_type'],
                ],
            ],
        ];

        $headers = [
            "Authorization: Bearer $token",
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            // ممكن تسجل الخطأ في اللوج بدلاً من return response()
            \Log::error('FCM Error: ' . $err);
        } else {
            \Log::info('Notification sent: ', json_decode($response, true));
        }
    }

    private function storeNotification($title_en, $title_ar, $description_en, $description_ar, $user_id, $model_id, $model_type)
    {
        return Notification::create([
            'id' => Uuid::uuid4()->toString(),
            'user_id' => $user_id,
            'title' => $title_en,
            'title_ar' => $title_ar,
            'body' => $description_en,
            'body_ar' => $description_ar,
            'seen' => 0,
            'model_type' => $model_type,
            'model_id' => $model_id,
        ]);
    }
}
