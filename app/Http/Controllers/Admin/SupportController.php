<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Support;

class SupportController extends Controller
{
    public function index(Request $request){
        $query=Support::query();
        if($request->has('seen'))
            $query->where("seen",$request->seen);

        $suports=$query->orderBy("id","DESC")->with(['user'])->get();

        $seen_count=Support::where("seen",1)->count();
        $not_seen_count=Support::where("seen",0)->count();
        return view('admin.support.index')
        ->with("supports",$suports)
        ->with("seen_count",$seen_count)
        ->with("not_seen_count",$not_seen_count)
        ;
    }
    public function show($suport_id){
        $suport=Support::where("id",$suport_id)->with(['user'])->first();
        if($suport){
            $suport->update([
                "seen"=>1,
            ]);
            return view('admin.support.show')->with('support',$suport);
        }
        return back();
    }

}
