<?php

namespace App\Http\Controllers\API;

use App\Models\FCM;
use App\CPU\Helpers;
use App\Models\User;
use App\Models\UserMobile;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\API\ResponseTrait;

class AuthController extends Controller
{
    use ResponseTrait;
    private $OtpService;
    public function __construct(OtpService $OtpService)
    {
        $this->OtpService = $OtpService;
    }
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login', 'register', 'reset_password', 'check_email']]);
    // }

    public function login(Request $request)
    {

        $lang = $request->lang ?? 'en'; // Default to 'en' if 'lang' is not provided
        App::setLocale($lang);
        $validator = Validator::make($request->all(), [
            "phone" => "required",
            'password' => 'required',
            "fcm_token" => "required",
        ], [
            'phone.required' => __('messages.required', ['attribute' => __('messages.attributes.phone')]),
            'password.required' => __('messages.required', ['attribute' => __('messages.attributes.password')]),
        ]);

        if ($validator->fails()) {
            return $this->Response($validator->errors()->first(), "Data Not Valid", 422);
        }
        $role = null;
        if ($request->role == 1)
            $role = "customer";
        elseif ($request->role == 2)
            $role = "provider";
        $user = User::where("phone", $request->phone)
            ->orWhere("email", $request->phone)
            ->where("power", $role)->first();

        if (! $user || !Hash::check($request->password, $user->password)) {
            return $this->Response(__('messages.Incorrect_phone_or_password'), __('messages.Incorrect_phone_or_password'), 401);
        }
        if ($user->blocked == 1) {
            return $this->Response(null, __('messages.Blocked_user'), 403);
        }
        $user->update([
            "lang" => $lang,
        ]);
        $user->tokens()->delete();
        $token = $user->createToken('API Token')->plainTextToken;
        $check_fcm = FCM::where("user_id", $user->id)
            ->where("fcm_token", $request->fcm_token)->first();
        if (!$check_fcm)
            FCM::create([
                "user_id" => $user->id,
                "fcm_token" => $request->fcm_token,
            ]);

        $data = [
            "user" => $user,
            "token" => $token,
        ];
        return $this->Response($data, "Login Successfully", 201);
    }

    public function register(Request $request)
    {
        $lang = $request->input('lang', 'en'); // Default to 'en' if 'lang' is not provided
        App::setLocale($lang);
        $validator = Validator::make($request->all(), [
            "name" => "required|min:2|max:255",
            "phone" => "required|regex:/^([0-9\s\-\+\(\)]*)$/",
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            "email" => "required",

            "fcm_token" => "required",
        ], [
            'name.required' => __('messages.required', ['attribute' => __('messages.attributes.name')]),
            'phone.required' => __('messages.required', ['attribute' => __('messages.attributes.phone')]),
            'email.required' => __('messages.required', ['attribute' => __('messages.attributes.email')]),
            'password.required' => __('messages.required', ['attribute' => __('messages.attributes.password')]),
            'phone.regex' => (__('messages.Phone_number_not_valid')),
        ]);
        if ($validator->fails()) {
            return $this->Response($validator->errors()->first(), $validator->errors()->first(), 422);
        }
        $role = null;
        if ($request->role == 1)
            $role = "customer";
        elseif ($request->role == 2)
            $role = "provider";
        else
            $role = "customer";
        $user = User::where("phone", $request->phone)
        ->orWhere("email", $request->email)
        ->where("power", $role)->first();
        // return $this->Response($user,"",200);
        if ($user) {
            if ($user->blocked == 1) {

                $user->update([
                    "blocked" => 0,
                    "password" => bcrypt($request->password),
                    "name" => $request->name,
                    "email_verified_at"=>null,

                ]);
                $check_fcm = FCM::where("user_id", $user->id)->where("fcm_token", $request->fcm_token)->first();
                if (!$check_fcm) {
                    FCM::create([
                        "user_id" => $user->id,
                        "fcm_token" => $request->fcm_token,
                    ]);
                }
                $response = $this->OtpService->sendOtp($user);

                if ($response) {
                    return $response;
                }
                $token = $user->createToken('users', ['*'])->plainTextToken;
                $data = [
                    'user' => $user,
                    "token" => $token,
                ];

                return $this->Response($data, __('messages.Account_recovered'), 201);
            } else {
                return $this->Response(null, __('messages.unique', ['attribute' => __('messages.attributes.phone')]), 422);
            }
        }

        $user = User::create([
            "name" => $request->name,
            "phone" => $request->phone,
            "password" => bcrypt($request->password),
            "image" => "images/default/avatar.png",
            "power" => $role,
            "email" => $request->email,
        ]);
        if ($user->power == 'customer') {
            Cache::forget("customers");
            Cache::rememberForever("customers", function () {
                return User::select('id', 'name')->where('power', 'customer')->orderBy('id', 'DESC')->get();
            });
        } elseif ($user->power == "provider") {
            Cache::forget("providers");

            Cache::rememberForever('providers', function () {
                return User::select('id', 'name')->where('power', 'provider')->get();
            });
        }
        $user->update([
            "lang" => $lang,
        ]);
        $user->assignRole($user->power);

        FCM::create([
            "user_id" => $user->id,
            "fcm_token" => $request->fcm_token,
        ]);
        $response = $this->OtpService->sendOtp($user);

        if ($response) {
            return $response;
        }
        $token = $user->createToken('users', ['*'])->plainTextToken;
        $data = [
            'user' => $user,
            "token" => $token,
        ];
        return $this->Response($data, "Created Successfully", 201);
    }
    public function logout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "fcm_token" => "required",
        ]);
        if ($validator->fails()) {
            return $this->Response($validator->errors(), "Data Not Valid", 422);
        }
        FCM::where("fcm_token", $request->fcm_token)->where("user_id", $request->user()->id)->delete();
        $request->user()->tokens()->delete();
        return $this->Response(null, "Logout", 200);
    }
    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'confrim_password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'new_password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            "email" => "required",

        ], [
            'email.required' => __('messages.required', ['attribute' => __('messages.attributes.email')]),
            'new_password.required' => __('messages.required', ['attribute' => __('messages.attributes.password')]),
        ]);
        if ($validator->fails()) {
            return $this->Response($validator->errors()->first(), $validator->errors()->first(), 422);
        }
        $user = User::where("email", $request->email)->first();
        if (!$user) {
            return $this->Response(null, "The email number is not registered", 422);
        }
        if ($user && $request->new_password == $request->confrim_password) {
            $user->update([
                "password" => bcrypt($request->new_password),
            ]);
            return $this->Response(null, __('messages.Password_updated_successfully'), 201);
        }
        return $this->Response(null, "Password not match", 422);
    }
    public function check_email(Request $request)
    {
        $user = User::where("email", $request->phone)->first();
        return $this->Response(($user != null ? true : false), ($user != null ? "email is registered" : "email is not registered"), ($user != null ? 201 : 422));
    }
}
