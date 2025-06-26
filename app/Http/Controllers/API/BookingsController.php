<?php

namespace App\Http\Controllers\API;

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
use App\Models\Feature;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Favorite;
use App\Models\Commission;
use App\Events\RequestEvent;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\ServiceEventDays;
use App\Services\BookingServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BookingsController extends Controller
{
    use ResponseTrait;
    private $bookingService;
    function __construct(BookingServices $bookingService)
    {
        $this->bookingService = $bookingService;
    }
    public function ProviderServices(Request $request)
    {
        $query = Service::query();
        if ($request->has('category_id') &&  $request->category_id > 0)
            $query->where('category_id', $request->category_id);

        if ($request->has('min_price') && $request->has('max_price'))
            $query->whereBetween('price', [$request->min_price, $request->max_price]);

        if ($request->has('bed') &&  $request->bed > 0)
            $query->where('bed', $request->bed);

        if ($request->has('bath')  &&  $request->bath > 0)
            $query->where('bath', $request->bath);


        if ($request->has('floor')  &&  $request->floor > 0)
            $query->where('floor', $request->floor);

        if ($request->has('text') &&  strlen($request->text) > 0)
            $query->where("name", "like", "%{$request->text}%")
                ->orWhere("name_ar", "like", "%{$request->text}%");




        if ($request->has('accept'))
            $query->where("accept", $request->accept);
        else
            $query->where("accept", 2);


        $query->withCount('booking')->where("user_id", $request->user()->id);


        $data = $query->with(['booking', 'features', 'review'])->orderBy('id', "DESC")->get();
        $services = [];
        foreach ($data as $service) {
            $check = Favorite::where("service_id", $service->id)
                ->where("user_id", $request->user()->id)->first();
            $eventDays = ServiceEventDays::where("service_id", $service->id)->orderBy("id", "DESC")->get();
            $features = [];
            foreach ($service->features as $feature) {
                $features[] = Feature::find($feature->feature_id);
            }

            $totalRating = 0;
            $reviewCount = count($service->review);

            foreach ($service->review as $review) {
                $totalRating += floatval($review['rating']);
            }
            $averageRating = $reviewCount > 0 ? $totalRating / $reviewCount : 0;


            $services[] = [
                'id' => $service->id,
                'name' => $service->name,
                'name_ar' => $service->name_ar,
                "category_id" => $service->category_id,
                "user_id" => $service->user_id,
                "floor" => $service->floor,
                "bed" => $service->bed,
                "bath" => $service->bath,
                "price" => $service->price,
                "description" => $service->description,
                "description_ar" => $service->description_ar,
                "days" => ($service->days),
                "range_days" => ($service->range_days),
                "lat" => $service->lat,
                "lng" => $service->lng,
                "available" => $service->available,
                "accept" => $service->accept,
                "price_with_commission" => $service->price_with_commission,
                "image" => $service->image,
                'is_favorited' => $check ? 1 : 0,
                'booking_count' => $service->booking()->count(),
                "created_at" => $service->created_at,
                'place' => $service->place,
                'place_ar' => $service->place_ar,
                "eventDays" => $eventDays,
                "features" => $features,
                "gallery" => $service->gallery,
                'regular_price' => $service->regular_price,
                "reviews" => $service->review,
                "rate" => $averageRating,

            ];
        }
        return $this->Response($services, "Services", 201);
    }
    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "booking_id" => "required",
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);


        $time = 1; // The number of hours to subtract
        $booking = Booking::where("id", $request->booking_id)
            ->orderBy("id", "DESC")
            ->with(["service", 'customer', "service.gallery", 'service.eventDays', 'service.features.feature', 'service.review'])
            ->first();

        if ($booking) {

            $createdAt = $booking->created_at;

            // Add $time hours to the created_at time
            $timeToCompare = $createdAt->addHours($time);

            // Compare the current time with the adjusted time
            if (now()->greaterThanOrEqualTo($timeToCompare) && $booking->booking_status_id == 1) {
                // Update the booking_status_id to 6 if 3 hours have passed
                $booking->update([
                    "booking_status_id" => 6,
                ]);
            }
            $remain_time = now()->diffForHumans($createdAt, [
                'parts' => 3, // Limit the output to the most relevant parts (e.g., hours, minutes, and seconds)
                'short' => true, // Return the short version like '3h 15m'
                'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE, // Avoid words like "ago"
            ]);
            $data = [
                "remain_time" => $remain_time,
                "booking_details" => $booking,
            ];
            return $this->Response($data, "Booking Details", 201);
        }
    }
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
                // $booking->delete();
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


        if ($request->user()->power == 'provider' || $request->user()->power == 'admin') {

            $today = date("Y-m-d H:i:s");
            $query = Booking::query();
            $query->where("provider_id", $request->user()->id);
            if ($request->has('service_id')) {
                $query->where("service_id", $request->service_id);
            }

            if ($request->has('booking_status_id')) {
                $query->where("booking_status_id", $request->booking_status_id);
            }
            if ($request->filled('search'))
                $query->whereHas('service', function ($q) use ($request) {
                    if ($request->has('search')) {
                        $q->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('name_ar', 'like', "%{$request->search}%")
                        ;
                    }
                });
            $orders = $query
                ->orderBy("id", "DESC")->with(["service", 'coupon', "service.user", 'customer', "service.gallery", 'service.eventDays', 'service.features.feature', 'service.review'])->get();
            return $this->Response($orders, "Requests", 201);
        } elseif ($request->user()->power == 'customer') {

            $today = date("Y-m-d H:i:s");
            $query = Booking::query();
            if ($request->has('booking_status_id')) {
                $query->where("booking_status_id", $request->booking_status_id);
            }
            if ($request->filled('search'))
                $query->whereHas('service', function ($q) use ($request) {
                    if ($request->has('search')) {
                        $q->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('name_ar', 'like', "%{$request->search}%")
                        ;
                    }
                });
            // $rental = $query->where("customer_id", $request->user()->id)

            //     ->where("booking_status_id", "!=", 1)
            //     ->where("booking_status_id", "!=", 2)
            //     ->orderBy("id", "DESC")->with(["service", 'coupon', "service.user", 'customer', "service.gallery", 'service.eventDays', 'service.features.feature', 'service.review'])
            //     ->get();
            $rental = [];
            $query2 = Booking::query();
            if ($request->has('booking_status_id')) {
                $query2->where("booking_status_id", $request->booking_status_id);
            }
            if ($request->filled('search'))
                $query2->whereHas('service', function ($q) use ($request) {
                    if ($request->has('search')) {
                        $q->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('name_ar', 'like', "%{$request->search}%")
                        ;
                    }
                });
            $upcoming = $query2->where("customer_id", $request->user()->id)
                // ->where("booking_status_id", "=", 1)
                // ->orWhere("booking_status_id", "=", 2)
                ->orderBy("id", "DESC")->with(["service", 'coupon', "service.user", 'customer', "service.gallery", 'service.eventDays', 'service.features.feature', 'service.review'])
                ->get();
            $contact_details = Setting::find(1);
            $bookings = [
                "rental" => $rental,
                "upcoming" => $upcoming,
                "contact_details" => [
                    "owner_name" => $contact_details->owner_name,
                    "phone" => $contact_details->phone,
                ],
            ];
            return $this->Response($bookings, "Booking ", 201);
        }
    }
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "service_id" => "required",
            "start_at" => "required|date",
            "end_at" => "required|date|after:start_at",
        ], [
            'end_at.after' => __('messages.after_date'),
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors(), $validator->errors()->first(), 422);


        $service = Service::find($request->service_id);
        if (!$service)
            return $this->Response(null, __('messages.Not_found'), 404);

        $startAt = Carbon::parse($request->start_at);
        $today = Carbon::today();
        if ($startAt->lt($today)) {
            return response()->json(['error' => __('messages.Booking_future')], 400);
        }

        if ($request->end_at < $request->start_at)
            return $this->Response(null, __("messages.Check_out_bigger_than_check_in"), 201);
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
            })->first();
        if ($check_is_booked) {
            $fromPeriod = Carbon::createFromFormat('Y-m-d H:i:s', $check_is_booked->start_at)->format('m/d/Y');
            $toPeriod = Carbon::createFromFormat('Y-m-d H:i:s', $check_is_booked->end_at)->format('m/d/Y');
            $perid = ['from_period' => $fromPeriod, 'to_period' => $toPeriod,];
            return $this->Response($check_is_booked, __('messages.Services_booked_already', $perid), 422);
        }


        $startDate = Carbon::createFromFormat('m/d/Y', $request->start_at);
        $endDate = Carbon::createFromFormat('m/d/Y', $request->end_at);
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
            return $this->Response($missingDates, __('messages.Days_not_Avaliable'), 422);
        }







        // Calculate the difference between the two dates
        $total = Helpers::get_booking_total_price(Carbon::createFromFormat('m/d/Y', $request->start_at), Carbon::createFromFormat('m/d/Y', $request->end_at), $service->id);
        //==290


        $commission = Commission::where("provider_id", $service->user_id)->first() ?? Setting::find(1);
        if ($commission) {
            $commission_value = $commission->commission_value;
            if ($commission->commission_type == "percentage") {
                $commission_money = (($total * $commission_value) / 100);
            } else {
                $commission_money =  $commission_value * (count($dates) - 1);
            }
        }


        $taxes = $commission_money; // 20 -- 29
        $amount = $total + $taxes;  // 310 -- 319 / 2 =159.5


        $coupon_id = null;
        if ($request->filled('coupon_code')) {
            $total = $total  +  $taxes; // 310
            $coupon = Coupon::where("coupon_code", $request->coupon_code)->first();
            if ($coupon) {
                $today = date('m/d/Y');
                if (Carbon::parse($coupon->start_at)->startOfDay()->format('m/d/Y') <= $today && Carbon::parse($coupon->end_at)->endOfDay()->format('m/d/Y') >= $today) {

                    $coupon_id = $coupon->id;
                    if ($coupon->type == "amount") {
                        if ($coupon->coupon_value >= $total) {
                            $total = 0;
                            $taxes = 0;
                        } else {
                            $total -= $coupon->coupon_value;

                            // $taxes -=$coupon->coupon_value;
                        }
                    } elseif ($coupon->type == "percentage") {
                        $total = $total - (($coupon->coupon_value * $total) / 100); // 155 -- 159.5
                        $taxes = $taxes - (($coupon->coupon_value * $taxes) / 100); // 20->10 -- 29-> 14.5

                    }
                }
                $amount = $total;
                if ($commission) {
                    $commission_value = $commission->commission_value;
                    if ($commission->commission_type == "percentage") {
                        $commission_money = (($total * $commission_value) / 100);
                    } else {
                        $commission_money =  $commission_value * (count($dates) - 1);
                    }
                }
                if ($total > 0) {
                    $total -=  $commission_money;
                    if ($coupon->type != "amount")
                        $total += $taxes;
                }
            }
        }


        $customer = User::find($request->user()->id);
        $settings = Setting::find(1);

        $overview_time = $customer->overview_time > 0 ? $customer->overview_time : $settings->overview_time;
        $overview_time_payment = $settings->overview_time_payment;
        $has_partial_option =  $amount < $settings->min_partial_payment ? false : true;
        $data = [
            "amount" => $total, // before taxes without commission
            'insurance' => 0,
            "taxes" => $taxes,
            "total_amount" => $amount,
            "customer_id" => $customer->id,
            "start_at" => $request->start_at,
            "end_at" => $request->end_at,
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
            "has_partial_option" => $has_partial_option,
        ];

        $booking = $this->bookingService->add_booking($data);
        broadcast(new RequestEvent($booking))->toOthers();


        $service_name = $service->name;
        $service_name_ar = $service->name_ar;
        $customer_name = $customer->name;

        $fcms = FCM::where("user_id", $service->user_id)->get();
        $notification_data = [];
        $notification_title_en = __('messages.new_request_title', ['service_name' => $service_name], 'en');
        $notification_title_ar = __('messages.new_request_title', ['service_name' => $service_name_ar], 'ar');
        $notification_description_en = __('messages.new_request_description', ['customer_name' => $customer_name], 'en');
        $notification_description_ar = __('messages.new_request_description', ['customer_name' => $customer_name], 'ar');


        $lang = $customer->lang;
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
        return $this->Response($booking, "Added Successfully", 201);
    }
    public function changeBookingStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => "required",
            "booking_id" => "required",
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);

        $booking = Booking::where("id", $request->booking_id)->where("provider_id", $request->user()->id)->first();
        if (!$booking)
            return $this->Response(null, "Not Allowed", 403);
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
                return $this->Response(null, __('messages.booking_conflict'), 422);
            }
        }

        $check_is_booked = Helpers::check_if_dates_are_booked($booking->service_id, $booking->start_at, $booking->end_at, $booking->id);
        if ($check_is_booked)
            return $this->Response(null, __("messages.The_selected_dates_are_already_booked"), 422);

        if ($request->status == 3 && $request->payment_plan != 'full' && $request->payment_plan != "partial")
            return $this->Response(null, 'payment plan required', 422);

        $down_payment = 0;
        if ($request->payment_plan == "partial") {
            $value = Setting::find(1)->down_payment;
            $down_payment = ($booking->total_amount * $value) / 100;
        }

        $booking->update([
            "booking_status_id" => $request->status,
            "comment" => $request->comment ?? $booking->comment,
            "approved_at" => $request->status == 3 ? now() : null,
            "payment_plan" => $request->payment_plan,
            "down_payment" => $down_payment,
        ]);

        if ($booking->booking_status_id == 1) {
            $booking_status_en = __('messages.status_pending', [], 'en');
            $booking_status_ar = __('messages.status_pending', [], 'ar');
        } else if ($booking->booking_status_id == 2) {
            $booking_status_en = __('messages.status_in_process', [], 'en');
            $booking_status_ar = __('messages.status_in_process', [], 'ar');
        } else if ($booking->booking_status_id == 3) {
            $booking_status_en = __('messages.status_approved', [], 'en');
            $booking_status_ar = __('messages.status_approved', [], 'ar');
        } else if ($booking->booking_status_id == 4) {
            $booking_status_en = __('messages.status_rejected', [], 'en');
            $booking_status_ar = __('messages.status_rejected', [], 'ar');
        }
        $notification_title_en = __('messages.booking_status_title', [], 'en');
        $notification_title_ar = __('messages.booking_status_title', [], 'ar');
        $notification_description_en = __('messages.booking_status_changed', ['booking_status' => $booking_status_en], 'en');
        $notification_description_ar = __('messages.booking_status_changed', ['booking_status' => $booking_status_ar], 'ar');

        $fcms = FCM::where("user_id", $booking->customer_id)->get();
        foreach ($fcms as $fcm) {
            $notification_data = [
                "title" => $booking->customer->lang == "en" ? $notification_title_en : $notification_title_ar,
                "description" => $booking->customer->lang == "en" ?  $notification_description_en :  $notification_description_ar,
                'fcm' => $fcm->fcm_token,
                'model_id' => $booking->service_id,
                "model_type" => 2,
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

        return $this->Response($booking, "Updated Successfully", 201);
    }
    public function changePaymentStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => "required",
            "booking_id" => "required",
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);

        $booking = Booking::where("id", $request->booking_id)->where("provider_id", $request->user()->id)->with(['customer', 'provider'])->first();
        if (!$booking)
            return $this->Response(null, "Not Allowed", 403);

        if ($booking->payment_status_id == 3) {
            return $this->Response(null, "Payment already Approved ", 401);
        }
        if ($booking->booking_status_id == 5) {
            return $this->Response(null, "Booking already cancelled", 401);
        }

        $check_is_booked = Helpers::check_if_dates_are_booked($booking->service_id, $booking->start_at, $booking->end_at, $booking->id);
        if ($check_is_booked)
            return $this->Response(null, "The selected dates are already booked.2", 422);



        if (($request->status == 3  || $booking->booking_status_id == 4)) {
            $service = $booking->service;
            if (!$service) {
                return $this->Response(null, "service not found", 401);
            }
            $booking->update([
                "payment_status_id" => $booking->payment_plan == 'full' ? 3 : ($booking->down_paid == 0 ? 4 : 3), //paid
                "booking_status_id" => 3, //approved
            ]);

            $booking->provider->update([
                "blance" => ($booking->provider->blance) + $booking->amount,
            ]);
            // User::find($booking->provider_id)->update([
            //     "blance" => (User::find($booking->provider_id)->blance) + $booking->amount,
            // ]);
            $total_paid = $booking->payment_plan == 'full' ? $booking->total_amount : ($booking->down_paid == 0 ? $booking->down_payment : $booking->total_amount - $booking->down_paid);
            if ($booking->payment_status_id == 3) {
                $customer = $booking->customer;
                $customer->update([
                    "points" => $customer->points +  Setting::find(1)->point_earn_on_each_booking,
                ]);
            }
            if ($booking->payment_plan == 'partial' || $booking->payment_status_id == 3) {
                Helpers::delete_days_after_booking($booking->id);
            }
            Earning::create([
                "user_id" => $booking->provider_id,
                "total_booking" => 1,
                "total_earning" => $total_paid,
                "provider_earning" => $booking->payment_status_id == 3 ? $booking->amount : 0,
                'admin_earning' => $booking->payment_status_id == 3 ? $booking->taxes : 0,
                'service_id' => $service->id,
                'booking_id' => $booking->id,
            ]);
        }
        $payment_status_en = '';
        $payment_status_ar = '';

        if ($request->status == 1) {
            $payment_status_en = "Unpaid";
            $payment_status_ar = "غير مدفوع";
        } elseif ($request->status == 2) {
            $payment_status_en = "Pending";
            $payment_status_ar = "قيد الانتظار";
        } elseif ($request->status == 3) {
            $payment_status_en = "Paid";
            $payment_status_ar = "مدفوع";
        } elseif ($request->status == 4) {
            $payment_status_en = "Partial payment";
            $payment_status_ar = "دفع جزئي";
        }

        // عناوين الإشعار ثابتة (مش من messages)
        $notification_title_en = "Payment Status";
        $notification_title_ar = "حالة الدفع";

        $notification_description_en = "Payment status changed to: {$payment_status_en}";
        $notification_description_ar = "تم تغيير حالة الدفع إلى: {$payment_status_ar}";

        $fcms = FCM::where("user_id", $booking->customer_id)->get();

        foreach ($fcms as $fcm) {
            $notification_data = [
                "title" => $booking->customer->lang === "en" ? $notification_title_en : $notification_title_ar,
                "description" => $booking->customer->lang === "en" ? $notification_description_en : $notification_description_ar,
                'fcm' => $fcm->fcm_token,
                'model_id' => $booking->service_id,
                "model_type" => 2,
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
        $booking->update([
            "payment_status_id" => $request->status,
        ]);
        return $this->Response($booking, "Updated Successfully", 201);
    }

    public function upload_attachment_to_booking(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            "booking_id" => "required",
            "attachment" => "required|file",
            "payment_method_id" => "required|integer",
        ]);

        if ($validator->fails()) {
            return $this->Response($validator->errors(), "Data Not Valid", 422);
        }

        // Begin transaction
        DB::beginTransaction();

        try {
            // Find the booking with customer ID check
            $booking = Booking::where("id", $request->booking_id)
                ->where("customer_id", $request->user()->id)
                ->first();

            if (!$booking) {
                return $this->Response(null, "Not Allowed", 403);
            }
            if ($booking->booking_status_id == 5) {
                return $this->Response(null, "Booking already cancelled", 401);
            }
            if ($booking->payment_status_id == 3) {
                return $this->Response(null, "Payment already Approved ", 401);
            }

            $check_is_booked = Helpers::check_if_dates_are_booked($booking->service_id, $booking->start_at, $booking->end_at, $booking->id);
            if ($check_is_booked)
                return $this->Response(null, "The selected dates are already booked.2", 422);

            // Check if booking status is allowed for update
            if ($booking->booking_status_id == 3) {
                // Handle file upload
                $newFilename = time() . '_' . str_replace(" ", "_", $request->attachment->getClientOriginalName());
                $request->attachment->move(public_path("files/"), $newFilename);

                if ($booking->payment_plan == "partial" && $booking->down_paid == 0) {

                    $booking->update([
                        "down_attachment" => "files/$newFilename",
                        'payment_method_id' => 4,
                        "payment_type" => "bank_transfer",
                        "comment" => $request->comment,
                    ]);
                } else {
                    // Update booking
                    $booking->update([
                        "attachment" => "files/$newFilename",
                        'payment_method_id' => 4,
                        "payment_type" => "bank_transfer",
                        "comment" => $request->comment,
                    ]);
                }
                $required_amount = $booking->payment_plan == "full" ? $booking->amount : ($booking->down_paid == 0 ? $booking->down_payment : $booking->amount -  $booking->down_payment);
                $invoice_id = Payment::orderBy("id", "DESC")->first() ? Payment::orderBy("id", "DESC")->first()->invoice_id + 1 : 10000;
                Payment::create([
                    "invoice_id" => $invoice_id,
                    "booking_id" => $booking->id,
                    "customer_id" => $request->user()->id,
                    "amount" => $required_amount,
                    "provider_id" => $booking->provider_id,
                    "payment_status_id" => 2,
                    "attachment" => "files/$newFilename",
                    'payment_method_id' => $request->payment_method_id,
                ]);

                // Commit the transaction
                DB::commit();

                // Prepare notification data
                $customer_name = $request->user()->name;
                $fcms = FCM::where("user_id", $booking->provider_id)->get();

                $notification_title_en = __('messages.file_uploaded_title', ['customer_name' => $customer_name], 'en');
                $notification_title_ar = __('messages.file_uploaded_title', ['customer_name' => $customer_name], 'ar');
                $notification_description_en = __('messages.money_transferred_title', ['customer_name' => $customer_name], 'en');
                $notification_description_ar = __('messages.money_transferred_title', ['customer_name' => $customer_name], 'ar');

                $lang = $booking->provider->lang;

                foreach ($fcms as $fcm) {
                    $notification_data = [
                        "title" => $lang == 'en' ? $notification_title_en :  $notification_title_ar,
                        "description" => $lang == 'en' ? $notification_description_en :  $notification_description_ar,
                        "fcm" => $fcm->fcm_token ?? ' ',
                        'model_id' => $booking->service_id,
                        "model_type" => 2,
                    ];
                    Helpers::push_notification_owner($notification_data);
                }

                // Store the notification in a list
                $notification_data["title_ar"] = $notification_title_ar;
                $notification_data["title_en"] = $notification_title_en;
                $notification_data["description_en"] = $notification_description_en;
                $notification_data["description_ar"] = $notification_description_ar;
                $notification_data['user_id'] = $booking->provider_id;
                $notification_data['model_type'] = 2;
                $notification_data['model_id'] = $booking->service_id;
                Helpers::push_notification_list($notification_data);

                return $this->Response($booking, "Uploaded Successfully", 201);
            } else {
                return $this->Response(null, "The owner Not Approved yet", 401);
            }
        } catch (\Exception $e) {
            // Rollback the transaction if any exception occurs
            DB::rollBack();

            // Optionally, delete the uploaded file if something goes wrong
            if (isset($newFilename) && file_exists(public_path("files/$newFilename"))) {
                unlink(public_path("files/$newFilename"));
            }

            return $this->Response(null, "Something went wrong: " . $e->getMessage(), 500);
        }
    }


    public function pay_by_wallet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "booking_id" => "required",
        ]);

        if ($validator->fails()) {
            return $this->Response($validator->errors(), "Data Not Valid", 422);
        }

        DB::beginTransaction(); // Start transaction

        try {
            // Find the booking with customer ID check
            $booking = Booking::where("id", $request->booking_id)
                ->where("customer_id", $request->user()->id)
                ->first();
            $service = $booking->service;
            if (!$booking) {
                return $this->Response(null, "Not Allowed", 403);
            }
            if ($booking->payment_status_id == 3) {
                return $this->Response(null, "Payment already Approved ", 401);
            }
            if ($booking->booking_status_id == 5) {
                return $this->Response(null, "Booking already cancelled", 401);
            }

            $check_is_booked = Helpers::check_if_dates_are_booked($booking->service_id, $booking->start_at, $booking->end_at, $booking->id);
            if ($check_is_booked)
                return $this->Response(null, "The selected dates are already booked", 422);

            // Check if booking status is allowed for payment
            if ($booking->booking_status_id == 3) {

                $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $booking->start_at);
                $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $booking->end_at);

                $fixedTime = '12:00 AM';

                $dates = [];

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

                $user = User::find($request->user()->id);

                $required_amount = 0;
                if ($booking->payment_plan == "partial" && $booking->down_paid == 0)
                    $required_amount = $booking->down_payment;
                elseif ($booking->payment_plan == "partial" && $booking->down_paid == $booking->down_payment)
                    $required_amount = $booking->total_amount - $booking->down_paid;
                elseif ($booking->payment_plan == "full")
                    $required_amount = $booking->total_amount;

                // Check if the user has enough balance
                if ($user->blance < $required_amount) {
                    return $this->Response(null, __("messages.Balance_not_enough"), 422);
                }

                // Deduct balance from user
                $user->update([
                    "blance" => $user->blance - $required_amount,
                ]);

                // Create transaction record
                Transactions::create([
                    "user_id" => $user->id,
                    "amount" => $required_amount,
                    "transaction_type" => "reservation",
                    "transaction_type_ar" => "حجز",
                    "status" => 1,
                    "attachment" => "static/reservation.png"
                ]);

                $payment_status_id = $booking->payment_plan == "full" ? 3 : ($booking->payment_status_id == 4 ? 3 : 4);
                // Update booking with payment type
                $booking->update([
                    "payment_status_id" =>  $payment_status_id,
                    'payment_type' => "wallet",
                    "down_paid" => $booking->payment_plan == "partial" ? ($booking->down_paid == 0 ?  $required_amount : $booking->down_paid) : 0,
                ]);

                // Generate invoice ID and create a payment record
                $invoice_id = Payment::orderBy("id", "DESC")->first()
                    ? Payment::orderBy("id", "DESC")->first()->invoice_id + 1
                    : 10000;

                Payment::create([
                    "invoice_id" => $invoice_id,
                    "booking_id" => $booking->id,
                    "customer_id" => $request->user()->id,
                    "amount" => $required_amount,
                    "provider_id" => $booking->provider_id,
                    "payment_status_id" => 3,  // Assuming 3 means "paid"
                    "payment_type" => "wallet",
                ]);
                $customer = User::find($request->user()->id);
                $customer->update([
                    "points" => $customer->points +  Setting::find(1)->point_earn_on_each_booking,
                ]);
                User::find($booking->provider_id)->update([
                    "blance" => (User::find($booking->provider_id)->blance) + $booking->amount,
                ]);
                if ($booking->payment_status_id == 3 || $booking->payment_plan == 'partial') {
                    Helpers::delete_days_after_booking($booking->id);
                }
                if ($booking->payment_status_id == 3) {

                    Earning::create([
                        "user_id" => $booking->provider_id,
                        "total_booking" => 1,
                        "total_earning" => $booking->total_amount,
                        "provider_earning" => $booking->amount,
                        'admin_earning' => $booking->taxes,
                        "booking_id" => $booking->id,
                    ]);
                }


                // Commit the transaction if all operations succeed
                DB::commit();

                // Prepare notification data
                $customer_name = $request->user()->name;
                $fcms = FCM::where("user_id", $booking->provider_id)->get();

                $notification_title_en = "Payment ";
                $notification_title_ar = "دفع";
                $notification_description_en = "$user->name paid the reservation via the wallet";
                $notification_description_ar = "$user->name دفع الحجز عبر المحفظة";

                $provider = $booking->provider;
                $lang = $provider->lang;

                foreach ($fcms as $fcm) {
                    $notification_data = [
                        "title" => $lang == 'en' ? $notification_title_en :  $notification_title_ar,
                        "description" => $lang == 'en' ? $notification_description_en :  $notification_description_ar,
                        "fcm" => $fcm->fcm_token ?? ' ',
                        'model_id' => $booking->service_id,
                        "model_type" => 2,
                    ];
                    Helpers::push_notification_owner($notification_data);
                }

                // Store the notification in a list
                $notification_data["title_ar"] = $notification_title_ar;
                $notification_data["title_en"] = $notification_title_en;
                $notification_data["description_en"] = $notification_description_en;
                $notification_data["description_ar"] = $notification_description_ar;
                $notification_data['user_id'] = $booking->provider_id;
                $notification_data['model_type'] = 2;
                $notification_data['model_id'] = $booking->service_id;
                Helpers::push_notification_list($notification_data);

                return $this->Response($booking, "Paid Successfully", 201);
            } else {
                return $this->Response(null, "The owner has not approved yet", 401);
            }
        } catch (\Exception $e) {
            // Rollback the transaction if any exception occurs
            DB::rollBack();

            return $this->Response(null, "Something went wrong: " . $e->getMessage(), 500);
        }
    }


    public function get_price_of_days(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "start_at" => "required",
            "end_at" => "required",
            "service_id" => "required",

        ]);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);

        $startDate = $request->start_at;
        $endDate = $request->end_at;

        // Convert to DateTime objects
        $start = DateTime::createFromFormat('m/d/Y', $startDate);
        $end = DateTime::createFromFormat('m/d/Y', $endDate);
        // $end->modify('+1 day');
        $interval = new DateInterval('P1D');
        $datePeriod = new DatePeriod($start, $interval, $end);
        $dates = [];
        foreach ($datePeriod as $date) {
            $dates[] = $date->format('m/d/Y');
        }


        // check if start_at and end_at range is avaliable
        $days = json_decode(Service::find($request->service_id)->days);
        $days_after_formate = [];
        foreach ($days as $day) {
            $date = Carbon::createFromFormat('m/d/Y h:i A', $day);
            $days_after_formate[] = $date->format('m/d/Y');
        }
        $missingDates = array_diff($dates, $days_after_formate);
        $data = [];
        $eventDays = [];
        $event_days_prices = 0;

        if ($request->filled('booking_id')  == 1) {
            $booking = Booking::find($request->booking_id);
            if (!$booking)
                return $this->Response(null, "Booking Not Found", 422);
            $startDate = Carbon::parse($booking->start_at);
            $endDate = Carbon::parse($booking->end_at);

            // Calculate the total days between the two dates
            $totalDays = $endDate->diffInDays($startDate);

            // Subtract one day from the total
            $adjustedDays = $totalDays;
            $data["total_days"] = $adjustedDays;
            $data["total"] =  (int) $booking->total_amount;
            $data['payment_plan'] = $booking->payment_plan;

            $data['paid'] = $booking->payment_plan == "full" || $booking->payment_status_id == 3 ? (int) $booking->total_amount : (int) $booking->down_paid;

            if ($booking->payment_status_id == 3) {
                $data['remain'] = 0;
            } elseif ($booking->payment_status_id == 4) {
                $data['remain'] = (int)($booking->total_amount - $booking->down_paid);
            } else {
                $data['remain'] = (int)$booking->total_amount;
            }


            $data['down_payment'] = (int) $booking->down_payment;

            return $this->Response($data, "safdas", 201);
        }
        if (count($missingDates) > 0) {
            return $this->Response(["un_avaliable_days" => array_values($missingDates)], __('messages.Days_not_Avaliable'), 200);
        }

        $eventDaysData = ServiceEventDays::where("service_id", $request->service_id)->get();

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
        $service = Service::find($request->service_id);
        if (!$service) {
            return $this->Response(null, __('messages.Service_not_found'), 404);
        }

        $prices_normal_day = $service->regular_price  * count($dates);
        $total = $event_days_prices + $prices_normal_day;
        $commission = Commission::where("provider_id", $service->user_id)->first() ?? Setting::find(1);
        if ($commission) {
            $commission_value = $commission->commission_value;
            if ($commission->commission_type == "percentage") {
                $commission_money = (($total * $commission_value) / 100);
                $commission_event_days = (($event_days_prices * $commission_value) / 100);
            } else {
                $commission_money =  $commission_value * (count($eventDays) + count($dates));
                $commission_event_days = $commission_value * (count($eventDays));
            }
        } else {
            $commission_value = $commission->commission_value;
            if ($commission->commission_type == "percentage") {
                $commission_money = (($total * $commission_value) / 100);
                $commission_event_days = (($event_days_prices * $commission_value) / 100);
            } else {
                $commission_money =  $commission_value * (count($eventDays) + count($dates));
                $commission_event_days = $commission_value * (count($eventDays));
            }
        }
        $data["event_days_details"] = $eventDays;
        $data["total"] = ceil($total + $commission_money);
        $data["event_days_prices"] = $event_days_prices + $commission_event_days;

        $data["total_event_days"] = count($data["event_days_details"]);
        $data["total_normal_days"] = count($dates);

        if ($request->has('coupon_code') || $request->has('booking_id')) {
            $booking = Booking::find($request->booking_id);
            if ($request->has('booking_id') && $booking->coupon_id != null)
                $coupon = Coupon::where("id", $booking->coupon_id)->first();
            else
                $coupon = Coupon::where("coupon_code", $request->coupon_code)->first();

            if ($coupon) {
                if ($request->has('booking_id')  && $booking->coupon_id != null) {
                    if ($coupon->type == "amount")
                        $data["total_after_apply_coupon"] = $data["total"] - $coupon->coupon_value;
                    elseif ($coupon->type == "percentage")
                        $data["total_after_apply_coupon"] = $data["total"] - (($coupon->coupon_value * $data["total"]) / 100);
                } else {

                    $today = date('m/d/Y');
                    if (Carbon::parse($coupon->start_at)->startOfDay()->format('m/d/Y') <= $today && Carbon::parse($coupon->end_at)->endOfDay()->format('m/d/Y') >= $today) {

                        if ($coupon->type == "amount")
                            $data["total_after_apply_coupon"] = $data["total"] - $coupon->coupon_value;
                        elseif ($coupon->type == "percentage")
                            $data["total_after_apply_coupon"] = $data["total"] - (($coupon->coupon_value * $data["total"]) / 100);
                    } else {
                        $data["total_after_apply_coupon"] = __('messages.Coupon_exoired');
                    }
                }
                $data["coupon_type"] = $coupon->type;
                $data['coupon_value'] = $coupon->coupon_value;
            } else {
                $data["total_after_apply_coupon"] = __('messages.Coupon_not_valid');
            }
        }



        return $this->Response($data, "safdas", 201);
    }
    public function cancel_booking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "booking_id" => "required",
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);
        $booking = Booking::where("id", $request->booking_id)->with(['customer'])->first();
        if ($booking->booking_status_id == 5) {
            return $this->Response(null, __('messages.Already_cancelled'), 401);
        }
        if ($booking->booking_status_id == 1) {
            $booking->update([
                'booking_status_id' => 5,
            ]);
            return $this->Response(null, __('messages.Cancelled_successfully', ["refund" => 0, "deduct_amount" => 0]), 201);
        }
        $setting = Setting::find(1);
        $dateFromDatabase = $booking->start_at;
        $currentDate = Carbon::now();

        $hoursDifference = $currentDate->diffInHours($dateFromDatabase);
        if ($hoursDifference > $setting->cancel_within_hours) {

            $payment = Payment::where('booking_id', $booking->id)->first();
            $customer = $booking->customer;
            if ($booking->payment_status_id == 4 || $booking->payment_status_id == 3) {

                $payment->update([
                    "is_cancelled" => true,
                ]);
                $dateFromDatabase = $booking->approved_at; //approved_at ex: 2025-05-27 15:07:15 ,type =timestamp
                $currentDate = Carbon::now();
                $hoursDifference = $currentDate->diffInHours($dateFromDatabase);

                // return full refund
                $refund_blacnce = 0;
                if ($hoursDifference > $setting->refund_full_amount_within_hours) {
                    $amount = $booking->payment_status_id == 4 ?  $booking->down_paid :  $booking->total_amount;
                    $refund_blacnce = round($amount);
                    $message = __("messages.Cancelled_successfully", ["refund" => $refund_blacnce, "deduct_amount" => 0]);
                    $message = __("messages.Cancelled_successfully", [
                        "refund" => $refund_blacnce,
                        "deduct_amount" => 0
                    ]);
                } else {
                    $deduct_an_amount = $setting->deduct_an_amount;
                    $amount = ($booking->payment_status_id == 4 ?  $booking->down_paid :  $booking->total_amount);
                    $cut_amount =  $amount * $deduct_an_amount / 100;

                    $refund_blacnce = round($amount - $cut_amount);
                    $message = __("messages.Cancelled_successfully", [
                        "refund" => $refund_blacnce,
                        "deduct_amount" => $cut_amount
                    ]);
                }
                $customer->update([
                    "blance" => $customer->blance + $refund_blacnce,
                    "points" => $customer->points -  $setting->point_earn_on_each_booking,
                ]);
                $earning = Earning::where('booking_id', $booking->id)->first();
                if ($earning)
                    $earning->update([
                        "is_cancelled" => true,
                    ]);
                Helpers::add_days_after_cancel_request($booking->id);
            }
            $booking->update([
                'booking_status_id' => 5,
            ]);

            return $this->Response(null, $message, 201);
        } else {
            return $this->Response(null, __('messages.Not_allowed_to_cancel_booking'), 401);
        }
    }
}
