<?php

namespace App\Http\Controllers\API;

use App\Models\FCM;
use App\CPU\Helpers;
use App\Models\User;
use App\Models\Rooms;
use App\Models\Messages;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    use ResponseTrait;

    public function getAllRooms(Request $request){
        $rooms=Rooms::where("user_one",$request->user()->id)
            ->orWhere("user_two",$request->user()->id)->with(["userOne","userTwo"])->get();
        $data=[
            "rooms"=>$rooms,
        ];
        return $this->Response($data,"Get All Rooms",201);
    }
    public function messages(Request $request){

        
        $room = Rooms::where(function($query) use ($request) {
            $query->where('user_one', $request->to_user)
                  ->where('user_two', $request->user()->id);
        })->orWhere(function($query) use ($request) {
            $query->where('user_two', $request->to_user)
                  ->where('user_one', $request->user()->id);
        })->first() ?? Rooms::find($request->room_id);
        if(! $room && ! $request->has("to_user"))
            return $this->Response(null,"Not Found User",422);
        if (!$room) {
            $room = Rooms::create([
                "user_one" => $request->user()->id,
                "user_two" => $request->to_user,
            ]);
        }else{
            if($room->user_one == $request->user()->id){
                $room->update([

                    "unread_one"=>0,
                ]);
            }else{
                $room->update([
                    "unread_two"=>0,

                ]);
            }
        }
        if($room->user_one == $request->user()->id){
            $user=User::find($room->user_two);
        }else{
            $user=User::find($room->user_one);
        }
        
        $messages=Messages::where("room_id",$room->id)->with(["user_sender"])->get();


        $data=[
            "messages"=>$messages,
            "room_id"=>$room->id,
            "user"=>$user,
        ];
        return $this->Response($data," ",201);
    }
    public function StoreMessage(Request $request){
        $validator=Validator::make($request->all(),[
            "room_id"=>"required",
            "message"=>"required",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",422);
        $room=Rooms::find($request->room_id);
        if(! $room)
            return $this->Response(null,"Room Not Found ",422);
        $room->update([
            "last_message"=>$request->message,
        ]);
        $message=Messages::create([
            "message"=>$request->message,
            "user_sender"=>$request->user()->id,
            "date"=>date("d D"),
            "time"=>date("d D"),
            "room_id"=>$request->room_id,
            "reply_to"=>(isset($request->reply) ? $request->reply : null)
        ]);
        broadcast(new MessageSent($message))->toOthers();

        if($room->user_one == $request->user()->id){
            $room->update([
                "unread_two"=>$room->unread_two +1,
            ]);
        }else{
            $room->update([
                "unread_one"=>$room->unread_one +1,
            ]);
        }

        $user=$room->user_one == $request->user()->id ? User::find($room->user_two) : User::find($room->user_one);
        $fcms = FCM::where("user_id", $user->id)->get();
        $notification_data = [];
        $notification_title_en = "New Message";
        $notification_title_ar ="رساله جديده";
        $notification_description= $request->message;

        $lang = $user->lang;
        foreach ($fcms as $fcm) {
            $notification_data = [
                "title" => $lang == 'en' ? $notification_title_en : $notification_title_ar,
                "description" =>  $notification_description,
                'fcm' => $fcm->fcm_token,
                'model_id' => $room->id,
                'model_type' => 11,
                "fcm" => $fcm->fcm_token,
            ];
            if($user->power=="provider")
                Helpers::push_notification_owner($notification_data);
            else 
                Helpers::push_notification_user($notification_data);
        }

        $messages=Messages::where("room_id",$request->room_id)->with(["user_sender"])->get();

        $data=[
            "messages"=>$messages,
            "room_id"=>$room->id,
        ];
        return $this->Response($data," ",201);
    }
    public function DeleteMessage(Request $request){
        Messages::destroy($request->message_id);

        $messages=Messages::where("room_id",$request->room_id)->with(["user_sender"])->get();
        $rooms=Rooms::where("user_one",$request->user()->id)
        ->orWhere("user_two",$request->user()->id)->with(["userOne","userTwo"])->get();
        $data=[
            "rooms"=>$rooms,
            "messages"=>$messages,
        ];
        return $this->Response($data,"message Deleted Successfully  ",201);
        
    }
    public function UpdateMessage(Request $request){
        Messages::find($request->message_id)->update([
            "message"=>$request->new_message,
        ]);
        $messages=Messages::where("room_id",$request->room_id)->with(["user_sender"])->get();
        $rooms=Rooms::where("user_one",$request->user()->id)
        ->orWhere("user_two",$request->user()->id)->with(["userOne","userTwo"])->get();
        $data=[
            "rooms"=>$rooms,
            "messages"=>$messages,
        ];
        return $this->Response($data,"message Deleted Successfully  ",201);
    }
}
