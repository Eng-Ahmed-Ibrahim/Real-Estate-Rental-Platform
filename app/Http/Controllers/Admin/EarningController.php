<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use Illuminate\Http\Request;

class EarningController extends Controller
{
    public function index(){
        $earnings=Earning::orderBy("id","DESC")->with(['user'])->paginate(15);
        return view('admin.earning.index')
        ->with('earnings',$earnings)
        ;
    }
}
