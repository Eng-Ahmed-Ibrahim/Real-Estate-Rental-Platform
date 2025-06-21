<?php

namespace App\Http\Controllers\API;

use App\Models\Service;
use App\Models\Setting;
use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    use ResponseTrait;
    public function index(Request $request){
        $favorites=Favorite::where("user_id",$request->user()->id)->orderBy("id","DESC")->with(['service'])->get();
        $contact_details=Setting::find(1);
        $data=[
            "favorites"=>$favorites,
            "contact_details"=>[
                "owner_name"=>$contact_details->owner_name,
                "phone"=>$contact_details->phone,
            ],
        ];
        return $this->Response($data,"Favorites",201);
    }
    public function save(Request $request){
        $validator=Validator::make($request->all(),[
            "service_id"=>"required",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",422);

        $service=Service::find($request->service_id);
        if(!$service)
            return $this->Response(null,"Not Found",404);
        $check=Favorite::where("service_id",$request->service_id)->where("user_id",$request->user()->id)->first();
        if($check){
            $check->delete();
            return $this->Response($check," Removed ",401);
        }
        $favorite=Favorite::create([
            "service_id"=>$request->service_id,
            "user_id"=>$request->user()->id,
        ]);
        return $this->Response($favorite,"Added Successfully",201);
    }
    public function delete(Request $request){
        $validator=Validator::make($request->all(),[
            "service_id"=>"required",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",422);
        $check=Favorite::where("user_id",$request->user()->id)->where("service_id",$request->service_id)->first();
        if(!$check)
            return $this->Response(null,"Not Allowed",405 );
        $check->delete();
        return $this->Response(null,"Deleted Successfully",201);
    }
}



