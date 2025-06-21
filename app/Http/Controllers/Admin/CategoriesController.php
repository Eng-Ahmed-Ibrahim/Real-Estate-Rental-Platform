<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CategoriesController extends Controller
{
    public function index(){
        $categories=Categories::orderBy("id","DESC")->paginate(15);
        return view('admin.categories.index')
        ->with("categories",$categories)
        ;
    }
    public function store(Request $request){
        $request->validate([
            "name_ar"=>'required',
            "name_en"=>'required',
            "image"=>'required',
        ]
        ,[
            "name_ar.required"=>__('messages.Validate_name_ar'),
            "name_en.required"=>__('messages.Validate_name_en'),
            "image.required"=>__('messages.Validate_image'),
        ]);
        Categories::create([
            "brand_name_ar"=>$request->name_ar,
            "brand_name"=>$request->name_en,
            "image"=>Helpers::upload_files($request->image),
        ]);
        Helpers::cacheCategories();

        session()->flash("success",__('messages.Added_successfully'));
        return back();

    }
    public function update(Request $request){
        $request->validate([
            "id"=>'required',
            "name_ar"=>'required',
            "name_en"=>'required',
        ]
        ,[
            "name_ar.required"=>__('messages.Validate_name_ar'),
            "name_en.required"=>__('messages.Validate_name_en'),
        ]);
        $category=Categories::find($request->id);
        if($category){
            if($request->hasFile('image')){
                Helpers::delete_file($category->image);
                $category->update([
                    "image"=>Helpers::upload_files($request->image),
                ]);
            }
            $category->update([
                "brand_name_ar"=>$request->name_ar ,
                "brand_name"=>$request->name_en ,
            ]);
            Helpers::cacheCategories();
            
            session()->flash("success",__('messages.Updated_successfully'));
            return back();
        }else{
            return back();
        }

    }
    public function destroy(Request $request){
        $category=Categories::find($request->id);
        if($category){
            Helpers::delete_file($category->image);
            $category->delete();
            Helpers::cacheCategories();

            session()->flash("success",__("messages.Deleted_successfully"));
            return back();
        }else{
            return back();
        }
    }
}
