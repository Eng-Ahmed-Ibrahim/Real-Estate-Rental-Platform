<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function index(Request $request){

        $permissions=Permission::orderBy("id","DESC")
        ->orderBy('section')
        ->get()->groupBy('section');
        $roles=Role::orderBy("id","DESC")->get();
        return view('admin.roles.index')
        ->with('permissions',$permissions)
        ->with('roles',$roles)
        ;
    }
    public function store(Request $request){
        $request->validate([
            'name'=>"required",
            "permissions"=>"required",
        ]);
        if(Role::where("name",$request->name)->first()){
            session()->flash("error",__('messages.Already_exists'));
            return back();
        }
        $role=Role::create([
            "name"=>$request->name,
        ]);

        $permissions = $request->permissions;

        $permissionGroups = [
            ["actions" => ["show_calendar", "show_services", "show_booking",'show_rating','show_overview_time','show_logs','show_packages','show_wallet'], "show" => "show providers"],
            ["actions" => ["create_feature", "edit_feature", "delete_feature"], "show" => "show_features"],
            ["actions" => ["change_subscriber_status"], "show" => "show_subscribers"],
            ["actions" => ["add category", "edit category", "delete category"], "show" => "show category"],
            ["actions" => ["add feature", "edit feature", "delete feature"], "show" => "show feature"],
            ["actions" => ["add coupon", "edit coupon", "delete coupon"], "show" => "show coupon"],
            ["actions" => ["add Property", "edit Property", "delete Property"], "show" => "show Property"],
            ["actions" => ["add payment method", "edit payment method", "delete payment method", "change payment method status"], "show" => "show payment method"],
            ["actions" => ["change booking status", "change payment status"], "show" => "show booking"],
            ["actions" => ["create role", "edit role"], "show" => "show roles"],
            ["actions" => ["accept or denied withdrawal"], "show" => "show withdrawal requests"],
            ["actions" => ["Add Assign", "Delete Assign"], "show" => "Show Only Assigned Providers"],
            ["actions" => ["Delete Package", "Edit packages", "Create Package"], "show" => "Show Packages"]
        ];
        if($permissions != null)
            foreach ($permissionGroups as $group) {
                if (array_intersect($permissions, $group['actions'])) {
                    if (!in_array($group['show'], $permissions)) {
                        $permissions[] = $group['show'];
                    }
                }
            }

        $role->syncPermissions($permissions);
        session()->flash('success',__("messages.Added_successfully"));
        return back();
    }
    public function edit($id){
        $role=Role::find($id);
        $users = User::role($role->name)->get();

        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $permissions = Permission::orderBy("id","DESC")->orderBy('section')->get()->groupBy('section');
        
        return view('admin.roles.edit', compact('role','users', 'permissions', 'rolePermissions'));

    }
    public function update(Request $request){
        $role = Role::findById($request->id);
        if (!$role) {
            return redirect()->back()->with('error', __('messages.Not_found'));
        }
        $role->update([
            "name"=>$request->name,
        ]);
        $permissions = $request->permissions;

        $permissionGroups = [
            ["actions" => ["show_calendar", "show_services", "show_booking",'show_rating','show_overview_time','show_logs','show_packages','show_wallet'], "show" => "show providers"],
            ["actions" => ["create_feature", "edit_feature", "delete_feature"], "show" => "show_features"],
            ["actions" => ["change_subscriber_status"], "show" => "show_subscribers"],
            ["actions" => ["add category", "edit category", "delete category"], "show" => "show category"],
            ["actions" => ["add feature", "edit feature", "delete feature"], "show" => "show feature"],
            ["actions" => ["add coupon", "edit coupon", "delete coupon"], "show" => "show coupon"],
            ["actions" => ["add Property", "edit Property", "delete Property"], "show" => "show Property"],
            ["actions" => ["add payment method", "edit payment method", "delete payment method", "change payment method status"], "show" => "show payment method"],
            ["actions" => ["change booking status", "change payment status"], "show" => "show booking"],
            ["actions" => ["create role", "edit role"], "show" => "show roles"],
            ["actions" => ["accept or denied withdrawal"], "show" => "show withdrawal requests"],
            ["actions" => ["Add Assign", "Delete Assign"], "show" => "Show Only Assigned Providers"],
            ["actions" => ["Delete Package", "Edit packages", "Create Package"], "show" => "Show Packages"]
        ];
        if($permissions != null)
            foreach ($permissionGroups as $group) {
                if (array_intersect($permissions, $group['actions'])) {
                    if (!in_array($group['show'], $permissions)) {
                        $permissions[] = $group['show'];
                    }
                }
            }
        
        $role->syncPermissions($permissions);
        session()->flash("success",__("messages.Updated_successfully"));
        return back();
 
    }

}
