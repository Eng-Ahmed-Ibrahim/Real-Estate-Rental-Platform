<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Customer\AddServiceReviewRequest;
use App\Models\Service;
use App\Models\Service_reviews;

class ServiceReviewsController extends Controller
{
    public function store(AddServiceReviewRequest $request){

        $user_id =  auth('sanctum')->user()->id;
        $service= Service::find($request->service_id);
        $old=Service_reviews::where('service_id', $service->id)->where('user_id', $user_id)->first();
        if($old){
            $old->rating = $request->rating;
            $old->review = $request->review;
            $old->save();
            return response(['message' =>"Review Updated Successfully"]);
        }else{
            $review= new Service_reviews();
            $review->user_id= $user_id;
            $review->provider_id= $service->user_id;
            $review->service_id= $request->service_id;
            $review->brand_id= $service->car_id;
            $review->rating= $request->rating;
            $review->review= $request->review;
            $review->save();
            return response(['message' =>"Review Added Successfully"]);
        }
        
    }
}
