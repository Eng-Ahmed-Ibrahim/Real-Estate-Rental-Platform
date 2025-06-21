<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignUsers;
use App\Models\Service;
use App\Models\ServiceReviews;
use App\Models\User;
use App\Notifications\PushNotification;

class ReviewsController extends Controller
{
    public function index(Request $request){
        $query=ServiceReviews::query();
        if($request->filled("service_id") && $request->service_id >0)
            $query->where("service_id",$request->service_id);
        $reviews=$query->with(["service",'user'])->orderBy("id","DESC")->paginate(15);
        $services= Service::orderBy("id","DESC")->get();
        return view('admin.services.reviews')
        ->with("reviews",$reviews)
        ->with("services",$services);
    }
    public function delete(Request $request ){
        $review=ServiceReviews::find($request->id);
        if(! $review)
            return back();
        $review->delete();
        session()->flash("success",__("messages.Deleted_successfully"));
        return back();
    }
}
