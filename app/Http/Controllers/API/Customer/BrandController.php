<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\BodyType;
use App\Models\Brand;
use App\Models\SubBrand;
use App\Models\Feature;
use App\Models\Service;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(){

        $brands= Brand::all();
        $features= Feature::all();
        $max_price= Service::max('price');
        $max_bed= Service::max('bed');
        $max_bath= Service::max('bath');
        $max_floor= Service::max('floor');

        return response([
            "message"=> 'Data retrieved successfully',
            "brands" => $brands,
            "features" => $features,
            "max_price" => $max_price,
            "max_bed" => $max_bed,
            "max_bath" => $max_bath,
            "max_floor" => $max_floor,
        ]);

    }
}
