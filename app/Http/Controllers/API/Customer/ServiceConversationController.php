<?php

namespace App\Http\Controllers\API\Customer;

use App\Events\SendMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Customer\GetServiceConversationRequest;
use App\Http\Requests\API\Customer\StoreServiceConversationRequest;
use Illuminate\Http\Request;
use App\Models\ServiceConversation;

class ServiceConversationController extends Controller
{
    public function index(){

        $customer_id =  auth('sanctum')->user()->id;


        $all_conversations= ServiceConversation::select('id','provider_id','created_at')->where(['user_id' => $customer_id])->with('provider')->orderBy('id','desc')->get()->unique('provider_id');

        if(count($all_conversations) > 0){
            return response([
                "message"=> 'Messages retrieved successfully',
                "all_conversations" => $all_conversations,
                'code' => 200
            ]);
        }
        return response(["message"=> "Sorry, This User Don't Have Any Conversation", 'code' => 404]);

    }


    public function show(GetServiceConversationRequest $request){

        $customer_id =  auth('sanctum')->user()->id;
        $provider_id = $request->provider_id;

        $conversations= ServiceConversation::select('message','send_by_customer','created_at')->where(['user_id' => $customer_id,'provider_id' => $provider_id])->orderBy('id','asc')->get();

        if(count($conversations) > 0){
            return response([
                "message"=> 'Messages retrieved successfully',
                "conversations" => $conversations,
                'code' => 200
            ]);
        }
        return response(["message"=> "Sorry, This Yser Don't Have Any Conversation", 'code' => 404]);

    }

    public function store(StoreServiceConversationRequest $request){



        $customer_id =  auth('sanctum')->user()->id;

        $conversation= new ServiceConversation();
        $conversation->user_id= $customer_id;
        $conversation->provider_id= $request->provider_id;
        $conversation->message= $request->message;
        $conversation->send_by_customer= 1;
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
