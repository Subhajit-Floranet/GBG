<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller as RootController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Mail\EmailVerification;
use App\Mail\PasswordReset;
use Illuminate\Support\Facades\Mail;


class ForgotPasswordController extends RootController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        //$this->middleware('guest');
    }

    public function showLinkRequestForm(){

        //return view('site.auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request){
        if($request->isMethod('POST')){
          //$this->validate($request, ['email' => 'required|email']);
          $user = User::where('email','=',$request->email)->first();
        
          if($user == null){
              return response()->json(['errors'=>'No account with the entered email id exist']);
          }
          if($user->email_verified == 'N'){
              return response()->json(['errors'=>'Your account is not verified yet please click on the verification link sent to you']);
          }
          else if($user->status == 'I')
              return response()->json(['errors'=>'Your account is temporarily suspended. Contact admin for details.']);
          else
             //dispatch(new SendPasswordResetEmail($user));
              Mail::to($request->email)->queue(new PasswordReset($user));
              
            return response()->json(['success'=>"A password reset link has been mailed to you."]);
        }
            return response()->json(['errors'=>'There was an unexpected error. Try again!']);
    }

    protected function getEmailSubject(){
        return isset($this->subject) ? $this->subject : 'Your Password Reset Link';
    }

}