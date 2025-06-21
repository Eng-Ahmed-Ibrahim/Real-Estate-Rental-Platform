<?php

namespace App\Http\Controllers\API;

use DateTime;
use App\Models\User;
use App\Models\Support;
use App\Models\Packages;
use App\Models\Subscription;
use App\Models\Transactions;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\PackageFeatures;
use App\Services\PackageServices;
use App\Models\PackageHasFeatures;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPackages;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Validator;

class PackagesController extends Controller
{
    use ResponseTrait;
    private $PackageServices;
    function __construct(PackageServices $PackageServices)
    {   
        $this->PackageServices = $PackageServices;
    }
    public function index(Request $request)
    {
        $is_subscribed_free_package =
        Subscription::where("provider_id", $request->user()->id)
        ->where("package_id",3)
        ->exists();

        $query = Packages::query();
        if ($is_subscribed_free_package) {
            $query->where("id", "!=", 3);
        } 
        $data=$query->orderBy("id", "DESC")->with(['features.feature'])->get();
        $packages = [];
        

        foreach ($data as $package) {
            $featuresIds = [];
            $features = [];

            foreach ($package->features as $feature) {
                $featuresIds[] = $feature->feature_id;
                $features[] = [
                    "feature" => $feature->feature->feature,
                    "feature_ar" => $feature->feature->feature_ar,
                ];
            }
            $un_features = PackageFeatures::whereNotIn("id", $featuresIds)->get();
            $provider_package = Subscription::where("provider_id", $request->user()->id)->where("package_id", $package->id)->where("status", 1)->exists();
            $packages[] = [
                "id" => $package->id,
                "is_subscribed" => $provider_package ? 1 : 0,
                "name" => $package->name,
                "name_ar" => $package->name_ar,
                "service_limit" => $package->service_limit,
                "image" => $package->image,
                "duration" => $package->duration,
                "price" => $package->price,
                "features" => $features,
                "un_features" => $un_features,
            ];
        }
        return $this->Response($packages, "Packages", 201);
    }
    public function subscribe(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "package_id" => "required",
            "payment_method" => "required",
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors(), "Data Not Valid", 422);
        // Free Package Subscription
        if($request->package_id == 3){
            $is_subscribed_free_package =
            Subscription::where("provider_id", $request->user()->id)
            ->where("package_id",3)
            ->exists();
            if($is_subscribed_free_package){
                return $this->Response(null, "Your free trial for this package has already ended.", 422);
            }
            
        }
        $package = Packages::find($request->package_id);
        if (!$package)
            return $this->Response(null, "Package Not exist", 422);

        if ($request->payment_method == "wallet") {

            $user=User::find($request->user()->id);
            if($user->blance < $package->price){
                return $this->Response(null, __("messages.Balance_not_enough"), 422);
            }
            $user->update([
                "blance" => $user->blance - $package->price,
            ]);
            Transactions::create([
                "user_id" => $user->id,
                "amount" => $package->price,
                "transaction_type" => "package",
                "transaction_type_ar" => "باقه",
                "status"=>1,
                "attachment"=>$package->image,
            ]);
            
            $subscribe=$this->PackageServices->set_package_to_provider($user->id,$package,"paid",true);

            return $this->Response($subscribe, __('messages.Added_successfully'), 201);
        } else {
            $validator = Validator::make($request->all(), [
                "attachment" => "required",
            ]);
            if ($validator->fails())
                return $this->Response($validator->errors(), "Data Not Valid", 422);

            $payment_method_id= PaymentMethod::where("name",$request->payment_method)->orWhere("name_ar",$request->payment_method)->first()->id;
            $lowercased = strtolower($request->payment_method);
            $payment_method = str_replace(' ', '_', $lowercased);

            $name = "1" . time() . str_replace(" ", "_", $request->attachment->getClientOriginalName());
            $request->attachment->move(public_path("files/"), $name);


            $provider_id=$request->user()->id;
            $subscribe=$this->PackageServices->set_package_to_provider($provider_id,$package,"pending",true,$payment_method,"files/$name",$payment_method_id);


            return $this->Response($subscribe, __('messages.Added_successfully'), 201);
        }
    }
    public function provider_packages(Request $request)
    {
        $data = Subscription::where("provider_id", $request->user()->id)->orderBy("id", "DESC")->with(['package', 'package.features.feature'])->get();
        $packages = [];
        foreach ($data as $package) {
            $packages[] = $this->PackageServices->provider_packages($package);
        }
        return $this->Response($packages, "Packages", 201);
    }
}
