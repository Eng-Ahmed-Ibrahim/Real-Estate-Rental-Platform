<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Commission;

class CommissionController extends Controller
{
    public function index(){
        $commissions=Commission::orderBy("id","DESC")->get();
        return view('admin.commission.index')
        ->with("commissions",$commissions)
        ;
    }
    public function store(Request $request){
        $request->validate([
            "name_ar"=>'required',
            "name_en"=>'required',
            "value"=>'required',
        ]
        ,[
            "name_ar.required"=>__('messages.Validate_name_ar'),
            "name_en.required"=>__('messages.Validate_name_en'),
            "value.required"=>__('messages.Validate_commission_value'),
        ]);
        Commission::create([
            "commission_name_ar"=>$request->name_ar,
            "commission_name"=>$request->name_en,
            "commission_value"=>$request->value
        ]);
        session()->flash("success",__('messages.Added_successfully'));
        return back();

    }
    public function update(Request $request){
        $request->validate([
            "id"=>'required',
            "name_ar"=>'required',
            "name_en"=>'required',
            "value"=>'required',
        ],[
            "name_ar.required"=>__('messages.Validate_name_ar'),
            "name_en.required"=>__('messages.Validate_name_en'),
            "value.required"=>__('messages.Validate_commission_value'),
        ]);
        $commission=Commission::find($request->id);
        if($commission){
            $commission->update([
                "commission_name_ar"=>$request->name_ar,
                "commission_name"=>$request->name_en,
                "commission_value"=>$request->value,
            ]);
            session()->flash("success",__('messages.Updated_successfully'));
            return back();
        }else{
            return back();
        }

    }
    public function destroy(Request $request){
        $commission=Commission::find($request->id);
        if($commission){
            $commission->delete();
            session()->flash("success",__("messages.Deleted_successfully"));
            return back();
        }else{
            return back();
        }
    }
}
