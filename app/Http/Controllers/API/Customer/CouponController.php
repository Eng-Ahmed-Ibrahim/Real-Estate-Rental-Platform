<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function check_coupon(Request $request){
        $my_time = \Carbon::now();
        $now= $my_time->toDateTimeString();

        $coupon= Coupon::WhereDate('start_at' , "<=", $now)->WhereDate('end_at' , ">=", $now)->where('coupon_code', $request->coupon_code)->select('id','coupon_code','coupon_value','type')->first();
        if($coupon){
            return response([
                "message"=> 'Coupon Code Is Correct',
                "status" => 200,
                'coupon' => $coupon
            ]);
        }else{
            return response([
                "message"=> 'Coupon Code Not Found',
                "status" => 404
            ]);
        }
    }
}
