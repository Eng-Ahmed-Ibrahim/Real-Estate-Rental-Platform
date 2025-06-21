<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Booking;

class BookingServices{

    public function add_booking($data){
        $booking = Booking::create([
            "amount" => $data['amount'], 
            'insurance' => 0,
            "taxes" => $data['taxes'],
            "total_amount" => $data['total_amount'],
            "customer_id" => $data['customer_id'],
            "start_at" => $data['start_at'],
            "end_at" => $data['end_at'],
            "payment_status_id" => 2,
            "booking_status_id" => 1,
            'vat' => $data['vat'],
            'coupon_id' => $data['coupon_id'],
            "service_id" => $data['service_id'],
            "category_id" => $data['category_id'],
            "provider_id" => $data['provider_id'],
            "lng" => $data['lng'],
            "lat" => $data['lat'],
            "overview_time" => $data['overview_time'],
            "overview_time_payment" => $data['overview_time_payment'],
            "has_partial_option" => $data['has_partial_option'],
            
        ]);
        return $booking;
    }


}