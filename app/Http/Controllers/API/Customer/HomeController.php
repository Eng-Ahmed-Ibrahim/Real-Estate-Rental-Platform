<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Term;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request){

       // $header= $request->header('lang');

            $service= Service::with(['review','category' => function ($query) {
                $query->select('id', 'brand_name','brand_name_ar','image');
            }])
            ->where(['accept' => 1, 'available' => 1])
            ->where('b_from','<=',date("Y-m-d"))
            ->where('b_to','>=',date("Y-m-d"))
            ->take(5)->get();
            $phone= Term::find(2)->title;
            return response([
                "message"=> 'Data retrieved successfully',
                "services" => $service,
                "phone" => $phone
            ]);



    }
}
