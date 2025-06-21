<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\FCM;
use App\CPU\Helpers;
use App\Models\User;
use App\Models\Booking;
use App\Models\Earning;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Packages;
use App\Models\Commission;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\ServiceReviews;
use App\Models\PackageFeatures;
use App\Models\WithdrawEarning;
use App\Models\PackageHasFeatures;
use App\Exports\SubscriptionExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
class PackagesController extends Controller
{
    public function index()
    {
        $packages = Packages::orderBy("id", "DESC")->with(["features"])->paginate(15);
        $features = PackageFeatures::orderBy("id", 'DESC')->get();
        return view('admin.packages.index')
            ->with("packages", $packages)
            ->with("features", $features)
        ;
    }
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "name_ar" => "required",
            "image" => "required",
            'service_limit' => 'required|numeric',
            'duration' => 'required|numeric',
            'price' => 'required|numeric',
            "features" => "required",
        ]);
        $package = Packages::create([
            "name" => $request->name,
            "name_ar" => $request->name_ar,
            "service_limit" => $request->service_limit,
            "duration" => $request->duration,
            "price" => $request->price,
            "image" => Helpers::upload_files($request->image),
            "verified" => $request->has('verified') ? true  : false,
        ]);
        foreach ($request->features as $feature) {
            PackageHasFeatures::create([
                "feature_id" => $feature,
                "package_id" => $package->id,
            ]);
        }
        Helpers::cachePackages();
        session()->flash("success", __('messages.Added_successfully'));
        return back();
    }
    public function update(Request $request)
    {
        $request->validate([
            "name" => "required",
            "name_ar" => "required",
            'service_limit' => 'required|numeric',
            'duration' => 'required|numeric',
            'price' => 'required|numeric',
            "package_id" => "required",
            "features" => "required",
        ]);
        $package = Packages::find($request->package_id);
        if (!$package) {
            session()->flash("error", __('messages.Not_found'));
            return  back();
        }
        if ($request->hasFile("image")) {
            Helpers::delete_file($package->image);
            $package->update([
                "image" => Helpers::upload_files($request->image),
            ]);
        }
        if ($request->has('verified'))
            $verified = true;
        else
            $verified = false;
        $package->update([
            "name" => $request->name,
            "name_ar" => $request->name_ar,
            "service_limit" => $request->service_limit,
            "duration" => $request->duration,
            "price" => $request->price,
            "verified" => $verified,

        ]);
        if ($request->has("features")) {
            $package->features()->delete();
            foreach ($request->features as $feature) {
                PackageHasFeatures::create([
                    "feature_id" => $feature,
                    "package_id" => $package->id,
                ]);
            }
        }
                Helpers::cachePackages();

        session()->flash("success", __('messages.Updated_successfully'));
        return back();
    }
    public function destroy(Request $request)
    {
        $request->validate([
            "package_id" => "required",
        ]);
        $package = Packages::find($request->package_id);
        if (!$package) {
            session()->flash("error", __('messages.Not_found'));
            return  back();
        }
        Helpers::delete_file($package->image);
        $package->delete();
        session()->flash("success", __('messages.Deleted_successfully'));
        return back();
    }
    public function features()
    {
        $features = PackageFeatures::orderBy("id", "DESC")->get();
        return view('admin.packages.features')
            ->with("features", $features);
    }
    public function add_feature(Request $request)
    {
        $request->validate([
            "feature" => "required",
            "feature_ar" => "required",
        ]);
        PackageFeatures::create([
            "feature" => $request->feature,
            "feature_ar" => $request->feature_ar,
        ]);
        session()->flash("success", __("messages.Added_successfully"));
        return back();
    }
    public function update_feature(Request $request)
    {
        $request->validate([
            "feature" => "required",
            "feature_ar" => "required",
            "feature_id" => "required",
        ]);

        PackageFeatures::find($request->feature_id)->update([
            "feature" => $request->feature,
            "feature_ar" => $request->feature_ar,
        ]);
        session()->flash("success", __("messages.Updated_successfully"));
        return back();
    }
    public function delete_feature(Request $request)
    {
        $request->validate([
            "feature_id" => "required",
        ]);

        PackageFeatures::find($request->feature_id)->delete();
        session()->flash("success", __("messages.Deleted_successfully"));
        return back();
    }
    public function subscribers(Request $request) 
    {
        $query = Subscription::orderBy("id", "DESC")
            ->with(['provider:id,name', 'package:id,name,name_ar']);
    
        // تطبيق الفلاتر
        if ($request->filled('name')) {
            $query->whereHas('provider', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }
    
        if ($request->filled('package_name')) {
            $query->whereHas('package', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->package_name . '%');
            });
        }
        if($request->filled("paid"))
            $query->where("paid",$request->paid);
        
        if($request->filled("status"))
            $query->where("status",$request->status);
    
        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->created_at);
        }
    
        if ($request->filled('from') && $request->filled('to')) {
            $startDate = Carbon::parse($request->from);
            $endDate = Carbon::parse($request->to);
            $query->whereBetween("created_at", [$startDate, $endDate]);
        }

        $subscribers = $query->paginate(15);
    
        return view('admin.packages.subscribers', compact('subscribers'));
    }
    
    public function change_subscriber_status(Request $request)
    {
        $request->validate([
            "id" => "required",
            "status" => "required",
        ]);
        $subscriber = Subscription::find($request->id);
        if (! $subscriber) {
            session()->flash('error', __('messages.Not_found'));
            return back();
        }
        if ($subscriber->paid == "pending") {
            $status = $request->status == 1 ? "paid" : "rejected";
            if ($status == "paid") {
                $subscribers = Subscription::where("provider_id", $subscriber->provider_id)->get();
                foreach ($subscribers as $subscribe) {
                    $subscribe->update(["status"=> 0]);
                }
            }
            $startSubscribe = now();
            $endSubscribe = $startSubscribe->copy()->addMonths($subscriber->package_duration);
            $startSubscribeFormatted = $startSubscribe->format('Y-m-d');
            $endSubscribeFormatted = $endSubscribe->format('Y-m-d');
            $subscriber->update([
                "paid" => $status,
                "start_subscribe" => $startSubscribeFormatted,
                "end_subscribe" => $endSubscribeFormatted,
                "status" => $status == "paid" ? 1 : 0,
            ]);


            // notification 
            $lang = User::find($subscriber->provider_id)->lang;
            $title_en =  $status=="paid" ?  "Package accepted" : "Package rejected" ;
            $title_ar = $status=="paid" ? "تم قبول باقه" : "تم رفض باقه";
            $describtion_en = $status=="paid" ? "The package has been accepted by the admin" : "تم قبول الباقه من  قبل الادمن ";
            $describtion_ar = $status=="paid" ? "تم قبول باقه من قبل الادمن ": "تم رفض الباقه من  قبل الادمن ";
            $fcms = FCM::where("user_id", $subscriber->provider_id)->get();
            foreach ($fcms as $fcm) {
                $notification_data = [
                    "title" => $lang == 'en' ? $title_en : $title_ar,
                    "description" => $lang == 'en' ? $describtion_en : $describtion_ar,
                    'fcm' => $fcm->fcm_token,
                    'model_id' => $subscribe->id,
                    'model_type' => 10,
                ];
                Helpers::push_notification_owner($notification_data);
            }
            $notification_data["title_ar"] = $title_ar;
            $notification_data["title_en"] = $title_en;
            $notification_data["description_en"] = $describtion_en;
            $notification_data["description_ar"] = $describtion_ar;
            $notification_data['user_id'] = $subscriber->provider_id;
            $notification_data['model_type'] = 10;
            $notification_data['model_id'] = $subscribe->id;

            Helpers::push_notification_list($notification_data);

            session()->flash("success", __("messages.Updated_successfully"));
            return back();
        }
        session()->flash("error", __("messages.Already_changed"));
        return back();
    }
    public function export(Request $request)
    {
        return Excel::download(new SubscriptionExport($request->all()), 'subscribers.xlsx');
    }

}
