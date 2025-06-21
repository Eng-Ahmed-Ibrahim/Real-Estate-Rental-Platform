<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function login(Request $request)  {
        $request->validate([
            'phone'=>'required',
            'password'=>"required",
        ]);
        $credentials = $request->only('phone', 'password');
        $user = User::where('phone', $credentials['phone'])->first();


        if ($user && Hash::check($credentials['password'], $user->password)) {
            if($user->power!='provider' && $user->power != "provider"){
                Auth::login($user);   
                return redirect()->route('admin.dashboard');
            }else{
                session()->flash('error','Not Allowed To Login ');
                return back();
            }
        }
        session()->flash("error","The phone or password is incorrect");
        return back();
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
    public function forget_password(){
        return view('auth.forget_password');
    }
    public function send_otp(Request $request){
        $request->validate([
            'email'=>'required',
        ]);

        $user = User::where("email", $request->email)->where("power", "admin")->first();
        if (! $user) {
            session()->flash('error', 'Email not found');
            return back();
        }
        session(['step' => 2]);
        session(['email' => $request->email]);
        Helpers::send_otp($user);
        return back()->with('success', 'OTP sent to your email');
    }
    public function verify_otp(Request $request){
        $request->validate([
            'otp'=>'required',
        ]);
        $user = User::where("email", session('email'))->where("power", "admin")->first();
        if (! $user) {
            return back()->with('error', 'Email not found');
        }
        if ($user->otp == $request->otp) {
            session(['step' => 3]);
            session(['otp' => $request->otp]);
            session()->flash('success', 'OTP verified successfully');
            return back() ;
        } else {
            return back()->with('error', 'Invalid OTP');
        }
    }
    public function reset_password(Request $request){
        $request->validate([
            'new_password'=>'required',
            'confirm_password'=>'required',
        ]);
        $user = User::where("email", session('email'))->where("power", "admin")->first();
        if (! $user) {
            return $this->Response(null, "User not found", 422);
        }
        if ($request->new_password != $request->confirm_password) {
            return back()->with('error', 'Passwords do not match');
        }
        if($user->otp != session('otp')){
            session()->forget('step');
            return redirect()->route('forget_password')->with('error', 'Invalid OTP');
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        session()->forget('step');
        session()->flash('success', 'Password reset successfully');
        return redirect('/login');
    }
}
