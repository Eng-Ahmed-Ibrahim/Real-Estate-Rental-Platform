<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logs;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "provider_id"=>"required",
            "notes"=>"required",
            "communicate_by"=>"required",
            "subject"=>"required",
        ]);
        if($request->filled('employee_id'))
            $employee_id=$request->employee_id;
        else 
            $employee_id=auth()->user()->id;
        
        Logs::create([
            "provider_id"=>$request->provider_id,
            "employee_id"=>$employee_id,
            "notes"=>$request->notes,
            "communicate_by"=>$request->communicate_by,
            "subject"=>$request->subject,
        ]);
        session()->flash("success",__('messages.Added_successfully'));
        return back();
    }
}
