<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;
use Hash;
use Auth;

class AdminController extends Controller
{
    public function dologin(Request $request){
        $request->validate([
            'email'=>'required|email|exists:admins,email',
            'password'=>'required|min:6|max:15',
        ],[
            'email.exists'=>'This email is not registered into our system'
        ]);
        $check = $request->only('email', 'password');
        if(Auth::guard('admin')->attempt($check)){
            Session::put('permissions.user_type', Auth::guard('admin')->user()->user_type);
            return redirect()->route('admin.home')->with('success', 'Welcome to Admin Dashboard');
        }else{
            return redirect()->back()->with('error', 'Login Failed');
        }
    }

    public function logout(){
        Session::forget('permissions');
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}