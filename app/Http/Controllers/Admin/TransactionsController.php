<?php

namespace App\Http\Controllers\Admin;

use App\Models\FCM;
use App\CPU\Helpers;
use App\Models\User;

use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class TransactionsController extends Controller
{

    public function index(Request $request)
    {
        $transactions = Transactions::orderBy("id", "DESC")->with(['payment', 'user'])->get();
        return view('admin.transactions.index')
            ->with("transactions", $transactions);
    }
    public function change_status(Request $request)
    {
        $request->validate([
            "status" => "required",
            "transaction_id" => "required",
        ]);
        $transaction = Transactions::find($request->transaction_id);
        $user = User::find($transaction->user_id);
        if (!$transaction || ! in_array($request->status, [1, 0, 2]))
            return back();

        if ($transaction->status == 1) {
            session()->flash("error", __("messages.status_cannot_changed"));
            return back();
        }
        $transaction->update([
            "status" => $request->status,
        ]);
        if ($request->status == 1) {
            $user->update([
                "blance" => $user->blance + $transaction->amount,
            ]);
        }


        $fcms = FCM::where("user_id", $transaction->user_id)->get();
        $lang = $user->lang;
        $notification_data = array();
        $notification_title=$notification_description="";
        if ($request->status == 1) {
            $notification_title = $lang == "en" ? "Deposit accepted" : "تم قبول الايداع";
            $notification_description = $lang == "en" ?  "Accepted by the admin. The amount has been deposited into your account." : "تم قبول من قبل الادمن تم ايداع المبلغ في حسابك";
        } elseif($request->status == 0) {
            $notification_title = $lang == "en" ? "Deposit rejected" : "تم رفض الايداع";
            $notification_description = $lang == "en" ? "It was rejected by the admin. Please check again." : "تم رفض من قبل الادمن الرجاء مراجعه مره اخري";
        }elseif($request->status == 2) {
            $notification_title = $lang == "en" ? "Deposit pending" : "تم تعليق الايداع";
            $notification_description = $lang == "en" ? "The deposit is pending review by the admin." : "الاييداع في انتظار المراجعه من قبل الادمن";
        }
        // 

        foreach ($fcms as $fcm) {
            $notification_data = [
                "title" => $notification_title,
                "description" => $notification_description,
                'fcm' => $fcm->fcm_token,
                'model_id' => $transaction->id,
                'model_type' => 20,
            ];
            if($user->power=="customer")
                Helpers::push_notification_user($notification_data);
            else 
                Helpers::push_notification_owner($notification_data);
        }
        if ($request->status == 1) {
            $notification_data["title_en"] = "Deposit accepted";
            $notification_data["title_ar"] = "تم قبول الايداع";
            $notification_data["description_en"] = "Accepted by the admin. The amount has been deposited into your account.";
            $notification_data["description_ar"] = "تم قبول من قبل الادمن تم ايداع المبلغ في حسابك";
        } else {
            $notification_data["title_en"] = "Deposit rejected";
            $notification_data["title_ar"] =  "تم رفض الايداع";
            $notification_data["description_en"] = "It was rejected by the admin. Please check again.";
            $notification_data["description_ar"] =  "تم رفض من قبل الادمن الرجاء مراجعه مره اخري";
        }
        $notification_data['model_type'] = 20;
        $notification_data['model_id'] = $transaction->id;


        $notification_data['user_id'] = $transaction->user_id;
        Helpers::push_notification_list($notification_data);

        session()->flash("success", __('messages.status_updated_succesfully'));
        return back();
    }
}
