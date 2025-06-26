<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\CPU\Helpers;
use App\Models\User;
use App\Models\AssignUsers;
use App\Models\Notification;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rules\Password;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        $user = auth()->user();

        if ($request->filled('role') && in_array($request->role, ['admin', 'provider', 'customer', 'employee'])) {
            if ($request->role == 'employee') {
                $query->whereNotIn('power', ['admin', 'customer', 'provider']);
            } else {
                $query->where("power", $request->role);
            }
        } else {
            $query->whereNotIn("power", ['admin', 'provider', 'customer']);
        }
        if ($request->filled('search')) {
            $query->where("name", 'LIKE', "%{$request->search}%")
                ->orWhere("phone", "LIKE", "%{$request->search}%");
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
        if ($request->filled('from') && $request->filled('to')) {
            $startDate = Carbon::parse($request->from);
            $endDate = Carbon::parse($request->to);
            $query->whereBetween("created_at", [$startDate, $endDate]);
        } else {
            $query->orderBy("id", "DESC");
        }


        $users = $query->orderBy("id", "DESC")->paginate(15);
        $roles = Role::orderBy("id", "DESC")->get();
        return view('admin.users.index')
            ->with("users", $users)
            ->with("roles", $roles)
            ->with("role", $request->role)
        ;
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                "name" => 'required',
                "image" => 'required',
                "power" => 'required',
                "phone" => "required|unique:users,phone",
                'password' => 'required|min:8|confirmed', //password_confirmation
                            'password' => [
                'required',
                'confirmed',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],

            ],
            [
                "name.required" => __('messages.Validate_name'),
                "image.required" => __('messages.Validate_image'),
                "phone.required" => __('messages.Validate_phone'),
                "password.required" => __('messages.Validate_password'),
                        "password.confirmed" => __('messages.Password_confirmation_mismatch'),

            ]
        );


        $check = User::where('email', $request->email)->orWhere("phone",$request->phone)
        ->where('power', $request->power)
        ->exists();
        if($check) {
            session()->flash("error", __('messages.Email_or_phone_already_exists'));
            return back();
        }
        
        $user = User::create([
            "name" => $request->name,
            "image" => Helpers::upload_files($request->image),
            "phone" => $request->country_code . $request->phone,
            "power" => $request->power,
            "bio" => $request->bio,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "email_verified_at"=> Carbon::now(),
        ]);
        if ($user->power == 'customer') {
            Cache::forget("customers");
            Cache::rememberForever("customers", function () {
                return User::select('id', 'name')->where('power', 'customer')->orderBy('id', 'DESC')->get();
            });
        } elseif ($user->power == "provider") {
            Cache::forget("providers");

            Cache::rememberForever('providers', function () {
                return User::select('id', 'name')->where('power', 'provider')->get();
            });
        }
        $user->assignRole($request->power);

        session()->flash("success", __('messages.Added_successfully'));
        return back();
    }
    public function update(Request $request)
    {
        $request->validate(
            [
                "name" => 'required',
                "phone" => "required",
            ],
            [
                "name.required" => __('messages.Validate_name'),
                "phone.required" => __('messages.Validate_phone'),
            ]
        );


        $user = User::find($request->id);

        if ($user) {
            if ($request->has("password") && $request->has('password_confirmation')) {
                if ($request->password != $request->password_confirmation) {
                    session()->flash("error", __('messages.Password_do_not_match'));
                    return back();
                }
            }
            if ($request->hasFile('image')) {
                Helpers::delete_file($user->image);
                $user->update([
                    "image" => Helpers::upload_files($request->image),
                ]);
            }
            $user->update([
                "name" => $request->name,
                "phone" => (strpos($request->phone, '2') === 0) ? $request->phone : '2' . $request->phone,
                "power" => $request->power,
                "bio" => $request->bio,
                "email" => $request->email,
            ]);
            if ($user->power == 'customer') {
                Cache::forget("customers");
                Cache::rememberForever("customers", function () {
                    return User::select('id', 'name')->where('power', 'customer')->orderBy('id', 'DESC')->get();
                });
            } elseif ($user->power == "provider") {
                Cache::forget("providers");

                Cache::rememberForever('providers', function () {
                    return User::select('id', 'name')->where('power', 'provider')->get();
                });
            }
            session()->flash("success", __('messages.Updated_successfully'));
            return back();
        } else {
            return back();
        }
    }
    public function destroy(Request $request)
    {
        $user = User::find($request->id);

        if ($user) {

            if ($user->power == 'customer') {
                Cache::forget("customers");
                Cache::rememberForever("customers", function () {
                    return User::select('id', 'name')->where('power', 'customer')->orderBy('id', 'DESC')->get();
                });
            } elseif ($user->power == "provider") {
                Cache::forget("providers");

                Cache::rememberForever('providers', function () {
                    return User::select('id', 'name')->where('power', 'provider')->get();
                });
            }
            $user->delete();
            session()->flash("success", __("messages.Deleted_successfully"));
            return back();
        } else {
            return back();
        }
    }
    public function make_notification_asread(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|exists:notifications,id',
        ]);



        $notification = Notification::find($request->notification_id);

        if ($notification) {
            $notification->update([
                "read_at" => Carbon::now(),
            ]);

            return response()->json(['message' => 'Notification marked as seen'], 200);
        } else {
            return response()->json(['message' => 'Notification not found'], 404);
        }
    }
}
