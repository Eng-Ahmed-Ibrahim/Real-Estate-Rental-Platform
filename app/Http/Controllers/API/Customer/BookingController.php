<?php

namespace App\Http\Controllers\API\Customer;

use App\Events\BookingStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Customer\AddBookingRequest;
use App\Http\Requests\API\Customer\EditBookingRequest;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingServiceNotification;
use App\Models\Coupon;
use App\Models\Service;

class BookingController extends Controller
{



    public function index(){

        $customer_id =  auth('sanctum')->user()->id;

        $booking=Booking::with(['brand','service','booking_status','payment_status','provider'])->where('customer_id',$customer_id)->orderBy('id','DESC')->get();
        return response([
            "message"=> 'Data retrieved successfully',
            "bookings" => $booking
        ]);
    }

    public function store(AddBookingRequest $request){

        $customer_id =  auth('sanctum')->user()->id;
        $service_id =  $request->service_id;

        $service= Service::whereId($service_id)->first();

        $days = $request->days;

        $amount= $this->calculate_coupon($request->coupon_id,$service,$days);

        $commission= $service->commission_money * $days;

        if($service && $customer_id){
            $booking= new Booking();
            $booking->service_id= $service_id;
            $booking->brand_id= Service::whereId($service_id)->first()->category_id;
            $booking->amount= $amount - $commission;
            $booking->total_amount= $amount;
            $booking->taxes= $commission;
            $booking->customer_id= $customer_id;
            $booking->provider_id= $service->user_id;
            $booking->booking_status_id= 1;
            $booking->start_at= $request->start_at;
            $booking->end_at= $request->end_at;
            $booking->payment_status_id= 1;

            if($request->coupon_id){
                $booking->coupon_id= $request->coupon_id;
            }

            $booking->save();

            //Send Notification To Provider
            $booking['action_type'] = 'create';
            event(new BookingStatus($booking));
            
            $booking['action_type'] = 'createC';
            event(new BookingStatus($booking));

            return response([
                "message"=> 'Booking Added successfully'
            ]);
        }


        return response([
            "message"=> 'Service Not Found',
        ]);
    }
    
     public function myServices(){
        
        $customer_id =  auth('sanctum')->user()->id;
        $booking= Booking::with(['brand','service','booking_status','payment_status','provider'])->where('customer_id',$customer_id)->where('booking_status_id',4)->get();

        return response([
            "message"=> 'Services retrieved successfully',
            "bookings" => $booking
        ]);

     }

    public function update(EditBookingRequest $request,$booking){
        return $request;
        $booking= Booking::find($booking);
        $service= Service::find($booking->service_id);

        $days = $request->days;

        $amount= $this->calculate_coupon($request->coupon_id,$service,$days);

        $commission= $service->commission_money * $days;

        $booking->amount= $amount - $commission;
        $booking->total_amount= $amount;
        $booking->taxes= $commission;
        $booking->start_at= $request->start_at;
        $booking->end_at= $request->end_at;
        $booking->save();

        return response([
            "message"=> 'Booking Updated Successfully',
        ]);
    }

    public function booking_notification(){

        $customer_id =  auth('sanctum')->user()->id;

        $notification=BookingServiceNotification::select('booking_id','text','text_ar','created_at')->where(['user_id' => $customer_id,'send_to_user' => 1])->orderBy('id','DESC')->get();
        return response([
            "message"=> 'Notification retrieved successfully',
            "notification" => $notification
        ]);
    }

    public function destroy($id){
        $booking= Booking::find($id);
        if($booking && $booking->booking_status_id == 1){
            $booking->delete();
            return response(["message"=> 'Booking Deleted successfully']);
        }
        return response(["message"=> "Sorry You Can't Delete This Booking !"]);
    }
    
     public function show($id){
        $booking= Booking::with('service')->find($id);
        if($booking ){
            return response([
                "message"=> 'Booking retrieved successfully',
                "data"=>$booking
                ]);
        }
        return response(["message"=> "Sorry You Can't Delete This Booking !"]);
    }

    public function calculate_coupon($coupon_id,$service,$days){

        $coupon=Coupon::find($coupon_id);

        if($coupon && $coupon->type == 'amount' && $coupon->coupon_value < $service->price){

            $service_commission= $service->commission_money * $days;
            $service_price_after_coupon= $service->price * $days - $coupon->coupon_value * $days;

            return  $service_commission  + $service_price_after_coupon;
        }

        elseif($coupon && $coupon->type == 'percentage'){

            $first= 100 - $coupon->coupon_value ;
            $second= $first  / 100;
            $third= $second  * $service->price;
            $coupon_value= $service->price - $third;

            $service_commission= $service->commission_money * $days;
            $service_price_after_coupon= $service->price * $days - $coupon_value * $days;

           return $service_commission  + $service_price_after_coupon;

        }
        else{
            return $service->price_with_commission * $days;
        }
    }
}
