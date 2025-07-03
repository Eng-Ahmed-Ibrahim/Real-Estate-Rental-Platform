<?php

namespace App\Services;

use DateTime;
use Exception;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Booking;
use App\Models\Service;
use App\Models\ServiceEventDays;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\ResponseTrait;

class BookingServices
{
    use ResponseTrait;
    public function add_booking($data)
    {

        $booking = Booking::create([
            "amount" => $data['amount'],
            'discount'=>$data['discount'],
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



    public function check_if_dates_are_booked($service_id, $start_at, $end_at, $booking_id)
    {
        $startDate = $start_at ? Carbon::parse($start_at)->format('Y-m-d') : null;
        $endDate = $end_at ? Carbon::parse($end_at)->format('Y-m-d') : null;

        $conflictingBooking = Booking::where('service_id', $service_id)
            ->when($booking_id, function ($query) use ($booking_id) {
                $query->where('id', '!=', $booking_id);
            })
            ->whereIn('payment_status_id', [3, 4])
            ->where("booking_status_id", 3)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where(DB::raw("STR_TO_DATE(start_at, '%m/%d/%Y')"), '<', $endDate)
                        ->where(DB::raw("STR_TO_DATE(end_at, '%m/%d/%Y')"), '>', $startDate);
                });
            })
            ->exists();

        return $conflictingBooking;
    }

    
    public  function get_booking_total_price(DateTime  $start_at, DateTime  $end_at, $service_id)
    {
        $service = Service::find($service_id);
        $startDate = $start_at;
        $endDate = $end_at;

        $start = $startDate;
        $end = $endDate;
        $interval = new DateInterval('P1D');

        $datePeriod = new DatePeriod($start, $interval, $end);

        $dates = [];
        foreach ($datePeriod as $date) {
            $dates[] = $date->format('m/d/Y');
        }
        $eventDays = ServiceEventDays::where("service_id", $service_id)->get();
        $event_days = [];
        $event_days_prices = 0;
        if (count($eventDays) > 0) {
            foreach ($eventDays as $eventDay) {
                if (($key = array_search($eventDay->day, $dates)) !== false) {
                    $event_days_prices += $eventDay->price;
                    $event_days[] = [
                        "date" => $eventDay->day,
                        "price" => $eventDay->price,
                    ];
                    unset($dates[$key]);
                }
            }
        }
        $prices_normal_day = $service->regular_price * count($dates);
        $total = $event_days_prices + $prices_normal_day;

        return $total;
    }
}
