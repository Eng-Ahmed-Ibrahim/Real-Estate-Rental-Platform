<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
    use ResponseTrait;
    public function profile(Request $request)
    {
        $user = User::find($request->user()->id);
        if ($request->user()->power == "provider") {

            $current_package = Subscription::where("provider_id", $request->user()->id)->where("status", 1)->with(["package"])->first();
            $data = [
                "user" => $user,
                "package" => $current_package,
            ];
            return $this->Response($data, "Profile", 201);
        }
        return $this->Response($user, "Profile", 201);
    }
    public function update_profile(Request $request)
    {
        $user = User::find($request->user()->id);
        if ($request->hasFile("image")) {
            $name = time() . str_replace([" ", "-"], "_", $request->image->getClientOriginalName());
            $request->image->move(public_path("images/"), $name);
            $path = "images/$name";
            $user->update([
                "image" => $path,
            ]);
        }
        if ($request->has('password')) {
            if ($user && Hash::check($request->current_password, $request->user()->password)) {
                if ($request->password != $request->password_confirmation) {
                    return $this->Response(null, "Password not match", 401);
                }
                $user->update([
                    "password" => bcrypt($request->password)
                ]);
            } else {
                return $this->Response(null, __('messages.Password_not_match'), 422);
            }
        }
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
        $user->update([
            "name" => $request->name ?? $user->name,
            "phone" => $request->phone ?? $user->phone,
        ]);
        return $this->Response($user, "Updated Successfully", 201);
    }
    public function block_user(Request $request)
    {
        $user = User::find($request->user()->id);
        if ($user->blocked == 0)
            $user->update([
                "blocked" => 1,
            ]);
        else {
            $user->update([
                "blocked" => 0,
            ]);
        }
        return $this->Response($user, "Success", 201);
    }
    public function delete_image(Request $request)
    {
        $user = User::find($request->user()->id);
        $user->update([
            "image" => "images/default/avatar.png",
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
        return $this->Response($user, "Image Deleted Successfully", 201);
    }
}
