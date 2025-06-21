<?php

namespace App\Http\Controllers\Admin;

use DateTime;

use Exception;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use App\Models\FCM;
use App\CPU\Helpers;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Booking;
use App\Models\Earning;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Setting;
use Google\Service\Books;
use App\Models\Commission;
use Illuminate\Http\Request;
use App\Models\ServiceEventDays;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function index(Request $request)
    {


        $pending_bookings = Booking::where("booking_status_id", 1)->get();
        foreach ($pending_bookings as $booking) {
            $createdAt = $booking->created_at;
            $time = $booking->overview_time; // The number of hours to subtract
            $timeToCompare = $createdAt->addHours($time); //  addHours
            if (now()->greaterThanOrEqualTo($timeToCompare)) {
                $booking->update([
                    "booking_status_id" => 6,
                ]);
            }
        }
        $approved_bookings = Booking::where("booking_status_id", 3)->where('payment_method_id', null)->where("payment_status_id", 2)->get();
        foreach ($approved_bookings as $booking) {
            if ($booking->approved_at != null) {

                $approved_at = Carbon::parse($booking->approved_at); // 2024-08-22 12:37:22
                $time = $booking->overview_time_payment; // 10

                $timeToCompare = $approved_at->addHours($time); // addMinutes

                if (now()->greaterThanOrEqualTo($timeToCompare)) {
                    $booking->update([
                        "booking_status_id" => 7,
                    ]);
                }
            }
        }


        $query = Booking::query();

        if ($request->filled('service_id'))
            $query->where("service_id", $request->service_id);
        if ($request->filled('booking_status'))
            $query->where('booking_status_id', $request->booking_status);

        if ($request->filled('from') && $request->filled('to')) {
            $startDate = Carbon::parse($request->from);
            $endDate = Carbon::parse($request->to);
            $query->whereBetween("created_at", [$startDate, $endDate]);
        }

        if ($request->filled('date')) {
            if ($request->date == 'daily') {
                $query->daily();
            } elseif ($request->date == 'weekly') {
                $query->weekly();
            } elseif ($request->date == 'monthly') {
                $query->monthly();
            }
        }
        $query->orderBy("id", "DESC")->with(['service', 'category', 'customer', "provider"]);

        $bookings = $query->paginate(20);
        return view('admin.booking.index')
            ->with("bookings", $bookings);
    }
    public function show($id)
    {
        $order = Booking::where("id", $id)->with(['payment_method'])->first();
        if (!$order) {
            session()->flash('error', __('messages.Order_not_found'));
            return back();
        }


        $startDate = Carbon::parse($order->start_at)->format('m/d/Y');
        $endDate = Carbon::parse($order->end_at)->format('m/d/Y');
        // $endDate = $order->end_at;

        // Format the Carbon instance as m/d/Y
        $start = DateTime::createFromFormat('m/d/Y', $startDate);
        $end = DateTime::createFromFormat('m/d/Y', $endDate);


        $interval = new DateInterval('P1D');
        $datePeriod = new DatePeriod($start, $interval, $end);
        $dates = [];
        foreach ($datePeriod as $date) {
            $dates[] = $date->format('m/d/Y');
        }


        // check if start_at and end_at range is avaliable
        $days = json_decode(Service::find($order->service_id)->days);
        $days_after_formate = [];
        foreach ($days as $day) {
            $date = Carbon::createFromFormat('m/d/Y h:i A', $day);
            $days_after_formate[] = $date->format('m/d/Y');
        }

        $eventDaysData = ServiceEventDays::where("service_id", $order->service_id)->get();

        $data = [];
        $eventDays = [];
        $event_days_prices = 0;



        foreach ($dates as $key => $date) {
            foreach ($eventDaysData as $event_day) {
                if ($date == Carbon::parse($event_day->day)->format('m/d/Y')) {
                    $event_days_prices += $event_day->price;
                    $eventDays[] = [
                        "date" => $event_day->day,
                        "price" => $event_day->price,
                    ];
                    unset($dates[$key]);
                }
            }
        }
        $service = Service::find($order->service_id);

        $prices_normal_day = $service->regular_price * count($dates);
        $total = $event_days_prices + $prices_normal_day;

        $data["event_days_details"] = $eventDays;
        $data["total"] = $total;
        $data["event_days_prices"] = $event_days_prices;

        $data["total_event_days"] = count($data["event_days_details"]);
        $data["total_normal_days"] = count($dates);

        if ($order->coupon_id != null) {
            $coupon = Coupon::where("id", $order->coupon_id)->first();
            if ($coupon) {
                if ($coupon->type == "amount")
                    $data["total_after_apply_coupon"] = $total - $coupon->coupon_value;
                elseif ($coupon->type == "percentage")
                    $data["total_after_apply_coupon"] = $total - (($coupon->coupon_value * $total) / 100);
            }
        }

        return view('admin.booking.view')
            ->with('order', $order)
            ->with('data', $data);
    }

    public function save(Request $request)
    {
        $request->validate([
            "service_id" => "required",
            "start_at" => "required",
            "end_at" => "required",
            "customer_id" => "required",
        ]);
        $service = Service::find($request->service_id);
        if (!$service) {
            session()->flash("error", __('messages.Not_found'));
            return back();
        }

        $startAt = Carbon::parse($request->start_at);
        $today = Carbon::today();
        if ($startAt->lt($today)) {
            session()->flash("error", __('messages.Booking_future'));
            return back();
        }

        if ($request->end_at < $request->start_at) {
            session()->flash("error", __('messages.Check_out_bigger_than_check_in'));
            return back();
        }
        $startAt = $request->start_at;
        $endAt = $request->end_at;
        $check_is_booked = Booking::where("payment_status_id", 3)
            ->where("booking_status_id", 3)
            ->where('service_id', $request->service_id)
            ->where(function ($query) use ($startAt, $endAt) {
                $query->where(function ($query) use ($startAt, $endAt) {
                    $query->where("start_at", "<=", $startAt)
                        ->where("end_at", ">", $startAt);
                })->orWhere(function ($query) use ($startAt, $endAt) {
                    $query->where("start_at", "<=", $endAt)
                        ->where("end_at", ">", $endAt);
                })->orWhere(function ($query) use ($startAt, $endAt) {
                    $query->where("start_at", ">=", $startAt)
                        ->where("end_at", "<", $endAt);
                });
            })->exists();
        if ($check_is_booked) {
            $fromPeriod = Carbon::createFromFormat('Y-m-d H:i:s', $check_is_booked->start_at)->format('m/d/Y');
            $toPeriod = Carbon::createFromFormat('Y-m-d H:i:s', $check_is_booked->end_at)->format('m/d/Y');
            $perid = ['from_period' => $fromPeriod, 'to_period' => $toPeriod,];
            session()->flash("error", __('messages.Services_booked_already', $perid));
            return back();
        }


        $startDate = Carbon::parse($request->start_at);
        $endDate = Carbon::parse($request->end_at);
        $dates = [];

        // Loop through each date in the ran    ge and add it to the array
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d'); // Change format as per your requirement
        }
        $days = json_decode($service->days);
        $days_after_formate = [];
        foreach ($days as $day) {
            $date = Carbon::createFromFormat('m/d/Y h:i A', $day);
            $days_after_formate[] = $date->format('Y-m-d');
        }
        $missingDates = array_diff($dates, $days_after_formate);

        if (count($missingDates) > 0) {
            session()->flash("error", __('messages.Days_not_Avaliable'));
            return back();
        }







        // Calculate the difference between the two dates
        $total = Helpers::get_booking_total_price(Carbon::parse($request->start_at), Carbon::parse($request->end_at), $service->id);
        $coupon_id = null;
        if ($request->has('coupon_code')) {
            $coupon = Coupon::where("coupon_code", $request->coupon_code)->first();
            if ($coupon) {
                $today = date('m/d/Y');
                if (Carbon::parse($coupon->start_at)->startOfDay()->format('m/d/Y') <= $today && Carbon::parse($coupon->end_at)->endOfDay()->format('m/d/Y') >= $today) {

                    $coupon_id = $coupon->id;
                    if ($coupon->type == "amount")
                        $total -= $coupon->coupon_value;
                    elseif ($coupon->type == "percentage")
                        $total = $total - (($coupon->coupon_value * $total) / 100);
                }
            }
        }

        $commission_value = Commission::where("provider_id", $service->user_id)->first()->commission_value ?? Setting::find(1)->commission_value;
        $taxes = ($total * $commission_value) / 100;
        $amount = $total + $taxes;

        $overview_time = User::find($request->user()->id)->overview_time > 0 ? User::find($request->user()->id)->overview_time : Setting::find(1)->overview_time;
        $overview_time_payment = Setting::find(1)->overview_time_payment;

        $booking = Booking::create([
            "amount" => $total,
            'insurance' => 0,
            "taxes" => $taxes,
            "total_amount" => $amount,
            "customer_id" => $request->customer_id,
            "start_at" =>  Carbon::parse($request->start_at)->format('m/d/Y'),
            "end_at" => Carbon::parse($request->end_at)->format('m/d/Y'),
            "payment_status_id" => 2,
            "booking_status_id" => 1,
            'vat' => $commission_value,
            'coupon_id' => $coupon_id,
            "service_id" => $service->id,
            "category_id" => $service->category_id,
            "provider_id" => $service->user_id,
            "lng" => $service->lng,
            "lat" => $service->lat,
            "overview_time" => $overview_time,
            "overview_time_payment" => $overview_time_payment,
        ]);


        $service_name = $service->name;
        $service_name_ar = $service->name_ar;
        $customer_name = $request->user()->name;

        $fcms = FCM::where("user_id", $service->user_id)->get();
        $notification_data = [];
        $notification_title_en = __('messages.new_request_title', ['service_name' => $service_name], 'en');
        $notification_title_ar = __('messages.new_request_title', ['service_name' => $service_name_ar], 'ar');
        $notification_description_en = __('messages.new_request_description', ['customer_name' => $customer_name], 'en');
        $notification_description_ar = __('messages.new_request_description', ['customer_name' => $customer_name], 'ar');


        $lang = User::find($service->user_id)->lang;
        foreach ($fcms as $fcm) {
            $notification_data = [
                "title" => $lang == 'en' ? $notification_title_en : $notification_title_ar,
                "description" =>  $lang == 'en' ? $notification_description_en : $notification_description_ar,
                'fcm' => $fcm->fcm_token,
                'model_id' => $booking->service_id,
                'model_type' => 2,
                "fcm" => $fcm->fcm_token,
            ];
            Helpers::push_notification_owner($notification_data);
        }

        $notification_data["title_ar"] = $notification_title_ar;
        $notification_data["title_en"] = $notification_title_en;
        $notification_data["description_en"] = $notification_description_en;
        $notification_data["description_ar"] = $notification_description_ar;
        $notification_data['model_type'] = 2;
        $notification_data['model_id'] = $booking->service_id;
        $notification_data['user_id'] = $service->user_id;
        Helpers::push_notification_list($notification_data);
        session()->flash("success", __('messages.Added_successfully'));
        return back();
    }


    public function ChangePaymentStatus(Request $request)
    {
        $request->validate([
            "id" => "required",
            "payment_status" => "required",
        ]);




        try {

            DB::transaction(function () use ($request) {
                $booking = Booking::find($request->id);
                $service = Service::find($booking->service_id);

                if (!$booking) {
                    throw new Exception("Booking not found");
                }
                if ($booking->booking_status_id == 1) {
                    throw new Exception(__("messages.Not_approved_yet"));
                }
                // if($booking->payment_method_id==null){
                //     throw new Exception(__("messages.Customer_not_paid_yet"));
                // }
                // if ($booking->payment_status_id == 1 || $booking->payment_status_id == 3) {
                //     throw new Exception(__('messages.Already_changed'));
                // }

                // Parse the start and end dates
                $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_at);
                $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $booking->end_at);

                // Fixed hour and minute
                $fixedTime = '12:00 AM';

                $dates = [];

                // Loop through each date in the range and add it to the array with fixed hour and minute
                for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                    $fixedDateTime = $date->format('m/d/Y') . ' ' . $fixedTime;
                    $dates[] = Carbon::createFromFormat('m/d/Y h:i A', $fixedDateTime)->format('m/d/Y h:i A'); // Ensure correct format
                }

                // Decode the service days
                $days = json_decode($service->days);
                $days_after_format = [];

                foreach ($days as $day) {
                    // Ensure the format matches exactly what is in $days
                    $date = Carbon::createFromFormat('m/d/Y h:i A', $day);
                    // Set the fixed hour and minute for each day
                    $fixedDateTime = $date->format('m/d/Y') . ' ' . $fixedTime;
                    $days_after_format[] = Carbon::createFromFormat('m/d/Y h:i A', $fixedDateTime)->format('m/d/Y h:i A'); // Ensure correct format
                }

                // Find the missing dates
                $missingDates = array_diff($dates, $days_after_format);

                if (count($missingDates) > 0) {
                    throw new Exception(__('messages.Days_not_Avaliable'));
                }


                $booking->update([
                    "payment_status_id" => $request->payment_status,
                ]);
                // throw new Exception('sfsa');
                // paid user
                if ($request->payment_status == 3 || $request->payment_status == 4) {
                    $invoice_id = Payment::orderBy("id", "DESC")->first() ? Payment::orderBy("id", "DESC")->first()->invoice_id + 1 : 10000;
                    $attachment = $booking->attachment;
                    $payment_type = $booking->payment_type;
                    Payment::create([
                        "invoice_id" => $invoice_id,
                        "booking_id" => $booking->id,
                        "customer_id" => $booking->customer_id,
                        "amount" => $booking->total_amount,
                        "provider_id" => $booking->provider_id,
                        "payment_status_id" => 3,
                        "attachment" => $attachment,
                        'payment_method_id' => $booking->payment_method_id,
                        "payment_type" => $payment_type,
                    ]);
                    $booking->update([
                        "booking_status_id" => 3,
                        "down_paid" => $booking->payment_plan == "partial" && $booking->down_paid == 0 ? $booking->down_payment : 0,
                    ]);
                    $booking->provider->update([
                        "blance" => ($booking->provider->blance) + $booking->amount,
                    ]);
                    $customer = $booking->customer;
                    $customer->update([
                        "points" => $customer->points +  Setting::find(1)->point_earn_on_each_booking,
                    ]);
                    Earning::create([
                        "user_id" => $booking->provider_id,
                        "total_booking" => 1,
                        "total_earning" => $request->payment_status == 4 ? $booking->down_payment :  $booking->total_amount,
                        "provider_earning" => $booking->amount,
                        'admin_earning' => $booking->taxes,
                        "booking_id" => $booking->id,
                    ]);
                    Helpers::delete_days_after_booking($booking->id);
                }



                // if ($request->payment_status == 1)


                $lang = User::find($booking->provider_id)->lang;

                // Define translation arrays
                $payment_status_translations = [
                    1 => [
                        'en' => 'rejected',
                        'ar' => 'مرفوضه',
                    ],
                    2 => [
                        'en' => 'Pending',
                        'ar' => 'معلق',
                    ],
                    3 => [
                        'en' => 'accepted',
                        'ar' => 'مقبوله',
                    ],
                    4 => [
                        'en' => 'accepted',
                        'ar' => 'مقبوله',
                    ],
                ];

                $notification_title_translations = [
                    'en' => 'Your request Payment Status',
                    'ar' => 'حالة الدفع لطلبك',
                ];

                $payment_status_changed_translations = [
                    'en' => 'Your payment has been  :booking_status !!',
                    'ar' => 'تم تغيير حالة الدفع للحجز الخاص بك إلى :booking_status !!',
                ];
                // Get the appropriate translation based on the language
                $booking_status_en = $payment_status_translations[$request->payment_status]['en'];
                $booking_status_ar = $payment_status_translations[$request->payment_status]['ar'];

                $notification_title_en = $notification_title_translations['en'];
                $notification_title_ar = $notification_title_translations['ar'];
                $notification_description_en = str_replace(':booking_status', $booking_status_en, $payment_status_changed_translations['en']);
                $notification_description_ar = str_replace(':booking_status', $booking_status_ar, $payment_status_changed_translations['ar']);

                // Get the appropriate translation based on the language
                $booking_status = $payment_status_translations[$request->payment_status][$lang];
                $notification_title = $notification_title_translations[$lang];
                $notification_description = str_replace(':booking_status', $booking_status, $payment_status_changed_translations[$lang]);

                $fcms = FCM::where("user_id", $booking->customer_id)->get();
                foreach ($fcms as $fcm) {
                    $notification_data = [
                        "title" => $notification_title,
                        "description" => $notification_description,
                        'fcm' => $fcm->fcm_token,
                        'model_id' => $booking->service_id,
                        'model_type' => 2,
                    ];
                    Helpers::push_notification_user($notification_data);
                }
                $notification_data["title_ar"] = $notification_title_ar;
                $notification_data["title_en"] = $notification_title_en;
                $notification_data["description_en"] = $notification_description_en;
                $notification_data["description_ar"] = $notification_description_ar;
                $notification_data['user_id'] = $booking->customer_id;
                $notification_data['model_type'] = 2;
                $notification_data['model_id'] = $booking->service_id;

                Helpers::push_notification_list($notification_data);
            });
        } catch (Exception $e) {
            // Handle error, set flash message, and redirect
            session()->flash("error", $e->getMessage());
            return back();
        }
        session()->flash("success", __('messages.Updated_successfully'));
        return back();
    }
    public function ChangeBookingStatus(Request $request)
    {
        $request->validate([
            "id" => "required",
            "booking_status" => "required",
        ]);
        $booking = Booking::find($request->id);

        if (!$booking) {
            session()->flash("error", "not found");
            return back();
        }

        $start = Carbon::parse($booking->start_at)->format('m/d/Y');
        $end = Carbon::parse($booking->end_at)->format('m/d/Y');

        if ($request->booking_status == 3) {
            $overlapExists = Booking::where('id', '!=', $booking->id)
                ->where('service_id', $booking->service_id)
                ->where('booking_status_id', 3)
                ->where(function ($query) use ($start, $end) {
                    $query->whereRaw("STR_TO_DATE(start_at, '%m/%d/%Y') < STR_TO_DATE(?, '%m/%d/%Y')", [$end])
                        ->whereRaw("STR_TO_DATE(end_at, '%m/%d/%Y') > STR_TO_DATE(?, '%m/%d/%Y')", [$start]);
                })
                ->exists();

            if ($overlapExists) {
                session()->flash("error", __('messages.booking_conflict'));
                return back();
            }
        }

        if ($booking->payment_status_id == 3) {
            session()->flash("error", __('messages.Customers_paid'));
            return back();
        }
        $booking->update([
            "booking_status_id" => $request->booking_status,
            "approved_at" => $request->status == 3 ? now() : null,
        ]);

        $lang = User::find($booking->customer_id)->lang;

        // Define translation arrays
        $booking_status_translations = [
            1 => [
                'en' => 'Pending',
                'ar' => 'معلق',
            ],
            2 => [
                'en' => 'In Process',
                'ar' => 'قيد التنفيذ',
            ],
            3 => [
                'en' => 'Approved',
                'ar' => 'موافق عليه',
            ],
            4 => [
                'en' => 'Rejected',
                'ar' => 'مرفوض',
            ],
        ];

        $notification_title_translations = [
            'en' => 'Your request Status',
            'ar' => 'حالة طلبك',
        ];

        $status_changed_translations = [
            'en' => 'Your reservation status has been changed to :booking_status !!',
            'ar' => 'تم تغيير حالة الحجز الخاصة بك إلى :booking_status !!',
        ];

        // Get the appropriate translation based on the language
        $booking_status = $booking_status_translations[$booking->booking_status_id][$lang];
        $notification_title = $notification_title_translations[$lang];
        $notification_description = str_replace(':booking_status', $booking_status, $status_changed_translations[$lang]);

        // Get the appropriate translation based on the language
        $booking_status_en = $booking_status_translations[$booking->booking_status_id]['en'];
        $booking_status_ar = $booking_status_translations[$booking->booking_status_id]['ar'];

        $notification_title_en = $notification_title_translations['en'];
        $notification_title_ar = $notification_title_translations['ar'];

        $notification_description_en = str_replace(':booking_status', $booking_status_en, $status_changed_translations['en']);
        $notification_description_ar = str_replace(':booking_status', $booking_status_ar, $status_changed_translations['ar']);


        $fcms = FCM::where("user_id", $booking->customer_id)->get();
        foreach ($fcms as $fcm) {
            $notification_data = [
                "title" => $notification_title,
                "description" => $notification_description,
                'fcm' => $fcm->fcm_token,
                'model_id' => $booking->service_id,
                'model_type' => 2,
            ];
            Helpers::push_notification_user($notification_data);
        }
        $notification_data["title_ar"] = $notification_title_ar;
        $notification_data["title_en"] = $notification_title_en;
        $notification_data["description_en"] = $notification_description_en;
        $notification_data["description_ar"] = $notification_description_ar;
        $notification_data['model_type'] = 2;
        $notification_data['model_id'] = $booking->service_id;


        $notification_data['user_id'] = $booking->customer_id;
        Helpers::push_notification_list($notification_data);
        session()->flash("success", __('messages.Updated_successfully'));
        return back();
    }
    public function confrim_cancellation(Request $request)
    {
        $request->validate([
            "id" => "required",
        ]);
        $booking = Booking::find($request->id);
        if ($booking) {
            $booking->update([
                "confirm_cancellation" => true,
            ]);
            session()->flash("success", __("messages.Confrimed_successfully"), 201);
            return back();
        }
        return back();
    }
}
