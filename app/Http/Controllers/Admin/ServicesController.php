<?php

namespace App\Http\Controllers\Admin;

use Log;
use DateTime;
use Carbon\Carbon;
use App\Models\FCM;
use App\CPU\Helpers;
use App\Models\User;
use App\Models\Cities;
use App\Models\Booking;
use App\Models\Earning;
use App\Models\Feature;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Categories;
use App\Models\Commission;
use Illuminate\Http\Request;
use App\Models\ServiceFeature;
use App\Models\ServiceGallery;
use App\Models\ServiceReviews;
use App\Models\ServiceEventDays;
use App\Http\Controllers\Controller;
use App\Services\PropertiesServices;
use Illuminate\Support\Facades\Cache;

class ServicesController extends Controller
{
    private $propertiesServices;
    function __construct(PropertiesServices $propertiesServices)
    {
       $this->propertiesServices = $propertiesServices;
    }
    public function index(Request $request)
    {
        $query = Service::query();
        if ($request->has('accept'))
            $query->where("accept", $request->accept);
        if ($request->has("commssion_id"))
            $query->where("commission_id", null);
        $services = $query->orderBy("id", "DESC")->with(["user", "category"])->paginate(15);
        return view('admin.services.index')
            ->with("services", $services);
    }

    public function show($id)
    {
        $service = Service::where("id", $id)->with(['user', 'features'])->first();
        if (!$service) {
            session()->flash('error', __("messages.Not_found"));
            return back();
        }
        $reviews = ServiceReviews::where("service_id", $service->id)->orderBy("id", "DESC")->with(['user', 'service'])->get();
        $earning = Earning::where('service_id', $service->id)
            ->where("is_cancelled", 0)
            ->sum('total_earning');
        $rating = 0;
        $avg_rating = 0;
        if (count($reviews) > 0) {

            foreach ($reviews as $review) {
                $rating += $review->rating;
            }
            $avg_rating = $rating / count($reviews);
        }
        return view('admin.services.view')
            ->with('service', $service)
            ->with('reviews', $reviews)
            ->with('earning', $earning)
            ->with('avg_rating', $avg_rating);
    }

    public function create()
    {
        $categories = Cache::get("categories");
                $commissions = Cache::rememberForever('commissions',function(){
            return Commission::select('id', 'commission_name')->orderBy('id', 'DESC')->get();
        });
        // Commission::orderBy("id", "DESC")->get()
                $providers = Cache::rememberForever('providers',function(){
            return User::select('id', 'name')->where('power', 'provider')->get();
        });

         $features =Cache::rememberForever('features', function () {
                return Feature::select('id', 'feature_name','feature_name_ar')->orderBy('id', 'DESC')->get();
            });;
                $cities = Cache::rememberForever("cities",function(){
           return  Cities::orderBy("id","DESC")->get();
        });
        // Cities::orderBy("id","DESC")->get()
        return view('admin.services.create')
            ->with("categories", $categories)
            ->with("features", $features)
            ->with("cities", $cities)
            ->with("commissions", $commissions)
            ->with("providers", $providers);
    }
    public function store(Request $request)
    {
        // return $request;

        $request->validate(
            [
                "name_en" => "required",
                "name_ar" => "required",
                "place_en" => "required",
                "category_id" => "required",
                "provider_id" => "required",
                "living_room" => "required",
                "bed" => "required",
                "bath" => "required",
                "price" => "required",
                "description_en" => "required",
                "days" => "required",
                "longitude" => "required",
                "image" => "required",
                "property_size" => "required",
                "property_type" => "required",
                "duration" => "required",
                "property" => "required",
                "city_id" => "required",
                "document" => "required",
            ],
            [
                "name_en" => __('messages.Validate_name_en'),
                "name_ar" => __('messages.Validate_name_ar'),
                "image" => __('messages.Validate_image'),
                "place_en" => __('messages.Validate_place'),
                "category_id" => __('messages.Validate_category_id'),
                "provider_id" => __('messages.Validate_provider_id'),
                "living_room" => __('messages.Validate_living_room'),
                "bed" => __('messages.Validate_bed'),
                "bath" => __('messages.Validate_bath'),
                "price" => __('messages.Validate_price'),
                "description_en" => __('messages.Validate_description'),
                "days" => __('messages.Validate_days'),
                "longitude" => __('messages.Validate_location'),
            ]
        );

        // Split the string into an array based on the delimiter ";"
        $daysArray = explode(";", $request->days);
        // Trim whitespace around each element to ensure clean results
        $days = array_map('trim', $daysArray);
        usort($days, function ($a, $b) {
            $dateA = DateTime::createFromFormat('m/d/Y h:i A', $a);
            $dateB = DateTime::createFromFormat('m/d/Y h:i A', $b);
            return $dateA <=> $dateB;
        });


        $image = "1" . time() . str_replace(" ", "_", $request->image->getClientOriginalName());
        $request->image->move(public_path("files/"), $image);

        $document = "2" . time() . str_replace(" ", "_", $request->document->getClientOriginalName());
        $request->document->move(public_path("files/"), $document);

        //  ($request, $image, $document, $days, $provider_id )
        $service = $this->propertiesServices->createService($request,$image,$document,$days,$request->provider_id);
        
        if ($request->has('event_days') && count(json_decode($request->event_days)) > 0 && $request->event_day_price > 0)
            foreach (json_decode($request->event_days) as $event) {
                ServiceEventDays::create([
                    "service_id" => $service->id,
                    "day" => $event,
                    "price" => $request->event_day_price,
                    "status" => 0,
                ]);
            }
        if ($request->has('features_id'))

            foreach (json_decode($request->features_id) as $feature_id) {
                ServiceFeature::create([
                    "service_id" => $service->id,
                    "feature_id" => $feature_id,
                ]);
            }
        if ($request->hasFile('galleries')) {

            foreach ($request->file('galleries') as $image) {
                $name = time() . str_replace(" ", "_", $image->getClientOriginalName());
                $image->move(public_path("files/"), $name);
                ServiceGallery::create([
                    "path" => "files/$name",
                    "service_id" => $service->id,

                ]);
            }
        }
        session()->flash("success", __('messages.Added_successfully'));
        return back();
    }
    public function edit(string $id)
    {
        // Eager load related models and necessary fields only
        $service = Service::with([
            'gallery' => function ($query) {
                $query->latest();
            },
            'features',
            'eventDays',
        ])->findOrFail($id); // Use findOrFail for direct retrieval
    
        // Load necessary data with limited fields
        $categories = Cache::get('categories');
        // $commissions = Cache::rememberForever('commissions',function(){
        //     Commission::select('id', 'commission_name')->orderBy('id', 'DESC')->get();
        // });
        $providers = Cache::rememberForever('providers',function(){
            return User::select('id', 'name')->where('power', 'provider')->get();
        });
        
        $eventDays = $service->eventDays;
        $features =Cache::rememberForever('features', function () {
                return Feature::select('id', 'feature_name','feature_name_ar')->orderBy('id', 'DESC')->get();
            });
        $service_features = $service->features;
        $customers = Cache::rememberForever("customers",function(){
            return User::select('id', 'name')->where('power', 'customer')->orderBy('id', 'DESC')->get();
        });
        $cities = Cache::rememberForever("cities",function(){
            return Cities::orderBy("id","DESC")->get();
        });
        // Initialize calendar dates array
        $calendar_dates = [];
    
        // Process available days for calendar
        $available_days = json_decode($service->days);
        if ($available_days) {
            foreach ($available_days as $day) {
                $carbonDay = Carbon::createFromFormat('m/d/Y h:i A', str_replace('0:00 AM', '12:00 AM', $day));
    
                $calendar_dates[] = [
                    'title' => $carbonDay->isPast() ? __('messages.Not_Available') : __('messages.Available'),
                    'start' => $carbonDay->format('Y-m-d H:i:s'),
                    'end' => $carbonDay->copy()->addHours(12)->format('Y-m-d H:i:s'),
                    'color' => $carbonDay->isPast() ? 'red' : 'orange',
                ];
            }
        }
    
        // Add bookings to calendar dates
        $bookings = Booking::with('customer:id,name')->where('service_id', $id)->where('payment_status_id', 3)->orderBy('id', 'DESC')->get();
        foreach ($bookings as $booking) {
            $calendar_dates[] = [
                'title' => 'Booked By ' . $booking->customer->name,
                'start' => Carbon::parse($booking->start_at)->addHours(12)->format('Y-m-d H:i:s'),
                'end' => Carbon::parse($booking->end_at)->addHours(12)->format('Y-m-d H:i:s'),
                'color' => 'green',
                'textColor' => 'white',
                'url' => route('admin.profile', $booking->customer_id),
            ];
        }
    
        // Add event days to calendar dates
        foreach ($eventDays as $eventDay) {
            $calendar_dates[] = [
                'title' => __('messages.Event_day'),
                'start' => Carbon::parse($eventDay->day)->addHours(12)->format('Y-m-d H:i:s'),
                'end' => Carbon::parse($eventDay->day)->addHours(12)->format('Y-m-d H:i:s'),
                'color' => 'black',
                'textColor' => 'white',
            ];
        }
    
        return view('admin.services.edit', [
            'categories' => $categories,
            // 'commissions' => $commissions,
            'features' => $features,
            'service_features' => $service_features,
            'providers' => $providers,
            'service' => $service,
            'eventDays' => $eventDays,
            'customers' => $customers,
            'calendar_dates' => $calendar_dates,
            'cities' => $cities,
        ]);
    }
    
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                "name_en" => "required",
                "name_ar" => "required",
                "place_en" => "required",
                "category_id" => "required",
                "provider_id" => "required",
                "living_room" => "required",
                "bed" => "required",
                "bath" => "required",
                "price" => "required",
                "description_en" => "required",
            ],
            [
                "name_en" => __('messages.Validate_name_en'),
                "name_ar" => __('messages.Validate_name_ar'),
                "place_en" => __('messages.Validate_place'),
                "category_id" => __('messages.Validate_category_id'),
                "provider_id" => __('messages.Validate_provider_id'),
                "living_room" => __('messages.Validate_living_room'),
                "bed" => __('messages.Validate_bed'),
                "bath" => __('messages.Validate_bath'),
                "price" => __('messages.Validate_price'),
                "description_en" => __('messages.Validate_description'),
            ]
        );

        $service = Service::find($id);
        if (!$service)
            return back();

        $daysArray = explode(";", $request->days);
        $days = array_map('trim', $daysArray);
        usort($days, function ($a, $b) {
            $dateA = DateTime::createFromFormat('m/d/Y h:i A', $a);
            $dateB = DateTime::createFromFormat('m/d/Y h:i A', $b);
            return $dateA <=> $dateB;
        });


        if ($request->hasFile("image")) {
            Helpers::delete_file($service->image);
            $service->update([
                "image" => Helpers::upload_files($request->image),

            ]);
        }
        if ($request->hasFile("document")) {
            Helpers::delete_file($service->document);
            $service->update([
                "document" => Helpers::upload_files($request->document),

            ]);
        }
        if ($request->hasFile('galleries')) {

            foreach ($request->file('galleries') as $image) {
                $name = time() . str_replace(" ", "_", $image->getClientOriginalName());
                $image->move(public_path("files/"), $name);
                ServiceGallery::create([
                    "path" => "files/$name",
                    "service_id" => $service->id,
                ]);
            }
        }

        if ($request->price != $service->price) {

            $commission = Commission::where("provider_id", $request->provider_id)->first() ?? Setting::find(1);
            if ($commission) {
                $commission_id = $commission->id;
                $commission_value = $commission->commission_value;
                $provider_money = $request->price;
    
                if ($commission->commission_type == "percentage")
                    $commission_money = ($request->price * $commission_value) / 100;
                else
                    $commission_money =  $commission_value;
    
            } else {
                $commission_value = $commission->commission_value;
                $commission_id = 1;
                $provider_money = $request->price;
    
                if ($commission->commission_type == "percentage")
                    $commission_money = ($request->price * $commission_value) / 100;
                else
                    $commission_money =  $commission_value;
                
            }


            $service->update([
                'commission_id' => $commission_id,
                "commission_percentage" => $commission_value,
                "commission_money" => $commission_money,
                "provider_money" => $provider_money,
                "regular_price" => $request->price,
                "price" => $request->price ,
            ]);
        }




        $service->update([
            "name" => $request->name_en,
            "name_ar" => $request->name_ar,
            "place" => $request->place_en,
            "place_ar" => $request->place_ar,
            "category_id" => $request->category_id,
            "user_id" => $request->provider_id,
            "living_room" => $request->living_room,
            "bed" => $request->bed,
            "bath" => $request->bath,
            "description" => $request->description_en,
            "description_ar" => $request->description_ar,
            "days" => json_encode($days),
            "lat" => $request->latitude ?? $service->lat,
            "lng" => $request->longitude ?? $service->lng,
            "accept" => $request->has('accept') ? 1 : 0,
            "city_id"=>$request->city_id,
            "property"=>$request->property,
            "duration"=>$request->duration,
            "property_type"=>$request->property_type,
            "property_size"=>$request->property_size,

        ]);

        $service_name_en = $service->name;
        $service_name_ar = $service->name_ar;
        $fcms = FCM::where("user_id", $service->user_id)->get();

        $lang = User::find($service->user_id)->lang;
        if ($request->has('accept')) {
            $notification_title_translations = [
                'en' => __('messages.service_accepted_title', [], 'en'),
                'ar' => __('messages.service_accepted_title', [], 'ar'),
            ];
            $notification_description_translations = [
                'en' => __('messages.service_accepted_description', ['service_name' => $service_name_en], 'en'),
                'ar' => __('messages.service_accepted_description', ['service_name' => $service_name_en], 'ar'),
            ];
        } else {
            $notification_title_translations = [
                'en' => __('messages.service_rejected_title', [], 'en'),
                'ar' => __('messages.service_rejected_title', [], 'ar'),
            ];
            $notification_description_translations = [
                'en' => __('messages.service_rejected_description', ['service_name' => $service_name_ar], 'en'),
                'ar' => __('messages.service_rejected_description', ['service_name' => $service_name_ar], 'ar'),
            ];
        }
        $notification_title_en = $notification_title_translations['en'];
        $notification_title_ar = $notification_title_translations['ar'];
        $notification_description_en = $notification_description_translations['en'];
        $notification_description_ar = $notification_description_translations['ar'];
        $notification_title = $notification_title_translations[$lang];
        $notification_description = $notification_description_translations[$lang];

        foreach ($fcms as $fcm) {
            $notification_data = [
                "title" => $notification_title,
                "description" => $notification_description,
                'fcm' => $fcm->fcm_token,
                'model_id' => $service->id,
                'model_type' => 1,
            ];
            Helpers::push_notification_owner($notification_data);
        }

        $notification_data["title_ar"] = $notification_title_ar;
        $notification_data["title_en"] = $notification_title_en;
        $notification_data["description_en"] = $notification_description_en;
        $notification_data["description_ar"] = $notification_description_ar;
        $notification_data['user_id'] = $service->user_id;

        $notification_data['model_type'] = 1;
        $notification_data['model_id'] = $service->id;
        Helpers::push_notification_list($notification_data);

        session()->flash("success", __('messages.Updated_successfully'));
        return back();
    }

    public function destroy(string $id)
    {
        $service = Service::find($id);
        if ($service) {
            Helpers::delete_file($service->image);
            $service->delete();
            session()->flash("success", __('messages.Deleted_successfully'));
            return back();
        } else {
            session()->flash("error", __('messages.Not_found'));
            return back();
        }
    }
    public function add_event(Request $request)
    {
        $request->validate([
            "id" => "required",
            "day" => "required",
            "price" => "required",
        ]);
        $check = ServiceEventDays::where("day", $request->day)->where("service_id", $request->id)->first();
        if ($check) {
            session()->flash("error", __('messages.Already_added'));
            return back();
        }
        ServiceEventDays::create([
            "day" => $request->day,
            "price" => $request->price,
            "service_id" => $request->id,
        ]);
        session()->flash("success", __('messages.Added_successfully'));
        return back();
    }
    public function delete_event(Request $request)
    {
        $request->validate([
            "id" => "required",
        ]);
        $event = ServiceEventDays::find($request->id);
        if (!$event) {
            return back();
        }
        $event->delete();
        session()->flash("success", __('messages.Deleted_successfully'));
        return back();
    }

    public function change_accept($service_id)
    {


        $service = Service::find($service_id);

        if ($service->accept == 1) {

            $service->update([
                'accept' => 0,
            ]);
        } else {

            $service->update([
                'accept' => 1,
            ]);
        }
        $service_name_en = $service->name;
        $service_name_ar = $service->name_ar;
        $fcms = FCM::where("user_id", $service->user_id)->get();

        $lang = User::find($service->user_id)->lang;

        // Define translation arrays
        $notification_title_translations = [
            'en' => [
                1 => __('messages.service_accepted_title', [], 'en'),
                0 => __('messages.service_rejected_title', [], 'en'),
            ],
            'ar' => [
                1 => __('messages.service_accepted_title', [], 'ar'),
                0 => __('messages.service_rejected_title', [], 'ar'),
            ],
        ];

        $notification_description_translations = [
            'en' => [
                1 => __('messages.service_accepted_description', ['service_name' => $service_name_en], 'en'),
                0 => __('messages.service_rejected_description', ['service_name' => $service_name_en], 'en'),
            ],
            'ar' => [
                1 => __('messages.service_accepted_description', ['service_name' => $service_name_ar], 'ar'),
                0 => __('messages.service_rejected_description', ['service_name' => $service_name_ar], 'ar'),
            ],
        ];

        // Ensure the accept value is valid
        $service_accept = $service->accept == 1 ? 1 : 0;

        $notification_title = $notification_title_translations[$lang][$service_accept];
        $notification_description = $notification_description_translations[$lang][$service_accept];
        $notification_title_en = $notification_title_translations['en'][$service_accept];
        $notification_title_ar = $notification_title_translations['ar'][$service_accept];
        $notification_description_en = $notification_description_translations['en'][$service_accept];
        $notification_description_ar = $notification_description_translations['ar'][$service_accept];

        foreach ($fcms as $fcm) {
            $notification_data = [
                "title" => $notification_title,
                "description" => $notification_description,
                'fcm' => $fcm->fcm_token,
                'model_id' => $service->id,
                'model_type' => 1,
            ];
            Helpers::push_notification_owner($notification_data);
        }

        // Add both language versions to notification data for push_notification_list
        $notification_data["title_ar"] = $notification_title_ar;
        $notification_data["title_en"] = $notification_title_en;
        $notification_data["description_en"] = $notification_description_en;
        $notification_data["description_ar"] = $notification_description_ar;
        $notification_data['user_id'] = $service->user_id;
        $notification_data['model_type'] = 1;
        $notification_data['model_id'] = $service->id;
        Helpers::push_notification_list($notification_data);


        session()->flash('success', __('messages.Updated_successfully'));
        return back();
    }
    public function disabled_service($service_id)
    {

        

        $service = Service::find($service_id);

        if ($service->disabled == 1) {

            $service->update([
                'disabled' => 0,
            ]);
        } else {

            $service->update([
                'disabled' => 1,
            ]);
        }
        $service_name_en = $service->name;
        $service_name_ar = $service->name_ar;
        $fcms = FCM::where("user_id", $service->user_id)->get();

        $lang = User::find($service->user_id)->lang;

        // Define translation arrays
        $notification_title_translations = [
            'en' => [
                1 => __('messages.service_disabled_title', [], 'en'),
                0 => __('messages.service_enabled_title', [], 'en'),
            ],
            'ar' => [
                1 => __('messages.service_disabled_title', [], 'ar'),
                0 => __('messages.service_enabled_title', [], 'ar'),
            ],
        ];

        $notification_description_translations = [
            'en' => [
                1 => __('messages.service_disabled_description', ['service_name' => $service_name_en], 'en'),
                0 => __('messages.service_enabled_description', ['service_name' => $service_name_en], 'en'),
            ],
            'ar' => [
                1 => __('messages.service_disabled_description', ['service_name' => $service_name_ar], 'ar'),
                0 => __('messages.service_enabled_description', ['service_name' => $service_name_ar], 'ar'),
            ],
        ];

        // Ensure the accept value is valid
        $service_accept = $service->accept == 1 ? 1 : 0;

        $notification_title = $notification_title_translations[$lang][$service_accept];
        $notification_description = $notification_description_translations[$lang][$service_accept];
        $notification_title_en = $notification_title_translations['en'][$service_accept];
        $notification_title_ar = $notification_title_translations['ar'][$service_accept];
        $notification_description_en = $notification_description_translations['en'][$service_accept];
        $notification_description_ar = $notification_description_translations['ar'][$service_accept];

        foreach ($fcms as $fcm) {
            $notification_data = [
                "title" => $notification_title,
                "description" => $notification_description,
                'fcm' => $fcm->fcm_token,
                'model_id' => $service->id,
                'model_type' => 1,
            ];
            Helpers::push_notification_owner($notification_data);
        }

        // Add both language versions to notification data for push_notification_list
        $notification_data["title_ar"] = $notification_title_ar;
        $notification_data["title_en"] = $notification_title_en;
        $notification_data["description_en"] = $notification_description_en;
        $notification_data["description_ar"] = $notification_description_ar;
        $notification_data['user_id'] = $service->user_id;
        $notification_data['model_type'] = 1;
        $notification_data['model_id'] = $service->id;
        Helpers::push_notification_list($notification_data);


        session()->flash('success', __('messages.Updated_successfully'));
        return back();
    }
    public function best_deal($service_id)
    {


        $service = Service::find($service_id);

        $service->update([
            "is_best_deal"=>$service->is_best_deal==true ? false : true,
        ]);
        Cache::forget('best_deals');
        $best_deals = Cache::rememberForever('best_deals', function () {
            return Service::where("is_best_deal", true)->inRandomOrder()->get();
        });

        session()->flash('success', __('messages.Updated_successfully'));
        return back();
    }
    public function add_feature(Request $request)
    {
        $request->validate([
            "service_id" => "required",
            "feature_id" => "required",
        ]);
        $service = Service::find($request->service_id);
        if ($service) {
            $check = ServiceFeature::where("service_id", $request->service_id)->where('feature_id', $request->feature_id)->first();
            if ($check) {
                session()->flash("error", __('messages.Already_added'));
                return back();
            }
            ServiceFeature::create([
                "service_id" => $request->service_id,
                "feature_id" => $request->feature_id,
            ]);
            session()->flash("success", __('messages.Added_successfully'));
            return back();
        };
        session()->flash('error', __("messages.Not_found"));
        return back();
    }
    public function delete_feature(Request $request)
    {
        $request->validate([
            "feature_id" => "required",
        ]);

        ServiceFeature::destroy($request->feature_id);
        session()->flash("success", __('messages.Deleted_successfully'));
        return back();
    }
    public function delete_gallery_image($id)
    {

        Helpers::delete_file(ServiceGallery::find($id)->path);
        ServiceGallery::destroy($id);
        session()->flash("success", __('messages.Deleted_successfully'));
        return back();
    }
}
