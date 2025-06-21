<?php

namespace App\Services;

use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\OtpMail;
use App\Models\Subscription;
use App\Models\PackageFeatures;
use Illuminate\Support\Facades\Mail;
use App\Notifications\PushNotification;
use App\Http\Controllers\API\ResponseTrait;

class  OtpService
{
    use ResponseTrait;
    public function sendOtp($user)
    {
        if ($user->last_otp_sent && now()->diffInSeconds($user->last_otp_sent) < 60) {
            $secondsLeft = 60 - now()->diffInSeconds($user->last_otp_sent);
            return $this->Response(null,__('messages.otp_wait_message', ['seconds' => $secondsLeft]), 422);
        }
        $otp = rand(10000, 99999);
        $email = $user->email;
        Mail::to($email)->send(new OtpMail(['otp' => $otp]));

        $user->update([
            "otp" => $otp,
            "last_otp_sent" => Carbon::now(),
        ]);
    }
}
