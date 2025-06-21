<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Models\Feature;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class FeaturesController extends Controller
{
    public function index()
    {
        $features = Feature::orderBy("id", "DESC")->paginate(15);
        return view('admin.features.index')
            ->with("features", $features);
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                "name_ar" => 'required|max:100',
                "name_en" => 'required|max:100',
            ],
            [
                "name_ar.required" => __('messages.Validate_name_ar'),
                "name_en.required" => __('messages.Validate_name_en'),
                "name_ar.max" => __("messages.Greater_than_100"),
                "name_en.max" => __("messages.Greater_than_100"),

            ]
        );
        Feature::create([
            "feature_name_ar" => $request->name_ar,
            "feature_name" => $request->name_en,
        ]);
        Helpers::cacheFeatures();

        session()->flash("success", __('messages.Added_successfully'));
        return back();
    }
    public function update(Request $request)
    {
        $request->validate(
            [
                "name_ar" => 'required|max:100',
                "name_en" => 'required|max:100',
                "id" => 'required',
            ],
            [
                "name_ar.required" => __('messages.Validate_name_ar'),
                "name_en.required" => __('messages.Validate_name_en'),
                "image.required" => __('messages.Validate_image'),
            ]
        );
        $feature = Feature::find($request->id);
        if ($feature) {
            if ($request->hasFile('image')) {
                Helpers::delete_file($feature->image);
                $feature->update([
                    "image" => Helpers::upload_files($request->image),
                ]);
            }
            $feature->update([
                "feature_name_ar" => $request->name_ar,
                "feature_name" => $request->name_en,
            ]);
            Helpers::cacheFeatures();

            session()->flash("success", __('messages.Updated_successfully'));
            return back();
        } else {
            return back();
        }
    }
    public function destroy(Request $request)
    {
        $feature = Feature::find($request->id);
        if ($feature) {
            Helpers::delete_file($feature->image);
            $feature->delete();
            Helpers::cacheFeatures();
            session()->flash("success", __("messages.Deleted_successfully"));
            return back();
        } else {
            return back();
        }
    }
}
