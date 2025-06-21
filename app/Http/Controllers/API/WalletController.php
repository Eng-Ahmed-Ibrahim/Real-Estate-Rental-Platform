<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Transactions;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\ResponseTrait;

class WalletController extends Controller
{
    use ResponseTrait;

    public function index(Request $request){
        $transactions=Transactions::where("user_id",$request->user()->id)->orderBy("id","DESC")->with(['payment'])->get();
        $data=[
            "transactions"=>$transactions,
        ];
        return $this->Response($data,"Transcations",201);
    }
    public function add_wallet(Request $request){
        $validator=Validator::make($request->all(),[
            "amount"=>"required",
            "payment_method"=>"required",
            'attachment' => 'required|file|mimes:jpg,png,pdf|max:10240', 
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",422);
        $sender_user=User::find($request->user()->id);
        if(! $sender_user )
            return $this->Response(null,"User Not Found",422);

        $name = "1" . time() . str_replace(" ", "_", $request->attachment->getClientOriginalName());
        $request->attachment->move(public_path("files/"), $name);
        Transactions::create([
            "user_id"=>$sender_user->id,
            "amount"=>$request->amount,
            "payment_method"=>$request->payment_method,
            "attachment" => "files/$name",
            "transaction_type"=>"deposit",
            "transaction_type_ar"=>"ايداع",
        ]);
        $admins=User::where("power","admin")->get();
        foreach($admins as $admin){

            $user=User::find($admin->id);
            $data=[
                "title_en"=>"deposit money",
                "title_ar"=>" ايداع اموال",
                "body_en"=>"$sender_user->name requested to deposit $request->amount in his wallet.",
                'body_ar' => __('messages.deposit_request', ['name' => $sender_user->name, 'amount' => $request->amount]),
                "url"=>route('admin.transcations.index'),
            ];
            $user->notify(new PushNotification($data));
        }
        return $this->Response(null,__('messages.Transcation_sent'),201);
    }
    public function balance(Request $request){
        $user= User::find($request->user()->id);
        $data=[
            "balance"=>$user->blance,
        ];
        return $this->Response($data,"balance ",201);
    }
}
