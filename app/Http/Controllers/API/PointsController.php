<?php

namespace App\Http\Controllers\API;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PointsController extends Controller
{
    use ResponseTrait;

    public function points (){
        $user = auth()->user();
        $point_equal_1_currency = Setting::find(1)->point_equal_1_currency;
        $data = [
            "current_points" => $user->points,
            "current_balance"=>$user->blance,
            "point_equal_1_currency" => $point_equal_1_currency,
            "minimum_point_required" => Setting::find(1)->minimum_point_required,
        ];
        return $this->Response($data, 'Points retrieved successfully', 200);
    }
    public function convert_to_balance(Request $request){
        $validator=Validator::make($request->all(),[
            "convert_points"=>"required",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",422);
        $user = auth()->user();
        $convert_points = $request->convert_points;
        $points = $user->points;
        $balance = $user->blance;
        $minimumPoints = Setting::find(1)->minimum_point_required;

        if($convert_points > $points){
            return $this->Response(null, 'You do not have enough points to convert to balance', 422);
        }
        if ($points < $minimumPoints) {
            $message = "You must have at least $minimumPoints points.";
            return $this->Response(null, $message, 400);
        }
        
        $point_equal_1_currency = Setting::find(1)->point_equal_1_currency;
        $user->points = $points - $convert_points;
        $user->blance = $balance + ($convert_points/$point_equal_1_currency);
        $user->save();
        return $this->Response(null, 'Points converted to balance successfully', 200);
    }
}
