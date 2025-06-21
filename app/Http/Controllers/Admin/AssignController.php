<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignUsers;
use App\Models\User;
use App\Notifications\PushNotification;

class AssignController extends Controller
{
    public function index(Request $request){
        $providers=User::where("power","provider")->orderBy("id","DESC")->get();
        $employees=User::whereNotIn("power",['admin','provider','customer'])->orderBy("id","DESC")->get();
        $query=AssignUsers::query();
        if($request->has('provider_id'))
            $query->where('provider_id',$request->provider_id);
        if(auth()->user()->power != "admin")
            $query->where("employee_id",auth()->user()->id);
        $assings=$query->orderBy("id","DESC")->with(['employee','provider'])->paginate(15);
        return view('admin.users.assign_users')
        ->with("providers",$providers)
        ->with("employees",$employees)
        ->with("assings",$assings)
        ;
    }
    public function  store(Request $request)  {
        $request->validate([
            "employee_id"=>"required",
            "provider_id"=>"required",
        ]);
        $check=AssignUsers::where("provider_id",$request->provider_id)
        ->where("employee_id",$request->employee_id)->exists();
        if($check){
            session()->flash("error",__('messages.Already_assigned'));
            return back();
        }
        AssignUsers::create([
            "employee_id"=>$request->employee_id,
            "provider_id"=>$request->provider_id,
        ]);
        $user=User::find($request->employee_id);
        $data=[
            "title_en"=>__('messages.A_new_assign',[],'en'),
            "title_ar"=>__('messages.A_new_assign',[],'ar'),
            "body_en"=>__('messages.Add_assign',['admin'=>auth()->user()->name],'en'),
            "body_ar"=>__('messages.Add_assign',['admin'=>auth()->user()->name],'ar'),
            "url"=>route('admin.profile',$request->provider_id),
        ];
        $user->notify(new PushNotification($data));
        session()->flash("success",__("messages.Added_successfully"));
        return back();
        
    }
    public function delete(Request $request){
        $request->validate([
            "assign_id"=>"required",

        ]);
        $check=AssignUsers::where("id",$request->assign_id)->first();
        if(!$check){
            return back();
        }
        $check->delete();
        session()->flash("success",__("messages.Deleted_successfully"));
        return back();
    }
}
