<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Support;
use Illuminate\Http\Request;
use App\Models\WithdrawEarning;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Validator;

class WithdrawalController extends Controller
{
    use ResponseTrait;

    public function wallet(Request $request){
        $blance=$request->user()->blance;
        $withdraw_pending=WithdrawEarning::where("user_id",$request->user()->id)->where("status",2)->sum('amount');
        $data=[
            "blance"=>$blance,
            "withdraw_pending"=>$withdraw_pending,
        ];

        return $this->Response($data,__("messages.Wallet"),201);
    }
    public function withdrawal(Request $request){


        $query = WithdrawEarning::query();
        if($request->has('status'))
            $query->where("status",$request->status);
        $withdraws=$query->where("user_id",$request->user()->id)->orderBy("id", "DESC")->get();
        


        return $this->Response($withdraws,"withdrawal Requests",201);
    }
    public function withdraw_details(Request $request)
    {
        $withdraw = WithdrawEarning::where("id", $request->withdraw_id)->with(['user', 'admin', 'payment_method'])->first();
        $created_at = Carbon::parse($withdraw->created_at)->addHour();

        return $this->Response($withdraw,"withdrawal details",201);
    }
    public function sent_withdrawal(Request $request){


        $validator=Validator::make($request->all(),[
            "password"=>"required",
            "account_number"=>"required",
            "payment_method_id"=>"required",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",422);
        $user = User::find($request->user()->id);


        if ($user && Hash::check($request->password, $user->password)) {
            if($request->amount > $user->blance){
                return $this->Response(null,__("messages.Your_balance_is_not_enough"),401);
            }
            
            WithdrawEarning::create([
                "user_id"=>$user->id,
                "amount"=>$request->amount,
                "account_number"=>$request->account_number,
                "payment_method_id"=>$request->payment_method_id,
            ]);

            $admins=User::where("power","admin")->get();
            foreach($admins as $admin){

                $user=User::find($admin->id);
                $data=[
                    "title_en"=>"Withdraw money ",
                    "title_ar"=>"سحب اموال",
                    "body_en"=>"There is a new request to withdraw funds, please check",
                    "body_ar"=>"يوجد طلب جديد لسحب اموال برجاء فحص ",
                    "url"=>route('admin.settings.withdrawal_requests'),
                ];
                $user->notify(new PushNotification($data));
            }
            return $this->Response(null,__("messages.Your_request_has_been_sent"),201);

        }else{
            return $this->Response(null,__("messages.Password_do_not_match"),401);

        }
    }
}
