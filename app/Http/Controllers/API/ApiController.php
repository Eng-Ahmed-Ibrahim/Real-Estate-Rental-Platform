<?php

namespace App\Http\Controllers\API;

use DateTime;
use Exception;
use App\CPU\Helpers;
use App\Models\User;
use App\Models\Cities;
use App\Models\Booking;
use App\Models\Earning;
use App\Models\Feature;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Sliders;
use App\Models\Favorite;
use App\Models\Categories;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\PropertyTypes;
use App\Models\ServiceEventDays;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\PropertiesServices;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    use ResponseTrait;
    // customer
    private $propertiesServices;
    function __construct(PropertiesServices $propertiesServices)
    {
        $this->propertiesServices = $propertiesServices;
    }
    public function guest_user(Request $request)
    {
        $this->propertiesServices->apply_event_days();

        $categories = Cache::rememberForever('categories', function () {
            return Categories::all();
        });
        $subscribers = Subscription::where("status", 1)->pluck('provider_id')->toArray();
        $filters = [
            "text" => $request->text,
            "min_price" => $request->min_price,
            "max_price" => $request->max_price,
            "min_area" => $request->min_area,
            "max_area" => $request->max_area,
            "bed" => $request->bed,
            "bath" => $request->bath,
            "living_room" => $request->living_room,
            "lat" => $request->lat,
            "long" => $request->long,
            "user" => null,
            "highest_price" => $request->highest_price,
            "lowest_price" => $request->lowest_price,
            "accept" => $request->accept,
            "category_id" => $request->category_id,
            "city_id" => $request->city_id,
            "property_type" => $request->property_type,
            "property" => $request->property,
            "duration" => $request->duration,
            'range_days' => $request->range_days,
            "features" => $request->features ?? [], // make sure features is defined
            "subscribers" => $subscribers, // make sure $subscribers is defined

        ];
        // return $this->Response($filters,"af",201);
        $services = $this->propertiesServices->getProperties($filters);



        $contact_details = Cache::rememberForever('contact_details', function () {
            return Setting::find(1);
        });
        $best_deals = Cache::rememberForever('best_deals', function () {
            return Service::where("is_best_deal", true)->inRandomOrder()->get();
        });
        $data = [
            "categories" => $categories,
            "best_deals" => $best_deals,
            "services" => $services,
            "contact_details" => [
                "owner_name" => $contact_details->owner_name,
                "phone" => $contact_details->phone,
                "whatsapp_phone" => $contact_details->whatsapp_phone,
                "contact_email" => $contact_details->contact_email,
            ],
        ];



        return $this->Response($data, "Data", 201);
    }
    public function home(Request $request)
    {

        $this->propertiesServices->apply_event_days();

        $categories = Cache::rememberForever('categories', function () {
            return Categories::all();
        });
        $subscribers = Subscription::where("status", 1)->pluck('provider_id')->toArray();
        $filters = [
            "text" => $request->text,
            "min_price" => $request->min_price,
            "max_price" => $request->max_price,
            "min_area" => $request->min_area,
            "max_area" => $request->max_area,
            "bed" => $request->bed,
            "bath" => $request->bath,
            "living_room" => $request->living_room,
            "lat" => $request->lat,
            "long" => $request->long,
            "highest_price" => $request->highest_price,
            "lowest_price" => $request->lowest_price,
            "accept" => $request->accept,
            "category_id" => $request->category_id,
            "city_id" => $request->city_id,
            "property_type" => $request->property_type,
            "property" => $request->property,
            'range_days' => $request->range_days,
            "duration" => $request->duration,
            "user" => $request->user(),
            "subscribers" => $subscribers, // make sure $subscribers is defined
            "features" => $request->features ?? [], // make sure features is defined

        ];
        $services = $this->propertiesServices->getProperties($filters);
        $contact_details = Cache::rememberForever('contact_details', function () {
            return Setting::find(1);
        });
        $best_deals = Cache::rememberForever('best_deals', function () {
            return Service::where("is_best_deal", true)->inRandomOrder()->get();
        });
        $data = [
            "categories" => $categories,
            "best_deals" => $best_deals,
            "services" => $services,
            "contact_details" => [
                "owner_name" => $contact_details->owner_name,
                "phone" => $contact_details->phone,
                "whatsapp_phone" => $contact_details->whatsapp_phone,
                "contact_email" => $contact_details->contact_email,
            ],
        ];

        if ($request->user()->power == 'provider') {
            $user_earning = Earning::where("user_id", $request->user()->id)
                ->where("is_cancelled", 0)
                ->sum('provider_earning');
            $report = [
                "total_earnings" => (int) $user_earning,
                "total_booking" => Booking::where("provider_id", $request->user()->id)->where("booking_status_id", 3)->where("payment_status_id", 3)->count(),
                "total_services" => Service::where("user_id", $request->user()->id)->count(),
            ];
            $data['report'] = $report;
        }

        return $this->Response($data, "Data", 201);
    }
    public function service(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "service_id" => "required",
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);
        $service = Service::where("id", $request->service_id)->with(['user', 'eventDays', 'gallery', 'features.feature'])->first();
        if (!$service)
            return $this->Response(null, "not found", 404);
        $contact_details = Setting::find(1);

        $data = [
            "service" => $service,
            "contact_details" => [
                "owner_name" => $contact_details->owner_name,
                "phone" => $contact_details->phone,
            ],
        ];
        return $this->Response($data, "Service Details", 201);
    }
    public function categories(Request $request)
    {
        $categories = Cache::rememberForever('categories', function () {
            return Categories::all();
        });
        return $this->Response($categories, "Categories", 201);
    }
    public function property_types(Request $request)
    {
        $property_Types = PropertyTypes::all();

        return $this->Response($property_Types, "Property Types", 201);
    }
    public function distance(Request $request)
    {
        $degToRad = pi() / 180;
        $user_lat = $request->user_lat * $degToRad;
        $user_lng = $request->user_lng * $degToRad;

        $service_lat = $request->service_lat * $degToRad;
        $service_lng = $request->service_lng * $degToRad;


        $earthRadius = 6371; // Radius of Earth in kilometers

        $deltaLat = $service_lat - $user_lat;
        $deltaLong = $service_lng - $user_lng;

        $a = sin($deltaLat / 2) ** 2 +
            cos($user_lat) * cos($service_lat) *
            sin($deltaLong / 2) ** 2;

        $c = 2 * asin(sqrt($a));

        $distance = ($earthRadius * $c) * 1000 . " m"; // Distance in kilometers
        return $this->Response(["distance" => $distance], "Distance", 201);
    }

    public function setLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "lat" => "required",
            "lng" => "required",

        ]);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);
        User::find($request->user()->id)->update([
            "lat" => $request->lat,
            "lng" => $request->lng,
        ]);
        return $this->Response(null, 'updated successfully', 201);
    }

    public function max_min_price(Request $request)
    {
        $query = DB::table('services')->selectRaw('
            MAX(price) as max_price,
            MIN(price) as min_price,
            MAX(property_size) as max_property_size,
            MIN(property_size) as min_property_size
        ')->first();

        // تحقق من التساوي وخلي min = 0 لو متساويين
        $min_price = $query->min_price == $query->max_price ? 0 : $query->min_price;
        $min_property_size = $query->min_property_size == $query->max_property_size ? 0 : $query->min_property_size;

        $data = [
            "max_price" => $query->max_price,
            "min_price" => $min_price,
            "max_property_size" => $query->max_property_size,
            "min_property_size" => $min_property_size,
        ];

        return $this->Response($data, "Price", 201);
    }
    public function cities()
    {
        $cities = Cache::get("cities");
        return $this->Response($cities, "Price", 201);
    }
    public function sliders()
    {
        $sliders = Cache::rememberForever('sliders', function () {
            return Sliders::orderBy("id", "DESC")->get();
        });
        return $this->Response($sliders, "sliders", 201);
    }
}
