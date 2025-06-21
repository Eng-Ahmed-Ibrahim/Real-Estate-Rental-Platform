<?php

namespace App\Http\Controllers\Api\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\School;

class FavoriteController extends Controller
{
    public function index()
    {
        $service=Favorite::with('service')->where('user_id',auth()->user()->id)->get();

        return response([
            'error' => false,
            "services" => $service,
            "services_count" => $service->count(),
        ]);
    }

    public function add($id)
    {
        $is=Favorite::where('user_id',auth()->user()->id)->where('service_id',$id)->first();
        if($is){
            $is->delete();
            return response([
                'error' => false,
                "message" => trans('messages.deleted_successfully'),
            ]);
        }
        $cat = new Favorite();
        $cat->user_id = auth()->user()->id;
        $cat->service_id = $id;
        $cat->save();
        return response([
            'error' => false,
            "service" => $cat,
        ]);
    }

}
