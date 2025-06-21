<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Booking;

class ServiceController extends Controller
{
    public function index(Request $request){

        $service= Service::with(['dates','category' => function ($query) {
            $query->select('id', 'brand_name','brand_name_ar','image');
        }])
        ->where(['accept' => 1, 'available' => 1])
        ->where('b_from','<=',date("Y-m-d"))
        ->where('b_to','>=',date("Y-m-d"))
        ->get();

        return response([
            "message"=> 'Services retrieved successfully',
            "services" => $service
        ]);

     }
     
    


    public function show($id){

      $service= Service::with(['category','gallery','features','review','dates'])
        ->where(['id' => $id,'accept' => 1, 'available' => 1])->first();
        $bookings = Booking::select('id','start_at','end_at')->where('service_id',$service->id)->whereNotIn('booking_status_id',array(5,6))->get();
        if($service){
            return response([
                "message"=> 'Service retrieved successfully',
                "service" => $service,
                "bookings" => $bookings
            ]);
        }

        return response(["message"=> 'Service Not Found',]);

     }


     public function search(Request $request){
        $category_id= $request->category_id;
        $price_from= $request->price_from;
        $price_to= $request->price_to;
        $features= $request->features;

        $query = Service::query();

        $query->with(['category'])->where(['accept' => 1, 'available' => 1]);
        
        if ($request->has('category_id') && $request->category_id != null) {
            $query->where('category_id',$category_id);
        }

        if ($request->has('price_from') && $request->price_from != null) {
            $query->where('price','>=',$price_from);
        }

        if ($request->has('price_to') && $request->price_to != null) {
            $query->where('price','<=',$price_to);
        }

        if ($request->has('features') && $features != null) {
            $query
            ->whereHas('features', function ($query2) use ($features) {
                 $query2->whereIn('feature_name',$features);
            });
        }

        if($query){
            return response([
                "message"=> 'Service retrieved successfully',
                "service" => $query->get()
            ]);
        }

        return response(["message"=> 'Service Not Found',]);
     }
}
