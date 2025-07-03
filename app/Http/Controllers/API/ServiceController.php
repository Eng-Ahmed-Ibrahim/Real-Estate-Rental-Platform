<?php

namespace App\Http\Controllers\API;

use DateTime;
use Carbon\Carbon;
use App\CPU\Helpers;
use App\Models\Cities;
use App\Models\Feature;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Commission;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\ServiceFeature;
use App\Models\ServiceGallery;
use App\Models\ServiceEventDays;
use App\Http\Controllers\Controller;
use App\Services\PropertiesServices;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    use ResponseTrait;
    private $propertiesServices;
    function __construct(PropertiesServices $propertiesServices)
    {
       $this->propertiesServices = $propertiesServices;
    }
    public function index()
    {
        $categories = Category::orderBy("id", "DESC")->get();
        $cities = Cities::orderBy("id", "DESC")->get();
        $data = [
            "categories" => $categories,
            "cities" => $cities,
        ];
        return $this->Response($data, "Data ", 201);
    }
    public function check_service_limit(Request $request){
        $subscribe = Subscription::where("provider_id", $request->user()->id)
        ->where("status", 1)
        ->first();

        if ($subscribe) {
            $endSubscribeDate = Carbon::parse($subscribe->end_subscribe);
            if (!Carbon::now()->lessThanOrEqualTo($endSubscribeDate)) {
                $subscribe->update([
                    "status" => 0,
                ]);
                return $this->Response(["check"=>0], __('messages.Renew_package'), 201);
            } else {
                if ($subscribe->service_limit == 0) {
                    return $this->Response(["check"=>0], __('messages.Number_limit_expired'), 201);
                } 
            }
        } else {
            return $this->Response(["check"=>0], __('messages.Subscribe_package'), 201);
        }
        return $this->Response(["check"=>1]," ",201);


    }
    public function service(Request $request)
    {
        $service = Service::where("user_id", $request->user()->id)->where("id", $request->service_id)
        ->with(['booking', 'features', 'gallery', 'review', 'user'])
        ->first();
        if (!$service)
            return $this->Response(null, "Not Allowed ", 403);
        return $this->Response($service, "Service", 201);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name_en" => "required",
            "place_en" => "required",
            "category_id" => "required",
            "living_room" => "required",
            "bed" => "required",
            "bath" => "required",
            "price" => "required",
            "description_en" => "required",
            "days" => "required",
            "latitude" => "required",
            "longitude" => "required",
            "image" => "required",
            "property_type" => "required",
            "property_size" => "required",
            "duration" => "required",
            "property" => "required",
            "city_id" => "required",
            "document" => "required",
        ],);
        if ($validator->fails())
            return $this->Response($validator->errors(), __("messages.Please_complete_all_required_fields"), 422);

        $subscribe = Subscription::where("provider_id", $request->user()->id)
            ->where("status", 1)
            ->first();

        if ($subscribe) {
            $endSubscribeDate = Carbon::parse($subscribe->end_subscribe);
            if (!Carbon::now()->lessThanOrEqualTo($endSubscribeDate)) {
                $subscribe->update([
                    "status" => 0,
                ]);
                return $this->Response(null, __('messages.Renew_package'), 422);
            } else {
                if ($subscribe->service_limit == 0) {
                    return $this->Response(null, __('messages.Number_limit_expired'), 422);
                } else {
                    $subscribe->update([
                        "service_limit" => $subscribe->service_limit - 1,
                    ]);
                }
            }
        } else {
            return $this->Response(null, __('messages.Subscribe_package'), 422);
        }

        // End of check
        
        $days = $request->days;
        
        usort($days, function ($a, $b) {
            $dateA = DateTime::createFromFormat('m/d/Y h:i A', $a);
            $dateB = DateTime::createFromFormat('m/d/Y h:i A', $b);
            return $dateA <=> $dateB;
        });

        $image = "1" . time() . str_replace(" ", "_", $request->image->getClientOriginalName());
        $request->image->move(public_path("files/"), $image);

        $document = "2" . time() . str_replace(" ", "_", $request->document->getClientOriginalName());
        $request->document->move(public_path("files/"), $document);
        
        $service = $this->propertiesServices->createService($request,$image,$document,$days,$request->user()->id);

        if (!$service)
            return $this->Response(null, "Service Not Created", 401);
        
        $event_days = ($request->event_days);
        $event_prices = ($request->event_prices);
        $features = ($request->features);

        if ($request->has('event_days')) {

            for ($i = 0; $i < count($event_days); $i++) {
                $date = DateTime::createFromFormat('m/d/Y g:i A', $event_days[$i]);
                $formattedDate = $date->format('m/d/Y');
                $event = ServiceEventDays::create([
                    "day" => $formattedDate,
                    "price" => $event_prices[$i],
                    "service_id" => $service->id,
                ]);
                if (!$event)
                    return $this->Response(null, "event Not Created", 401);
            }
        }
        if ($request->has("features")) {

            foreach ($features as $feature) {
                $feature = ServiceFeature::create([
                    "service_id" => $service->id,
                    "feature_id" => $feature,
                ]);
                if (!$feature)
                    return $this->Response(null, "feature Not Created", 401);
            }
        }
        if ($request->has('galleries')) {
            foreach ($request->file('galleries') as $key => $image) {
                $name = $key . " " . time() . str_replace(" ", "_", $image->getClientOriginalName());
                $image->move(public_path("files/"), $name);
                ServiceGallery::create([
                    "path" => "files/$name",
                    "service_id" => $service->id,
                    // "type"=>$image->getClientMimeType(),
                ]);
            }
        }
        return $this->Response($service, "Added Successfully", 201);
    }
    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "service_id" => 'required',
        ],);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);


        $service = Service::where("user_id", $request->user()->id)->where("id", $request->service_id)->first();
        if (!$service)
            return $this->Response(null, "Not Allowed ", 403);


        if ($request->hasFile("image")) {
            // Helpers::delete_file($service->image);

            $file = str_replace("files/", "", $service->image);

            File::delete(public_path("files/$file"));

            $name = "1" . time() . str_replace(" ", "_", $request->image->getClientOriginalName());
            $request->image->move(public_path("files/"), $name);
            $service->update([
                "image" => "files/$name",
            ]);
        }
        if ($request->hasFile("document")) {
            // Helpers::delete_file($service->document);
            $file = str_replace("files/", "", $service->document);
            File::delete(public_path("files/$file"));
            $name = "2" . time() . str_replace(" ", "_", $request->document->getClientOriginalName());
            $request->document->move(public_path("files/"), $name);
            $service->update([
                "document" => "files/$name",
            ]);
        }
        if ($request->has('price')) {
            if ($request->price != $service->price) {

                $commission = Commission::where("provider_id", $request->user()->id)->first();
                if ($commission) {
                    $commission_value = $commission->commission_value;
                    $commission_money = ($request->price * $commission_value) / 100;
                    $commission_id = $commission->id;
                    $provider_money = $request->price - $commission_money;
                } else {
                    $commission_value = Setting::find(1)->commission_value;
                    $commission_money = ($request->price * $commission_value) / 100;
                    $commission_id = 1;
                    $provider_money = $request->price - $commission_money;
                }
                $service->update([
                    'commission_id' => $commission_id,
                    "commission_percentage" => $commission_value,
                    "commission_money" => $commission_money,
                    "provider_money" => $provider_money,
                    "regular_price" => $request->price,
                    "price" => $request->price,
                ]);
            }
        }
        if ($request->has('days')) {
            $days = $request->days;
            usort($days, function ($a, $b) {
                $dateA = DateTime::createFromFormat('m/d/Y h:i A', $a);
                $dateB = DateTime::createFromFormat('m/d/Y h:i A', $b);
                return $dateA <=> $dateB;
            });
            $service->update([
                // "days" => json_encode($request->days),
                "days" => json_encode($days),

            ]);
        }
        if ($request->has('range_days')) {
            $service->update([
                "range_days" =>  (string) ($request->range_days),

            ]);
        }


        $service->update([
            "accept"=>2,
            "name" => $request->name_en ?? $service->name,
            "name_ar" => $request->name_ar ?? $service->name_ar,
            "place" => $request->place_en ?? $service->place,
            "place_ar" => $request->place_ar ?? $service->place_ar,
            "category_id" => $request->category_id ?? $service->category_id,
            "living_room" => $request->living_room ?? $service->living_room,
            "bed" => $request->bed ?? $service->bed,
            "bath" => $request->bath ?? $service->bath,
            "description" => $request->description_en ?? $service->description,
            "description_ar" => $request->description_ar ?? $service->description_ar,
            "lat" => $request->latitude ?? $service->lat,
            "lng" => $request->longitude ?? $service->lng,
            "range_days" =>  (string) ($request->range_days),

            "city_id" => $request->city_id ?? $service->city_id,
            "property" => $request->property ?? $service->property,
            "duration" => $request->duration ?? $service->duration,
            "property_type" => $request->property_type ?? $service->property_type,
            "property_size" => $request->property_size ?? $service->property_size,

        ]);
        return $this->Response($service, __("messages.Updated_successfully_under_review"), 201);
    }
    public function add_event_day(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "service_id" => "required",
            "day" => "required",
            "price" => "required",
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);



        $day = ($request->day);
        $price = ($request->price);
        $service = Service::find($request->service_id);
        $date = DateTime::createFromFormat('m/d/Y g:i A', $day);
        if (!$date) {
            return $this->Response(null, "Invalid date format", 422);
        }
        $formattedDay = $date->format('m/d/Y');
        $check = ServiceEventDays::where("day", $formattedDay)->where("service_id", $request->service_id)->first();
        if ($check) {
            $check->update([
                "price" => $request->price,
            ]);
            return $this->Response($check, " Updated Successfully", 201);
        }
        if (!$service) {
            return $this->Response(null, "Service not found", 404);
        }
        $services_days = json_decode($service->days, true);
        $check = ServiceEventDays::where("day", $day)->where("service_id", $request->service_id)->first();
        if ($check)
            return $this->Response($check, "Already Exists", 201);
        if (!in_array($day, $services_days)) {
            // Add the event day to the service days
            $services_days[] = $day;
            // Update the service model
            $service->days = json_encode($services_days);
            $service->save();
        }
        $event = ServiceEventDays::create([
            "day" => $formattedDay,
            "price" => $price,
            "service_id" => $request->service_id,
        ]);

        return $this->Response($event, " Added Successfully", 201);
    }
    public function delete_event_day(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "service_id" => "required",
            "event_id" => "required",
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);
        $event = ServiceEventDays::where("id", $request->event_id)->where("service_id", $request->service_id)->first();
        if ($event)
            $event->delete();
        else
            return $this->Response($event, "Not Found", 404);


        return $this->Response($event, " Deleted Successfully", 201);
    }
    public function add_feature(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "service_id" => "required",
            "feature_id" => "required",
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);

        $service = Service::where("id", $request->service_id)->where("user_id", $request->user()->id)->first();
        if (!$service) {
            return $this->Response(null, "Not Allowed", 422);
        }




        $check = ServiceFeature::where("service_id", $request->service_id)->where('feature_id', $request->feature_id)->first();
        if (!$check) {

            ServiceFeature::create([
                "service_id" => $request->service_id,
                "feature_id" => $request->feature_id,
            ]);
        }


        return $this->Response(null, " Added Successfully", 201);
    }
    public function delete_feature(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "service_id" => "required",
            "feature_id" => "required",
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);


        ServiceFeature::where("service_id", $request->service_id)->where("feature_id", $request->feature_id)->delete();
        return $this->Response(null, " Deleted Successfully", 201);
    }
    public function features()
    {
        $features = Feature::orderBy("id", "DESC")->get();
        return $this->Response($features, "Features", 201);
    }
    public function add_gallery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "service_id" => "required",
            "galleries" => "required|array",
        ],);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);


        foreach ($request->file('galleries') as $image) {
            $name = time() . str_replace(" ", "_", $image->getClientOriginalName());
            $image->move(public_path("files/"), $name);
            ServiceGallery::create([
                "path" => "files/$name",
                "service_id" => $request->service_id,
            ]);
        }
        return $this->Response(null, "Added Successfully", 201);
    }
    public function delete_image_gallery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "service_id" => "required",
            "gallery_id" => "required",
        ],);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);

        $gallery = ServiceGallery::where("id", $request->gallery_id)->where("service_id", $request->service_id)->first();
        $file = str_replace("files/", "", $gallery->path);

        File::delete(public_path("files/$file"));
        $gallery->delete();
        return $this->Response($gallery, "Deleted Successfully", 201);
    }
    public function gallery_features_event_days(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "service_id" => "required",
        ],);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);

        $event_days = ServiceEventDays::where("service_id", $request->service_id)->orderBy("id", "DESC")->get();

        $features = [];

        $service_features = ServiceFeature::where("service_id", $request->service_id)->orderBy("id", "DESC")->get();
        foreach ($service_features as $feature) {
            $features[] = Feature::find($feature->feature_id);
        }


        $gallery = ServiceGallery::where("service_id", $request->service_id)->orderBy("id", "DESC")->get();
        $data = [
            "eventDays" => $event_days,
            "features" => $features,
            "gallery" => $gallery,
        ];
        return $this->Response($data, "Data", 201);
    }
}
