<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Models\Cities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CitiesController extends Controller
{
    public function index()
    {
        $cities = Cities::orderBy("id", "DESC")->paginate(15);
        return view('admin.cities.index')
            ->with("cities", $cities);
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                "name_ar" => 'required',
                "name_en" => 'required',
            ],
            [
                "name_ar.required" => __('messages.Validate_name_ar'),
                "name_en.required" => __('messages.Validate_name_en'),
            ]
        );
        Cities::create([
            "name_ar" => $request->name_ar,
            "name_en" => $request->name_en,
        ]);
        Helpers::cacheCities();

        session()->flash("success", __('messages.Added_successfully'));
        return back();
    }
    public function update(Request $request)
    {
        $request->validate(
            [
                "id" => 'required',
                "name_ar" => 'required',
                "name_en" => 'required',
            ],
            [
                "name_ar.required" => __('messages.Validate_name_ar'),
                "name_en.required" => __('messages.Validate_name_en'),
            ]
        );
        $city = Cities::find($request->id);
        if ($city) {
            if ($request->hasFile('image')) {
                Helpers::delete_file($city->image);
                $city->update([
                    "image" => Helpers::upload_files($request->image),
                ]);
            }
            $city->update([
                "name_ar" => $request->name_ar,
                "name_en" => $request->name_en,
            ]);
            Helpers::cacheCities();

            session()->flash("success", __('messages.Updated_successfully'));
            return back();
        } else {
            return back();
        }
    }
    public function destroy(Request $request)
    {
        $city = Cities::find($request->id);
        if ($city) {
            $city->delete();
            Helpers::cacheCities();
            session()->flash("success", __("messages.Deleted_successfully"));
            return back();
        } else {
            return back();
        }
    }
}
