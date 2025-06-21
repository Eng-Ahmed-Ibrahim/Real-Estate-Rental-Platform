<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    use ResponseTrait;
    public function payment_methods(){
        $methods=PaymentMethod::orderBy("id","DESC")->where("status",1)->get();
        return $this->Response($methods,"Payment Methods",201);
    }
}
