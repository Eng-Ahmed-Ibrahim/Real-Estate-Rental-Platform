<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Support;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    use ResponseTrait;
    public function store(Request $request){
        $validator=Validator::make($request->all(),[
            "subject"=>"required",
            "message"=>"required",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",422);
        $support=Support::create([
            "user_id"=>$request->user()->id,
            "subject"=>$request->subject,
            "message"=>$request->message,
        ]);
        return $this->Response($support,"Sent Successfully",201);

    }
}
