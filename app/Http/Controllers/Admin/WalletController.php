<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Transactions;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\ResponseTrait;

class TransactionsController extends Controller
{

    public function index(Request $request){
        $transactions=Transactions::orderBy("id","DESC")->with(['payment'])->get();
        return view('transactions.index')
        ->with("transactions",$transactions)
        ;
    }
    public function change_status(Request $request){
        $request->validate([
            "status"=>"required",
            "transaction_id"=>"required",
        ]);
        $transaction=Transactions::find($request->transaction_id);
        if(!$transaction)
            return back();
        if($transaction->status == 1){

            session()->flash("error",__("messages.status_cannot_changed"));
            return back();
        }
        $transaction->update([
            "status"=>$request->status,
        ]);
        if($request->status == 1){
            $user=User::find($transaction->user_id);
            $user->update([
                "blance"=>$user->blance + $transaction->amount	,
            ]);
        
        }
        session()->flash("success",__('messages.status_updated_succesfully'));
        return back();
    }
}
