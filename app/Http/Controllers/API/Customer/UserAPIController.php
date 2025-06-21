<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Customer\ChangePasswordRequest;
use App\Http\Requests\API\Customer\EditProfileRequest;
use App\Http\Requests\API\Customer\ResetPasswordRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\Driver_license;
use App\Models\Term;

class UserAPIController extends Controller
{


    function login(Request $request)
    {
        try {
            $this->validate($request, [
                'phone' => 'required|numeric',
                'password' => 'required',
                'device_token' => 'required',
            ]);

            if (auth()->attempt(['phone' => $request->input('phone'), 'password' => $request->input('password')])) {
                // Authentication passed...


                $user = auth()->user();

                $user['token']= $user->createToken('myAppToken')->plainTextToken;

                // Update device Token
                $update_user= User::where('phone',$request->input('phone'))->first();

                $update_user->device_token= $request->device_token;
                $update_user->save();

                return response([
                    "message"=> 'User retrieved successfully',
                    "user" => $user
                ]);

            } else {
                return response([
                    "message"=> 'Authentication Error'
                ]);
            }
        } catch (ValidationException $e) {
            return  response([
                "message"=>'Validation Error',
                "errors" =>array_values($e->errors())
            ]);

        } catch (Exception $e) {
            return  response([
                "message"=>'Error',
                "errors" =>$e->getMessage()
            ]);
        }

    }


    public function register(Request $request){

        try{

                $validation= $request->validate([
                    'name' => 'required|string',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|string',
                    'device_token' => 'required',
                    'phone' => 'required|numeric|unique:users,phone',
                    'image' => ['nullable','image', 'mimes:jpeg,png,jpg', 'max:2048'],

                ]);

                $input = $request->all();


                $input['power'] = 'customer';
                $input['password']=Hash::make($validation['password']);

                if($request->hasFile('image')){
                    $input['image'] =  (new \MainHelper)->save_image($request->image,'users');
                }else{
                    $input['image'] =  'images/default/avatar.png';
                }

                $user= User::create($input);

                $user['token']= $user->createToken('myAppToken')->plainTextToken;

                return response([
                    "message"=> 'User retrieved successfully',
                    "user" => $user
                ]);



        }catch (ValidationException $e) {
            return  response([
                "message"=>'Validation Error',
                "errors" =>array_values($e->errors())
            ]);

        }catch (Exception $e) {

            return  response([
                "message"=>'Error',
                "errors" =>$e->getMessage()
            ]);
        }

    }


    public function logout(){

         auth()->user()->tokens()->delete();
         return response(['message' =>"Logout Success"]);
     }



     public function upload_license(Request $request){

        $request->validate(['license' => 'required']);
        $user_id =  auth('sanctum')->user()->id;

        if($request->hasFile('license')){

            foreach($request->license as $license){

                $driver_license= new Driver_license();
                $driver_license->user_id = $user_id;
                $driver_license->image =  (new \MainHelper)->save_image($license,'driver_license');
                $driver_license->save() ;
            }
            return response(['message' =>"Driver License Added Successfully"]);
        }

        return response(['message' =>"Please Insert Driver License"]);

     }

     public function get_license(){

        $user_id =  auth('sanctum')->user()->id;

        $user= User::find($user_id);
        if($user){
            return response([
                "message"=> 'Driver License retrieved successfully',
                "Data" => $user->driver_license
            ]);
        }

     }


     public function terms(){
        $terms=Term::first();
        return response([
            "message"=> 'terms retrieved successfully',
            "terms" => $terms
        ]);
     }



     public function re_login(){
        $user_id =  auth('sanctum')->user()->id;
        $user= User::with('driver_license')->find($user_id);

        return response([
            "message"=> 'user retrieved successfully',
            "user" => $user
        ]);
     }


     public function edit_profile(EditProfileRequest $request){

        $user_id =  auth('sanctum')->user()->id;
        $user= User::find($user_id);

        if($user){
            $user->name= $request->name;
            $user->phone= $request->phone;
            $user->email= $request->email;

            if($request->hasFile('image')){
                $image = (new \MainHelper)->save_image($request->file('image'), 'users');
                $user->image= $image;
            }

            $user->save();
        }

        return response([
            "message"=> 'user Updated successfully',
            "user" => $user
        ]);
     }

     public function change_password(Request $request){

        $user_id =  auth('sanctum')->user()->id;
        $user=User::find($user_id);

        if($user){

            if(!Hash::check($request->old_password, auth('sanctum')->user()->password)){
                return response(["message"=> "Old Password Doesn't match!"]);
            }


            #Update the new Password
            $user->password= Hash::make($request->new_password);
            $user->save();
        }

        return response(["message"=> 'Password Updated successfully']);
     }


     public function check_phone(Request $request){


        $user= User::where('phone',$request->phone)->first();

        if($user){

            return response(['code' => 200 ,"message"=> 'Phone Number is Correct']);
        }

        return response(['code' => 404 ,"message"=> 'Phone Number Not Found']);
     }


     public function reset_password(ResetPasswordRequest $request){

        $user= User::where('phone',$request->phone)->first();

        $user->password= Hash::make($request->new_password);
        $user->save();

        return response(['code' => 200 ,"message"=> 'Password Updated Successfully']);

     }



     public function update_token(Request $request){

        $request->validate(['device_token' => 'required']);
        $user_id =  auth('sanctum')->user()->id;
        $user=User::find($user_id);
        $user->device_token= $request->device_token;
        $user->save();

        return response(['code' => 200 ,"message"=> 'device token Updated Successfully']);

     }

}
