<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    use ResponseTrait;
    public function notifications(Request $request){
        $notifications = Notification::where("user_id",$request->user()->id)->orderBy("created_at","DESC")->get();
        $data=[];
        foreach($notifications as $notification){
            $data[]=[
                "body"=>$notification["body"],
                "body_ar"=>$notification["body_ar"],
                "title"=>$notification["title"],
                "title_ar"=>$notification["title_ar"],
                "model_id"=>$notification["model_id"],
                "model_type"=>$notification["model_type"],
                "seen"=>$notification->seen==0 ? 0 :1,
            ];
            $notification->update([
                'seen'=>1,
            ]);
        }
        return $this->Response($data,"Notifications",201);
    }
    public function num_un_seen_notification(Request $request){
        $notification_count = Notification::where("user_id",$request->user()->id)->where('seen',0)->count();
        return $this->Response($notification_count," ",201);

    }
}
