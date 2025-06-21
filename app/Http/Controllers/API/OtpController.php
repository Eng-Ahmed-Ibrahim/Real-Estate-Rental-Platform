<?php

namespace App\Http\Controllers\API;

use App\CPU\Helpers;
use App\Models\User;
use App\Mail\OtpMail;
use App\Services\OtpService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    use ResponseTrait;
    private $OtpService;
    public function __construct(OtpService $OtpService)
    {
        $this->OtpService = $OtpService;
    }
    public function send_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email|exists:users,email",
        ]);

        if ($validator->fails()) {
            return $this->Response($validator->errors()->first(), "Data Not Valid", 422);
        }
        $role = null;
        if ($request->role == 1)
            $role = "customer";
        elseif ($request->role == 2)
            $role = "provider";
        if ($role == null) {
            return $this->Response(null, "Data invalid", 422);
        }
        $user = User::where("email", $request->email)->where("power", $role)->first();
        if (! $user) {
            return $this->Response(null, "User not found", 422);
        }
        $response = $this->OtpService->sendOtp($user);

        if ($response) {
            return $response;
        }

        return response()->json(['message' => 'OTP sent successfully.']);
    }

    public function verify_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email|exists:users,email",
            "otp" => "required|numeric",
        ]);

        if ($validator->fails()) {
            return $this->Response($validator->errors()->first(), "Data Not Valid", 422);
        }
        $role = null;
        if ($request->role == 1)
            $role = "customer";
        elseif ($request->role == 2)
            $role = "provider";
        if ($role == null) {
            return $this->Response(null, "Data invalid", 422);
        }
        $user = User::where("email", $request->email)->where("power", $role)->first();
        if (! $user) {
            return $this->Response(null, "User not found", 422);
        }
        if ($user->otp != $request->otp) {
            return $this->Response(null, "Invalid OTP", 422);
        }
            if ($user->last_otp_sent && now()->diffInMinutes($user->last_otp_sent) > 5) {
        return $this->Response(null, __('messages.otp_expired'), 422);
    }

        $user->update([
            "otp" => null,
            "email_verified_at" => now(),
        ]);
        return response()->json(['message' => 'Email verified successfully.']);
    }
}
