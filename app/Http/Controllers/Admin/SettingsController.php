<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\FCM;
use App\CPU\Helpers;
use App\Models\User;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Sliders;
use App\Models\Commission;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use App\Models\PropertyTypes;
use App\Models\WithdrawEarning;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $setting = Setting::find(1);
        $commissions = Commission::orderBy("id", "DESC")->get();
        $social_medias = SocialMedia::orderBy("id", "DESC")->get();
        $sliders = Sliders::orderBy("id", "DESC")->get();
        $propertyTypes = PropertyTypes::orderBy("id", "DESC")->get();
        return view('admin.settings.index')
            ->with("setting", $setting)
            ->with("sliders", $sliders)
            ->with("propertyTypes", $propertyTypes)
            ->with("social_medias", $social_medias)
            ->with("commissions", $commissions);
    }
    public function change_commission(Request $request)
    {
        $request->validate([
            'commission' => 'required|numeric|min:0|max:100',
        ]);
        // Commission::find(1)->update([
        //     'commission_value'=>$request->commission,
        // ]);
        $commission = Setting::find(1);
        if ($commission->commission_value != $request->commission) {
            $services = Service::where("commission_id", 0)->get();

            foreach ($services as $service) {

                $commission_value = $request->commission;
                if ($request->commission_type == "percentage")
                    $commission_money = ($service->regular_price * $commission_value) / 100;
                else
                    $commission_money =  $commission_value;
                $service->update([
                    "commission_percentage" => $commission_money,
                    "commission_money" => $commission_money,

                    "price" => $service->regular_price + $commission_money,
                ]);
            }
        }
        if ($request->has('website_logo')) {
            Helpers::delete_file(Setting::find(1)->website_logo);
            Setting::find(1)->update([
                "website_logo" => Helpers::upload_files($request->website_logo),
            ]);
        }
        Setting::find(1)->update([
            'commission_value' => $request->commission,
            "commission_type" => $request->commission_type,
            "contact_email" => $request->contact_email,
            "phone" => $request->phone,
            "whatsapp_phone" => $request->whatsapp_phone,
            "down_payment" => $request->down_payment,
            "min_partial_payment" => $request->min_partial_payment,
            "cancel_within_hours" => $request->cancel_within_hours,
            "overview_time_payment" => $request->overview_time_payment,
            "overview_time" => $request->overview_time,
            "deduct_an_amount" => $request->deduct_an_amount,
            "refund_full_amount_within_hours" => $request->refund_full_amount_within_hours,
        ]);
        session()->flash('success', __('messages.Updated_successfully'));
        return back();
    }
    public function withdrawal_requests(Request $request)
    {
        $withdraws = WithdrawEarning::orderBy("id", "DESC")->with(['user'])->get();
        return view('admin.settings.withdrawal_requests')
            ->with("withdraws", $withdraws);
    }

    public function withdraw_details($withdraw_id)
    {
        $withdraw = WithdrawEarning::where("id", $withdraw_id)->orderBy("id", "DESC")->with(['user', 'admin', 'payment_method'])->first();
        $created_at = Carbon::parse($withdraw->created_at)->addHour();
        return view('admin.settings.withdraw_details')
            ->with('withdraw', $withdraw)
            ->with('created_at', $created_at);
    }
    public function change_withdraw_status($withdraw_id, Request $request)
    {
        $request->validate([
            "status" => "required",
        ]);
        $withdraw = WithdrawEarning::find($withdraw_id);
        $provider = User::find($withdraw->user_id);
        if ($withdraw->status == 2) {
            if ($request->status == 1) {
                $request->validate([
                    "attachment" => "required",
                ]);
                if ($provider->blance < $withdraw->amount) {
                    session()->flash('error', __('messages.Your_balance_is_not_enough'));
                    return back();
                }
                $provider->update([
                    "blance" => $provider->blance - $withdraw->amount,
                ]);
                $withdraw->update([
                    'status' => $request->status,
                    "admin_id" => Auth::user()->id,
                    "admin_attachment" => Helpers::upload_files($request->attachment),
                ]);
            } else if ($request->status == 0) {
                $withdraw->update([
                    'status' => $request->status,
                    "admin_id" => Auth::user()->id,
                ]);
            }

            $fcms = FCM::where("user_id", $provider->id)->get();
            $notification_data = [];
            $notification_title_en = "Withdraw";
            $notification_title_ar = "سحب المال";
            $notification_description_en =  $withdraw->status == 1 ? 'Your request has been accepted' : "Your request has been rejected";
            $notification_description_ar = $withdraw->status == 1 ? 'تم قبول طلبكم' : "تم رفض طلبكم";


            $lang = User::find($provider->id)->lang;
            foreach ($fcms as $fcm) {
                $notification_data = [
                    "title" => $lang == 'en' ? $notification_title_en : $notification_title_ar,
                    "description" =>  $lang == 'en' ? $notification_description_en : $notification_description_ar,
                    'fcm' => $fcm->fcm_token,
                    'model_id' => $withdraw->id,
                    'model_type' => 3,
                    "fcm" => $fcm->fcm_token,
                ];
                Helpers::push_notification_owner($notification_data);
            }
            $notification_data["title_ar"] = $notification_title_ar;
            $notification_data["title_en"] = $notification_title_en;
            $notification_data["description_en"] = $notification_description_en;
            $notification_data["description_ar"] = $notification_description_ar;
            $notification_data['user_id'] = $provider->id;
            $notification_data['model_type'] = 3;
            $notification_data['model_id'] = $withdraw->id;
            Helpers::push_notification_list($notification_data);



            session()->flash("success", __('messages.Updated_successfully'));
            return back();
        }
        return back();
    }
    public function update_policy(Request $request)
    {
        $setting = Setting::find(1);
        if ($request->has('status')) {
            $status = true;
        } else {
            $status = false;
        }
        if ($request->section == 1) {
            $setting->update([
                "term" => $request->term,
                "term_ar" => $request->term_ar,
                'terms_status' => $status,
                "term_user" => $request->term_user,
                "term_ar_user" => $request->term_ar_user,
                'terms_status_user' => $request->has("status_user") ? true : false,
            ]);
        } elseif ($request->section == 2) {

            $setting->update([
                "refund_policy" => $request->refund_policy,
                "refund_policy_ar" => $request->refund_policy_ar,
                'refund_status' => $status,
                "refund_policy_user" => $request->refund_policy_user,
                "refund_policy_ar_user" => $request->refund_policy_ar_user,
                'refund_status_user' => $request->has("status_user") ? true : false,

            ]);
        } elseif ($request->section == 3) {
            $setting->update([
                "privacy_policy" => $request->privacy_policy,
                "privacy_policy_ar" => $request->privacy_policy_ar,
                'privacy_status' => $status,
                "privacy_policy_user" => $request->privacy_policy_user,
                "privacy_policy_ar_user" => $request->privacy_policy_ar_user,
                'privacy_status_user' => $request->has("status_user") ? true : false,

            ]);
        } elseif ($request->section == 4) {
            $setting->update([
                "about_us" => $request->about_us,
                "about_us_ar" => $request->about_us_ar,
                'point_equal_1_currency' => $status,

            ]);
        } elseif ($request->section == 8) {
            $setting->update([
                "minimum_point_required" => $request->minimum_point_required,
                "point_earn_on_each_booking" => $request->point_earn_on_each_booking,
                'point_equal_1_currency' => $request->point_equal_1_currency,

            ]);
        }

        session()->flash('success', __('updated_successfully'));
        return back();
    }
    public function add_social_media(Request $request)
    {
        $request->validate([
            "link" => "required",
            "image" => "required",
        ]);
        SocialMedia::create([
            "link" => $request->link,
            "image" => Helpers::upload_files($request->image),
            "status" => true,
        ]);
        session()->flash("success", __('messages.Added_successfully'));
        return back();
    }
    public function update_social_media(Request $request)
    {
        $request->validate([
            "social_id" => "required",
        ]);
        $social = SocialMedia::find($request->social_id);
        if ($request->hasFile('image')) {
            Helpers::delete_file($social->image);
            $social->update([
                "image" => Helpers::upload_files($request->image),
            ]);
        }
        $social->update([
            "link" => $request->link,
        ]);
        session()->flash("success", __('messages.Updated_successfully'));
        return back();
    }
    public function change_social_status($social_id)
    {

        $social = SocialMedia::find($social_id);
        if (!$social)
            return  back();
        $social->update([
            "status" => $social->status ? false : true,
        ]);
        session()->flash("success", __('messages.Updated_successfully'));
        return back();
    }
    public function delete_social_media($social_id)
    {

        $social = SocialMedia::find($social_id);
        if (!$social)
            return  back();
        Helpers::delete_file($social->image);
        $social->delete();
        session()->flash("success", __('messages.Deleted_successfully'));
        return back();
    }
    public function add_slider(Request $request)
    {
        $request->validate([
            "text" => "required",
            "text_ar" => "required",
            "image" => "required",
        ]);
        Sliders::create([
            "text" => $request->text,
            "text_ar" => $request->text_ar,

            "image" => Helpers::upload_files($request->image),
        ]);
        Helpers::cacheSliders();

        session()->flash("success", __('messages.Added_successfully'));
        return back();
    }
    public function update_slider(Request $request)
    {
        $request->validate([
            "slider_id" => "required",
        ]);
        $slider = Sliders::find($request->slider_id);
        if ($request->hasFile('image')) {
            Helpers::delete_file($slider->image);
            $slider->update([
                "image" => Helpers::upload_files($request->image),
            ]);
        }
        $slider->update([
            "text" => $request->text,
            "text_ar" => $request->text_ar,
        ]);
        Helpers::cacheSliders();

        session()->flash("success", __('messages.Updated_successfully'));
        return back();
    }

    public function delete_slider($slider_id)
    {

        $slider = Sliders::find($slider_id);
        if (!$slider)
            return  back();
        Helpers::delete_file($slider->image);
        $slider->delete();
        Helpers::cacheSliders();
        session()->flash("success", __('messages.Deleted_successfully'));
        return back();
    }


    public function add_property_type(Request $request)
    {
        $request->validate([
            "title" => "required",
            "title_ar" => "required",
        ]);
        PropertyTypes::create([
            "title" => $request->title,
            "title_ar" => $request->title_ar,
        ]);
        session()->flash("success", __('messages.Added_successfully'));
        return back();
    }
    public function update_property_type(Request $request)
    {
        $request->validate([
            "id" => "required",
        ]);
        $type = PropertyTypes::find($request->id);

        $type->update([
            "title" => $request->title,
            "title_ar" => $request->title_ar,
        ]);
        session()->flash("success", __('messages.Updated_successfully'));
        return back();
    }

    public function delete_property_type($id)
    {

        $type = PropertyTypes::find($id);
        if (!$type)
            return  back();
        $type->delete();
        session()->flash("success", __('messages.Deleted_successfully'));
        return back();
    }
}
