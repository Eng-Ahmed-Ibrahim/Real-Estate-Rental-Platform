<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\CPU\Helpers;
use App\Models\Logs;
use App\Models\User;
use App\Models\Booking;
use App\Models\Earning;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Packages;
use App\Models\Commission;
use App\Models\AssignUsers;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\ServiceReviews;
use App\Models\WithdrawEarning;
use App\Services\PackageServices;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
    private $PackageServices;
    function __construct(PackageServices $PackageServices)
    {
        $this->PackageServices = $PackageServices;
    }
    public function index($id, Request $request)
    {
        $user = User::where("id",$id)->with(['service'])->first();
        if (!$user) {
            session()->flash("error", __('Not_found'));
            return back();
        }
        if (count($user->roles) == 0)
            $user->assignRole($user->power);

        if ($user->roles->first()->name == 'customer') {
            if (!$request->has('section') || $request->section == 'orders') {

                $orders = Booking::where("customer_id", $user->id)->orderBy("id", "DESC")->with(['provider', 'customer', 'service'])->get();

                return view('admin.profile.customers.orders')
                    ->with("orders", $orders)
                    ->with("user", $user);
            } elseif ($request->section == 'rating') {
                $ratings = ServiceReviews::where("user_id", $user->id)->orderBy("id", "DESC")->with(['provider', 'service'])->get();
                return view('admin.profile.customers.rating')
                    ->with("ratings", $ratings)
                    ->with("user", $user);
            } elseif ($request->section == 'calendar') {
                $ratings = ServiceReviews::where("user_id", $user->id)->orderBy("id", "DESC")->with(['provider', 'service'])->get();
                $calendar_dates = array();
                $bookings = Booking::where("customer_id", $user->id)->orderBy("id", "DESC")->with(['customer'])->get();
                foreach ($bookings as $booking) {

                    $service_name = session('lang') == 'en'  ? Service::find($booking->service_id)->name :  Service::find($booking->service_id)->name_ar;
                    $calendar_dates[] = [
                        "title" => $service_name,
                        "start" => Carbon::parse($booking->start_at)->addHours(12)->format('Y-m-d H:i:s'),
                        "end" => Carbon::parse($booking->end_at)->subDays(1)->addHours(12)->format('Y-m-d H:i:s'),
                        "color" => $booking->payment_status_id == 1 ? "red" : ($booking->payment_status_id == 2 ? "orange" : "green"),
                        "textColor" => "white",
                        'url' => route('admin.services.edit', $booking->service_id),
                    ];
                }

                return view('admin.profile.customers.calendar')
                    ->with("ratings", $ratings)
                    ->with("user", $user)
                    ->with("calendar_dates", $calendar_dates);
            }
        } elseif ($user->roles->first()->name == 'provider') {

            $tab = null;

            $earnings = Earning::where("user_id", $user->id)->where("is_cancelled", 0)->get();
            $user_earning = 0;
            $admin_earning = 0;
            foreach ($earnings as $earning) {
                $user_earning += $earning->provider_earning;
                $admin_earning += $earning->admin_earning;
            }
            $total_services = count(Service::where("user_id", $user->id)->get());
            $services = $user->service;
            $ratings  = $user->reviews()->with(['service', 'user'])->orderBy('id', 'DESC')->get();
            
            $commission = Commission::where("provider_id", $user->id)->first() ?? Setting::find(1);
            
            $payment_methods = PaymentMethod::where("status", 1)->get();
        
            $data_withdraw = $user->earning()
                ->selectRaw('status, SUM(amount) as amount')
                ->whereIn('status', [1, 2])
                ->groupBy('status')
                ->get()
                ->keyBy('status');
            
            $withdraw_pending = $data_withdraw[2]->amount ?? 0;
            $withdrawn = $data_withdraw[1]->amount ?? 0;
            // $withdraw_pending = WithdrawEarning::where("user_id", $user->id)->where("status", 2)->sum('amount');
            // $withdrawn = WithdrawEarning::where("user_id", $user->id)->where("status", 1)->sum('amount');

            $customers = Cache::rememberForever("customers", function () {
                return User::select('id', 'name')->where('power', 'customer')->orderBy('id', 'DESC')->get();
            });

            $packages_list=Cache::rememberForever("packages",function(){
                return Packages::orderBy("id","DESC")->get();
            });
            $calendar_dates = array();
            $bookings = Booking::where("provider_id", $user->id)->where('payment_status_id', 3)->orderBy("id", "DESC")->with(['customer','service'])->get();
            foreach ($bookings as $booking) {

                $service_name = session('lang') == 'en'  ? $booking->service->name :  $booking->service->name_ar;
                $calendar_dates[] = [
                    "title" => "Booked By " . $booking->customer->name . " -  $service_name",
                    "start" => Carbon::parse($booking->start_at)->addHours(12)->format('Y-m-d H:i:s'),
                    "end" => Carbon::parse($booking->end_at)->subDays(1)->addHours(12)->format('Y-m-d H:i:s'),
                    "color" => "green",
                    "textColor" => "white",
                    'url' => route('admin.profile', $booking->customer_id),
                ];
            }
            $employees = User::where("power", "employee")->orderBy("id", "DESC")->get();
            $logs = Logs::where("provider_id", $user->id)->orderBy("id", "DESC")->with(['employee'])->get();
            $packages = Subscription::where("provider_id", $user->id)->orderBy("id", "DESC")->with(['provider:name,id', 'package:id,name,name_ar'])->get();
            $overview_time = $user->overview_time > 0 ? $user->overview_time : Setting::find(1)->overview_time;



            $query = Booking::query();

            if ($request->has('date')) {
                if ($request->date == 'daily') {
                    $query->daily();
                } elseif ($request->date == 'weekly') {
                    $query->weekly();
                } elseif ($request->date == 'monthly') {
                    $query->monthly();
                }
                $tab = "booking";
            }

            if ($request->has('from') && $request->has('to')) {
                $startDate = Carbon::parse($request->from);
                $endDate = Carbon::parse($request->to);
                $query->whereBetween("created_at", [$startDate, $endDate]);
                $tab = "booking";
            }

            // Apply filters for provider_id and relationships
            $query->where("provider_id", $user->id)->with(['service'])->orderBy("id", "DESC");

            // Get the filtered orders
            $orders = $query->get();

            // Calculate totals with the same filters applied
            $total_pending_requests = $query->clone()->where("booking_status_id", 1)->count();
            $total_approved_requests = $query->clone()->where("booking_status_id", 3)->count();
            $total_rejected_requests = $query->clone()->where("booking_status_id", 4)->count();
            $total_cancelled_requests = $query->clone()->where("booking_status_id", 5)->count();
            $total_overview_time = $query->clone()->where("booking_status_id", 6)->count();
            $total_overview_time_payment = $query->clone()->where("booking_status_id", 7)->count();


            return view('admin.profile.provider')
                ->with("total_overview_time", $total_overview_time)
                ->with("total_overview_time_payment", $total_overview_time_payment)
                ->with("tab", $tab)
                ->with('packages', $packages)
                ->with('overview_time', $overview_time)
                ->with('logs', $logs)
                ->with('employees', $employees)
                ->with('calendar_dates', $calendar_dates)
                ->with('total_cancelled_requests', $total_cancelled_requests)
                ->with('total_approved_requests', $total_approved_requests)
                ->with('total_rejected_requests', $total_rejected_requests)
                ->with('total_pending_requests', $total_pending_requests)
                ->with('user', $user)
                ->with('user_earning', $user_earning)
                ->with('admin_earning', $admin_earning)
                ->with('total_services', $total_services)
                ->with('services', $services)
                ->with('orders', $orders)
                ->with('payment_methods', $payment_methods)
                ->with('commission', $commission)
                ->with('ratings', $ratings)
                ->with('withdraw_pending', $withdraw_pending)
                ->with('withdrawn', $withdrawn)
                ->with('packages_list', $packages_list)
                ->with('customers', $customers);
        } elseif ($user->roles->first()->name == 'employee') {
            $assings = AssignUsers::where("employee_id", $user->id)->with(['provider', 'employee'])->get();
            $providers = User::where("power", "provider")->orderBy("id", "DESC")->get();
            $logs = Logs::where("employee_id", $user->id)->orderBy("id", "DESC")->with(['provider'])->get();
            return view('admin.profile.employee')
                ->with("user", $user)
                ->with("logs", $logs)
                ->with("assings", $assings)
                ->with("providers", $providers);
        } else {
            $roles = Role::all();
            return view('admin.profile.profile')
                ->with("user", $user)
                ->with("roles", $roles)
            ;
        }
    }
    public function edit($id, Request $request)
    {
        $user = User::find($id);
        if (!$user) {
            session()->flash("error", __('Not_found'));
            return back();
        }
        $roles = Role::all();

        return view('admin.profile.profile')
            ->with("user", $user)
            ->with("roles", $roles)
        ;
    }

    public function block_user($user_id, Request $request)
    {
        $user = User::find($request->user_id);
        if ($user->blocked == 1) {

            $user->update([
                'blocked' => 0,
            ]);
            session()->flash("success", __('messages.Profile_unblocked_suessfully'));
        } else {

            $user->update([
                'blocked' => 1,
            ]);
            session()->flash("success", __('messages.Profile_blocked_suessfully'));
        }


        return back();
    }
    public function update_profile($user_id, Request $request)
    {
        // if($user_id != Auth::user()->id){
        //     session()->flash("error",__('messages.Not_allowed'));
        //     return back();
        // }
        $user = User::find($user_id);
        if ($request->section == 1) {
            $request->validate([
                "name" => "required",
                // "email"=>"required",
            ]);
            if ($request->hasFile('image')) {
                Helpers::delete_file($user->image);
                $user->update([
                    "image" => Helpers::upload_files($request->image),
                ]);
            }
            $user->update([
                "name" => $request->name,
                "email" => $request->email,
            ]);
            if ($request->filled('role')) {
                $user->syncRoles([$request->role]); // This removes old roles and assigns the new one
            }
            session()->flash("success", __("messages.Updated_successfully"));
            return back();
        } elseif ($request->section == 2) {
            $request->validate([
                'confirmemailpassword' => "required",
                "phone" => "required|unique:users,phone",
            ]);


            if ($user && Hash::check($request->confirmemailpassword, $user->password)) {
                $user->update([
                    "phone" => $request->phone,
                ]);
                session()->flash("success", __("messages.Updated_successfully"));
                return back();
            } else {
                session()->flash("error", __('messages.The_password_is_incorrect'));
                return back();
            }
        } elseif ($request->section == 3) {
            $request->validate([
                "new_password" => 'required',
                'confirm_password' => "required",
            ]);
            if ($request->new_password != $request->confirm_password) {
                session()->flash('error', __('messages.Password_not_match'));
                return back();
            }
            $user->update([
                "password" => bcrypt($request->new_password),
            ]);
            session()->flash("success", __("messages.Updated_successfully"));
            return back();
        }
    }
    public function add_commissin_to_provider($user_id, Request $request)
    {
        $request->validate([
            "commission" => "required",
        ]);
        $commission = Commission::where("provider_id", $user_id)->first();
        if ($commission) {
            $commission->update([
                "commission_value" => $request->commission,
                "commission_type" => $request->commission_type,
            ]);
        } else {
            $commission = Commission::create([
                "provider_id" => $user_id,
                "commission_value" => $request->commission,
                "commission_type" => $request->commission_type,
            ]);
        }
        $services = Service::where("user_id", $user_id)->get();

        foreach ($services as $service) {

            $commission_value = $request->commission;
            if ($request->commission_type == "percentage")
                $commission_money = ($service->regular_price * $commission_value) / 100;
            else
                $commission_money =  $commission_value;
            $service->update([
                "commission_percentage" => $commission_money,
                "commission_money" => $commission_money,
                "commission_id" => $commission->id,
                "price" => $service->regular_price + $commission_money,
            ]);
        }

        session()->flash("success", __("messages.Updated_successfully"));
        return back();
    }

    public function update_overview_time($user_id, Request $request)
    {
        $request->validate([
            "overview_time" => "required",
        ]);
        User::find($user_id)->update([
            "overview_time" => $request->overview_time,
        ]);
        session()->flash("success", __('messages.Updated_successfully'));
        return back();
    }

    public function add_packge_to_provider(Request $request)
    {
        $request->validate([
            "provider_id" => "required|exists:users,id",
            "package_id" => "required|exists:packages,id",
        ]);
        $package = Packages::find($request->package_id);
        if (!$package)
            return back();
        $provider_id = $request->provider_id;
        $this->PackageServices->set_package_to_provider($provider_id, $package, "paid", false);
        session()->flash("success", __("messages.Added_successfully"));
        return back();
    }
    public function add_balance(Request $request){
        $request->validate([
            "password"=>"required",
            "balance"=>"required",
            "customer_id"=>"required|exists:users,id",
        ]);
        $auth_user =Auth::user();            
        if ($auth_user && Hash::check($request->password, $auth_user->password)) {
            $customer=User::find($request->customer_id);
            $customer->update([
                "blance"=>$customer->blance + $request->balance,
            ]);
            session()->flash("success",__("messages.Added_successfully"));
            return back();
        }else{
            session()->flash("error",__("messages.Password_not_match"));
            return back();
        }
    }
}
