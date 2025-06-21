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
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\WithdrawEarning;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WithdrawRequestsController extends Controller
{

    public function withdrawal_requests(Request $request)
    {
        $withdraws = WithdrawEarning::orderBy("id", "DESC")->with(['user'])->get();
        return view('admin.withdraw.withdrawal_requests')
            ->with("withdraws", $withdraws);
    }

    public function withdraw_details($withdraw_id)
    {
        $withdraw = WithdrawEarning::where("id", $withdraw_id)->orderBy("id", "DESC")->with(['user', 'admin', 'payment_method'])->first();
        $created_at = Carbon::parse($withdraw->created_at)->addHour();
        return view('admin.withdraw.withdraw_details')
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
    public function withdraw($user_id, Request $request)
    {
        $request->validate([
            "password" => "required",
            "account_number" => "required",
            "payment_method_id" => "required",
        ]);

        $user = User::find($user_id);


        if ($user && Hash::check($request->password, $user->password)) {
            if ($request->amount > $user->blance) {
                session()->flash("error", __("messages.Your_balance_is_not_enough"));
                return back();
            }
            WithdrawEarning::create([
                "user_id" => $user->id,
                "amount" => $request->amount,
                "account_number" => $request->account_number,
                "payment_method_id" => $request->payment_method_id,
            ]);
            session()->flash("success", __('messages.Your_request_has_been_sent'));
            return back();
        } else {
            session()->flash("error", __("messages.Password_do_not_match"));
            return back();
        }
    }
    public function deposit($user_id, Request $request)
    {
        $request->validate([
            "password" => "required",
            "amount" => "required",
        ]);
        $user = User::find($user_id);
        $admin = auth()->user();
        if ($user->power=="provider" && Hash::check($request->password, $admin->password)) {
            Transactions::create([
                "user_id"=>$user->id,
                "amount"=>$request->amount,
                "payment_method"=>$request->payment_method,
                "attachment" => null,
                "transaction_type"=>"deposit",
                "transaction_type_ar"=>"ايداع",
                "status"=>1,
                "description"=>"Deposit by $admin->name",
                "description_ar"=>"تم الإيداع بواسطة $admin->name",
                "admin_id"=>$admin->id
            ]);
            $user->update([
                "blance"=>$user->blance + $request->amount,
            ]);
            
            session()->flash("success", __('messages.Deposit_successfully'));
            return back();
        } else {
            session()->flash("error", __("messages.Password_do_not_match"));
            return back();
        }
    }
}
