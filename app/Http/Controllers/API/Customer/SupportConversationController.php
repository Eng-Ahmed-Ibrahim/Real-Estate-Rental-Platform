<?php

namespace App\Http\Controllers\API\Customer;

use App\Events\SendMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Customer\StoreSupportConversationRequest;
use App\Models\Conversation;
use Illuminate\Http\Request;

class SupportConversationController extends Controller
{
    public function index(){

        $customer_id =  auth('sanctum')->user()->id;

        $conversations= Conversation::select('message','send_by_user','created_at')->where('user_id',$customer_id)->orderBy('id','asc')->get();

        if(count($conversations) > 0){
            return response([
                "message"=> 'Messages retrieved successfully',
                "conversations" => $conversations,
                'code' => 200
            ]);
        }
        return response(["message"=> "Sorry, This Yser Don't Have Any Conversation", 'code' => 404]);

    }

    public function store(StoreSupportConversationRequest $request){

        $customer_id =  auth('sanctum')->user()->id;

        $conversation= new Conversation();
        $conversation->user_id= $customer_id;
        $conversation->admin_id= 1;
        $conversation->message= $request->message;
        $conversation->send_by_user= 1;
        $conversation->seen= 1;
        $conversation->save();


        if($conversation){

            //make Notification
            event(new SendMessage($conversation));

            return response([
                "message"=> 'Message Sent successfully',
                'code' => 200
            ]);
        }
        return response(["message"=> "Some Thing Error", 'code' => 404]);

    }



}
