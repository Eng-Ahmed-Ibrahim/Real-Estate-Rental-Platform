<?php

namespace App\Http\Controllers\API;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\ServiceReviews;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ReviewsController extends Controller
{
    use ResponseTrait;
    public function add_review(Request $request){
        $validator=Validator::make($request->all(),[
            "rating"=>"required",
            "review"=>"required",
            "service_id"=>"required",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",422);
        $service=Service::find($request->service_id);

        $check_bookings=Booking::where("service_id",$service->id)->where("customer_id",$request->user()->id)->where("booking_status_id",3)->where("payment_status_id",3)->count();
        if($check_bookings==0){
            return $this->Response(null,"You have not booked this property. You cannot rate it.",422);
        }
        // $check=ServiceReviews::where("user_id",$request->user()->id)->where("service_id",$service->id)->first();

        // if($check){
        //     $check->update([
        //         "rating"=>$request->rating,
        //         "review"=>$request->review,
        //     ]);
        //     return $this->Response($check,__('messages.Updated_successfully'),201);

        // }else{

            $review=ServiceReviews::create([
                "user_id"=>$request->user()->id,
                "provider_id"=>$service->user_id,
                "service_id"=>$service->id,
                "brand_id"=>$service->category_id,
                "rating"=>$request->rating,
                "review"=>$request->review,
    
            ]);
        return $this->Response($review,__('messages.Added_successfully'),201);
        // }

        
    }
    public function reviews(Request $request){
        $query=ServiceReviews::query();
        if($request->filled('service_id'))
            $query->where("service_id",$request->service_id);
        $reviews=$query
        ->orderBy("id","DESC")
        ->with(['user','service','category'])
        ->get();
        $total_rate=0;
        foreach($reviews as $review){
            $total_rate +=$review->rating;
        }
        if(count($reviews)>0){
            $rating=($total_rate/count($reviews));
        }else{
            $rating=0;
        }
        $data=[
            "rating"=>$rating,
            "reviews"=>$reviews,
        ];
        return $this->Response($data,"Reviews",201);
    }
}
