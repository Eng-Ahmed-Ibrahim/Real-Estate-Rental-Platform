<?php

namespace App\Http\Controllers\API;

use DateTime;
use Exception;
use App\CPU\Helpers;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SocialMedia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;

class SettingsController extends Controller
{
    use ResponseTrait;
    public function index(){
        $data=Helpers::settings();
        return $this->Response($data,"Settings",201);

    }
    public function terms_policy(Request $request){
        if($request->role==2){

            $data=Setting::where("id",1)->select('privacy_status','refund_status','terms_status','about_us_status','privacy_policy_ar','privacy_policy','refund_policy_ar','refund_policy','term_ar','term','about_us','about_us_ar')->first();
        }else{
            $setting=Setting::where("id",1)->select('privacy_status_user','refund_status_user','terms_status_user','about_us_status','privacy_policy_ar_user','privacy_policy_user','refund_policy_ar_user','refund_policy_user','term_ar_user','term_user','about_us','about_us_ar')->first();
            $data=[
                "privacy_status"=>$setting->privacy_status_user,
                "refund_status"=>$setting->refund_status_user,
                "terms_status"=>$setting->terms_status_user,
                "about_us_status"=>$setting->about_us_status,

                "privacy_policy_ar"=>$setting->privacy_policy_ar_user,
                "privacy_policy"=>$setting->privacy_policy_user,

                "refund_policy_ar"=>$setting->refund_policy_ar_user,
                "refund_policy"=>$setting->refund_policy_user,

                "term_ar"=>$setting->term_ar_user,
                "term"=>$setting->term_user,

                "about_us"=>$setting->about_us,
                "about_us_ar"=>$setting->about_us_ar,
            ];
        }
        return $this->Response($data,"Settings",201);

    }
    public function social_media(){
        $social=SocialMedia::where("status",true)->orderBy("id","DESC")->get();
        $contact_email=Setting::find(1)->contact_email;
        $data=[
            "social_media"=>$social,
            "email"=>$contact_email,
        ];
        return $this->Response($data,"Settings",201);

    }
    public function change_local_language(Request $request){
        
        User::find($request->user()->id)->update([
            'lang'=>$request->lang,
        ]);
        App::setLocale($request->lang);
        session()->put("lang",$request->lang);


        return $this->Response(null,__('messages.Updated_successfully'),201);
    }

}
