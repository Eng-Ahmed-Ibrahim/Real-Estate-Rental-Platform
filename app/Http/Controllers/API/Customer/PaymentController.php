<?php

namespace App\Http\Controllers\API\Customer;

use App\Events\BookingStatus;
use App\Events\PaymentStatus;
use App\Events\UpdateEarning;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Customer\AddPaymentRequest;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentMethod;
use DB;

class PaymentController extends Controller
{

    public function store(AddPaymentRequest $request)
    {
        $booking = Booking::whereId($request->booking_id)->first();
        if ($booking) {
            // check if paid already
            $check_payment = Payment::where('booking_id', $request->booking_id)->first();
            if (!$check_payment) {
                try {
                    DB::beginTransaction();

                    $payment = new Payment();
                    $payment->invoice_id = $request->invoice_id;
                    $payment->booking_id = $request->booking_id;
                    $payment->customer_id = $booking->customer_id;
                    $payment->provider_id = $booking->provider_id;
                    $payment->amount = $booking->total_amount;
                    $payment->payment_status_id = 3;
                    $payment->payment_method_id = $request->payment_method_id;
                    $payment->save();
                    // change payment status in booking
                    $booking->booking_status_id= 7;
                    $booking->payment_status_id = 3;
                    $booking->save();

                    $payment['action_type'] = 'payment';

                    event(new UpdateEarning($booking));
                    event(new PaymentStatus($payment));

                    DB::commit();
                    
                    return response([
                        "message" => 'Payment Added successfully'
                    ]);

                }catch (\Exception $exception){
                    DB::rollback();
                    return response([
                        "message" => $exception
                    ]);
                }
            }else{
                return response([
                    "message" => 'This Booking Already Paid'
                ]);
            }
        }
        return response([
            "message" => 'Booking Not Found'
        ]);
    }
    
    
    public function paymentMethods()
    {

        $methods = PaymentMethod::where('status',1)->get();

       
        return response([
            "message" => 'Methods relative',
            'methods'=>$methods
        ]);
    }
    
}
