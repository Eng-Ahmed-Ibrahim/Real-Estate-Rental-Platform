<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Commission;
use App\Models\PaymentMethod;

class PaymentMethodsController extends Controller
{
    public function index(){
        $methods=PaymentMethod::orderBy("id","DESC")->get();
        return view('admin.payment.methods')
        ->with("methods",$methods)
        ;
    }
    public function store(Request $request){
        $request->validate([
            "name_ar"=>'required',
            "name_en"=>'required',
            "account"=>'required',
            "image"=>"required"
        ]
        ,[
            "name_ar.required"=>__('messages.Validate_name_ar'),
            "name_en.required"=>__('messages.Validate_name_en'),
            "image.required"=>__('messages.Validate_image'),
            "account.required"=>__('messages.Validate_account'),
        ]);
        PaymentMethod::create([
            "name_ar"=>$request->name_ar,
            "name"=>$request->name_en,
            "account"=>$request->account,
            "status"=>1,
            "image"=>Helpers::upload_files($request->image),

        ]);
        session()->flash("success",__('messages.Added_successfully'));
        return back();

    }
    public function update(Request $request){
        $request->validate([
            "id"=>"required",
            "name_ar"=>'required',
            "name_en"=>'required',
            "account"=>'required',
        ]
        ,[
            "name_ar.required"=>__('messages.Validate_name_ar'),
            "name_en.required"=>__('messages.Validate_name_en'),
            "account.required"=>__('messages.Validate_account'),
        ]);
        $method=PaymentMethod::find($request->id);
        if($method){
            if($request->hasFile('image')){
                Helpers::delete_file($method->image);
                $method->update([
                    "image"=>Helpers::upload_files($request->image),
                ]);
            }
            $method->update([
                "name_ar"=>$request->name_ar,
                "name"=>$request->name_en,
                "account"=>$request->account,
            ]);
            session()->flash("success",__('messages.Updated_successfully'));
            return back();
        }else{
            return back();
        }

    }
    public function destroy(Request $request){
        $method=PaymentMethod::find($request->id);
        if($method){
            Helpers::delete_file($method->image);
            $method->delete();
            session()->flash("success",__("messages.Deleted_successfully"));
            return back();
        }else{
            return back();
        }
    }
    public function change_status($id){
        $method=PaymentMethod::find($id);
        if($method ){
            
            if($method->status == true)
                $method->update([
                    "status"=>false,
                ]);
            else 
                $method->update([
                    "status"=>true,
                ]);
            session()->flash("success",__("messages.Updated_successfully"));
            return back();
        }else{
            return back();
        }
    }
}
