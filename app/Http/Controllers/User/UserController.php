<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;

class UserController extends Controller
{
    public function create_user(Request $request){
        //dd($request);
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|max:15',
            'cpassword'=>'required|same:password',
            //'gender'=> 'required|in:M,F' 
        ],[
            'cpassword.same'=>'The confirm password is required.',
            'cpassword.same'=>'The confirm password and password must match.'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $data = $user->save();

        if($data){
            return redirect()->back()->with('success', 'You have registered successfully');
            //return redirect()->route('user.login')->with('success', 'You have registered successfully');
            //return redirect()->intended('/user/home');
        }else{
            return redirect()->back()->with('error', 'Registration Failed');
        }
    }

    public function dologin(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:6|max:15',
        ]);
        $check = $request->only('email', 'password');
        if(Auth::guard('web')->attempt($check)){
            return redirect()->route('user.home')->with('success', 'Welcome to Dashboard');
            //return redirect()->intended('/user/home');
        }else{
            return redirect()->back()->with('error', 'Login Failed');
        }
    }

    public function logout(){
        Auth::guard('web')->logout();
        //return redirect('/');
        return redirect()->route('user.login');
    }
}