<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

use Auth;

class CommonController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }
    
    public function hasInput(Request $request)
    {
        if($request->has('_token')) {
            return count($request->all()) > 1;
        } else {
            return count($request->all()) > 0;
        }
    }

    

    public function checkPermission($controller,$method){
        //dd(session('permissions.user_type'));
        if(session('permissions.'.strtolower($controller).'.'.strtolower($method)) == 1 || session('permissions.user_type') =='A'){
            return true;
        }else{
            return false;
        }
    }

    public function checkSuperPermission($controller,$method){
        //dd(session('permissions.user_type'));
        if(session('permissions.'.strtolower($controller).'.'.strtolower($method)) == 1 || session('permissions.user_type') =='SU'){
            return true;
        }else{
            return false;
        }
    }

    public function checkEditorPermission($controller,$method){
        //dd(session('permissions.user_type'));
        if(session('permissions.'.strtolower($controller).'.'.strtolower($method)) == 1 || session('permissions.user_type') =='AEU'){
            return true;
        }else{
            return false;
        }
    }

    public function get_date_time() {
        $ip = '103.251.83.170';
        //$ip = '110.142.215.61';
        //$ip = $_SERVER['REMOTE_ADDR'];
        $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));

        if( isset($query) && $query['status'] == 'success' ) {
            date_default_timezone_set($query['timezone']);
            return date('Y-m-d H:i:s');
        }else{
            return date('Y-m-d H:i:s');
        }
    }

    //Getting IP wise details and current time
    public static function get_time(){
        $ip = '103.251.83.170';
        //$ip = '110.142.215.61';
        //$ip = $_SERVER['REMOTE_ADDR'];
        $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));

        if( isset($query) && $query['status'] == 'success' ) {
            date_default_timezone_set($query['timezone']);
            return date('H:i');
        }else{
            return date('H:i');
        }
    }

    

}
