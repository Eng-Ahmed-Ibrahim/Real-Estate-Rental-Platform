<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function list(){
        $payments=Payment::orderBy("id","DESC")
        ->with(['payment_method','payment_status','customer','provider'])
        
        ->get();
        return view('admin.payment.payments')
        ->with("payments",$payments)
        ;
    }
}
