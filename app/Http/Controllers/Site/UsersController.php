<?php
namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller as RootController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Mail\UserEmailVerification;
use App\Mail\EmailVerification;
use App\Mail\ThankyouEmailVerification;
use App\Mail\EmailGuestUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
use App\Rules\Captcha;
use Auth;
use App\Models\User;
use App\Models\Country;
use App\Models\City;
use App\Models\UserAddress;
use App\Models\Common;
use App\Models\Order;
use App\Models\OrderDetail;
use PDF;

use Socialite;

class UsersController extends RootController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //parent::__construct();
        $users = new User;
    }

    /* Add users */
    public function register(Request $request) {
        $users = new User;
        
        if($request->isMethod('POST')){            
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'mobile' => 'integer|required|unique:users,mobile',
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password',
                //'CaptchaCode' => 'required|valid_captcha'
            ]);
            //            if ($validator->fails()){
            //                return response()->json(['errors'=>$validator->errors()->all()]);
            //            }

            //Checking for hacking bulk upload
            if(strpos($request->name, 'http') !== false || strpos($request->name, '.com') !== false){

            } else {

                if($users->create(['name'=>$request->name,'mobile'=>$request->mobile,'email'=>$request->email,'password'=>Hash::make($request->password),'countries_id'=>$request->countries_id,'email_token' => base64_encode($request->email).rand(1111,9999)])){
                    // if($request->mobile != null || $request->mobile != ''){
                    //     $phone_number = $request->mobile;
                    //     $text_sms_content = 'Thanks for registering with www.giftbasketworldwide.com';
                    //     @$this->generate_sms($phone_number,$text_sms_content);
                    // }

                    if($request->email != null || $request->email != ''){
                        $get_user_data = User::Where('email',$request->email)->first();
                        Mail::to($request->email)->queue(new EmailVerification($get_user_data));
                        //BCC mail
                          $bccemails = ['auto-update@germanflorist.de'];
                          Mail::bcc($bccemails)->queue(new EmailVerification($get_user_data));
                        //END BCC mail
                    }
                    $request->session()->flash('alert-success', 'Please check your email for an activation link.');
                    return redirect()->route('site.login');
                }else{
                    $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!'); 
                    return redirect()->back()->with($request->except(['_method', '_token']));
                }
            }
        }        
        return view('site.user.register',['users'=>$users]);
    }

    /* Gift login */
    public function login( Request $request ) {
        $flag=0;
        $users   = new User;
        if(Auth::user()){
            return redirect()->route('users.dashboard');
        }
        if($request->isMethod('POST')){
            
            if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password,'user_type'=>['C','GU']])) {
                //dd(Auth::user()->email_verified);
                //dd($request);
                // The user is active, not suspended, and exists.
                if(Auth::user()->email_verified=='N') {
                    Auth::guard('web')->logout();
                    $flag++;
                    $request->session()->flash('alert-danger', 'Oops! Your Email is not verified. Click on the link mailed to you for verification.');
                    return redirect()->back()->with($request->except(['_method', '_token']));
                }
                if(!$flag) {

                    //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart start//
                    // if ($request->session()->has('Cart.session_id')) {
                    //     $sessionId = $request->session()->get('Cart.session_id');
                    //     $this->mergeCartDetails( $sessionId );
                    // }
                    // //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart end//

                    // //NEW ADD SECTION 02.09.2020
                    // $get_cart_details = $this->get_cart_item_details();
                    // if( !empty($get_cart_details['item_dtl']) ) {
                    //     //echo $get_cart_details['item_dtl'][0]['delivery_city_id'];
                    //     session([
                    //         'Delivery.delivery_country_id'  => $get_cart_details['item_dtl'][0]['delivery_country_id'],
                    //         'Delivery.delivery_city_id'     => $get_cart_details['item_dtl'][0]['delivery_city_id']
                    //     ]);
                    // }
                    //NEW ADD SECTION 02.09.2020

                    //dd($get_cart_details);
                    //return response()->json(['success'=>'Login success.']);
                    $request->session()->flash('alert-success', 'Login success.');
                    return redirect()->route('users.dashboard');
                }
            }else{
                $request->session()->flash('alert-danger', 'Oops! Your credentials are wrong.'); 
                return redirect()->back()->with($request->except(['_method', '_token']));
            }
        }
        return view('site.user.login',['users'=>$users]);
    }

    /* Auth logout */
    public function logout() {
        Session::forget('Cart');

        Session::forget(['Cart.order_id','Cart.session_id','Cart.order_details','product_delivery_pin_code','delivery_address_id','billing_address_id','couponid','coupon_discount_amount']);
        //NEW ADD SECTION 02.09.2020
        Session::forget(['Delivery.delivery_country_id','Delivery.delivery_city_id']);
        //NEW ADD SECTION 02.09.2020
        Auth::guard('web')->logout();
        return redirect()->route('home');
    }

    /* User Dashboard */
    public function dashboard() {
        $user_data = Auth::user();
        //dd($user_data);

        $country_list = Country::where(['is_block' => 'N'])->get();
        //dd($country_list);

        return view('site.user.dashboard', ['user_data' => $user_data, 'country_list' => $country_list]);
    }

     /* Show the listing */
    public function list(Request $request) {
        
        $orWhere = array();
        $where[] = ['user_type', '=', 'C'];
        // search confitions
        if($request->search != null){
            $orWhere[] = ['email', 'LIKE', '%'.$request->search.'%'];
            $orWhere[] = ['first_name', 'LIKE', '%'.$request->search.'%'];
            $orWhere[] = ['last_name', 'LIKE', '%'.$request->search.'%'];
            $orWhere[] = ['mobile', 'LIKE', '%'.$request->search.'%'];

            // When searching with full name
            if(strpos($request->search, ' ') !== false){
                $exploded_search = explode(' ', $request->search);
                $orWhere[] = ['first_name', 'LIKE', '%'.$exploded_search[0].'%'];
                $orWhere[] = ['last_name', 'LIKE', '%'.$exploded_search[1].'%'];
            }
        }
        $users = User::where($where)
                        ->where(function($query) use ($orWhere){
                            // creating "OR" queries for search
                            foreach($orWhere as $key => $where){
                                if($key == 0){
                                    $query->where([$where]);
                                }else{
                                    $query->orWhere([$where]);
                                }
                            }
                        })
                        ->when($request->sort && $request->direction, function($query) use ($request){
                            $query->orderBy($request->sort, $request->direction);
                        }, function($query){
                            $query->orderBy('created_at', 'desc');
                        })
                        ->paginate(10);
        //echo '<pre>';
        //print_r($users); exit;
        return view('frontend.user.list', ['users' => $users, 'request' => $request]);
    }

    /* User edit personal information */
    public function editPersonalInformation(Request $request) {
        $user_data = Auth::user();
        if($request->isMethod('POST')){
            $validator = \Validator::make($request->all(), [
                'name'  => 'required',
                'dob'   => 'required',
                'mobile'=> 'required'
            ]);

            if ($validator->fails()){
                return response()->json(['errors'=>$validator->errors()->all()]);
            }
            if($user_data->update($request->except(['_method', 'accountedit', '_token', 'redirect']))){
                return response()->json(['success'=>'User information updated successfully.','name'=>$request->name,'dob'=>$request->dob,'mobile'=>$request->mobile]);
            }else{
                return response()->json(['errors'=>'Sorry! There was an unexpected error. Try again!']);
            }
        }
        return response()->json(['errors'=>'Sorry! There was an unexpected error. Try again!']);
    }

    /* User change password */
    public function changePassword(Request $request) {
        $user_data = Auth::user();
        if ($request->isMethod('post')) {

            $validator = \Validator::make($request->all(), [
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password',
            ]);

            if ($validator->fails()){
                return response()->json(['errors'=>$validator->errors()->all()]);
            }

            // if(!Hash::check($request->old_password, $user_data->password)){
            //     return response()->json(['errors'=>'Old password does not match.']);
            // }else{
                $request->password = Hash::make($request->password);

                if($user_data){
                    $user_data->password = $request->password;
                    $user_data->save();
                    return response()->json(['success'=>'Password updated successfully.']);
                }else{
                     return response()->json(['errors'=>'Sorry! There was an unexpected error. Try again!']);
                }
            //}
        }
         return response()->json(['errors'=>'Sorry! There was an unexpected error. Try again!']);
    }

    /* Get Country respective cities */
    public function getCountryCities( Request $request ) {
        if( $request->country_id != '' ) {
            $cities = City::where('country_id',$request->country_id)->get();
            //dd($cities);
            return view('site.user.city_list', ['cities' => $cities]);
        }
    }

    /* Get Country respective cities */
    public function checkothercity( Request $request ) {
        if( $request->city_id != '' ) {
            $city = City::where('id',$request->city_id)->first();
            //dd($cities);
            //return view('site.user.city_list', ['cities' => $city->]);
            echo json_encode(array('type'=>'success', 'cityCheck' => $city->check_other));
        }
    }

    /* Get Address */
    public function getAddress( Request $request ) {
        if( $request->received_pincode != '' ) {
            $pincode = Pincode::where('pincode',$request->received_pincode)->first();

            //dd($pincode);

            if( $pincode != null ) {
                if( $pincode->country_id != '' ) {
                    $country_id     = $pincode->country_id;
                    $country_name   = $pincode->country->name;
                }
                if( $pincode->state_id != '' ) {
                    $state_id     = $pincode->state_id;
                    $state_name   = $pincode->state->name;
                }
                if( $pincode->city_id != '' ) {
                    $city_id     = $pincode->city_id;
                    $city_name   = $pincode->city->name;
                }
                echo json_encode(array('type'=>'success', 'country_id'=>$country_id, 'country_name'=>$country_name, 'state_id'=>$state_id, 'state_name'=>$state_name, 'city_id'=>$city_id, 'city_name'=>$city_name));
            }else{
                echo json_encode(array('type'=>'error', 'title'=>'Error!', 'message'=>'Sorry! There was an unexpected error. Try again!'));
            }
        }
    }

    /* Get Address Session Pincode wise */
    public function sessionPincodeGetAddress( Request $request ) {
        if( $request->isMethod('POST') ) {
            if(Session::has('product_delivery_pin_code')) {
                $received_pincode = Session::get('product_delivery_pin_code');

                $pincode = Pincode::where('pincode',$received_pincode)->first();
                if( $pincode != null ) {
                    if( $pincode->country_id != '' ) {
                        $country_id     = $pincode->country_id;
                        $country_name   = $pincode->country->name;
                    }
                    if( $pincode->state_id != '' ) {
                        $state_id     = $pincode->state_id;
                        $state_name   = $pincode->state->name;
                    }
                    if( $pincode->city_id != '' ) {
                        $city_id     = $pincode->city_id;
                        $city_name   = $pincode->city->name;
                    }
                    echo json_encode(array('type'=>'success', 'country_id'=>$country_id, 'country_name'=>$country_name, 'state_id'=>$state_id, 'state_name'=>$state_name, 'city_id'=>$city_id, 'city_name'=>$city_name, 'received_pincode'=>$received_pincode));
                }else{
                    echo json_encode(array('type'=>'error', 'title'=>'Error!', 'message'=>'Sorry! There was an unexpected error. Try again!'));
                }
            }else{
                echo json_encode(array('type'=>'error', 'title'=>'Error!', 'message'=>'Sorry! There was an unexpected error. Try again!'));
            }
        }
    }

    /* My addresses */
    public function myAddresses( Request $request ) {
        $user_data      = Auth::user();
        $user_address   = new UserAddress;
        $address_list   = UserAddress::where([['user_id',Auth::user()->id],['address_type','DA']])->orderby('id','desc')->get();

        $country_list = Country::where(['is_block' => 'N'])->get();
        //dd($address_list);

        return view('site.user.my_addresses', ['user_data' => $user_data, 'address_list' => $address_list, 'country_list' => $country_list]);
    }

    /* Add address */
    public function addAddress(Request $request ) {
        if ($request->isMethod('post')) {
            //dd($request);
            $validator = \Validator::make($request->all(), [
                'name'      => 'required',
                'address'   => 'required',
                'country_id'=> 'required',
                'state_name'=> 'required',
                'city_id'   => 'required',
                //'pincode'   => 'required',
                'mobile'    => 'required',
                //'email'     => 'required|email',
            ]);

            if ($validator->fails()) {
                echo json_encode(array('type'=>'error', 'title'=>'Error!', 'message'=>'Sorry! There was an unexpected error. Try again!'));
            }
            if($request->address_state=='edit'){
                $user_data       = Auth::user();
                $decryp_id       = Common::flower_encrypt_decrypt($request->address_data_id,'d');
                $countrydtl = Country::where(['id' => $request->country_id])->first();
                $citydtl = City::where(['id' => $request->city_id])->first();
                $address_details = UserAddress::where('id',$decryp_id)->first();

                if($citydtl->check_other == 'N'){
                    $cityname = $citydtl->name;
                }else{
                    $cityname = $request->city_name;
                }

                
                //if( $address_details->update($request->except(['_method', '_token', 'redirect', 'accountedit', 'country_name', 'state_name', 'city_name','address_state','address_data_id'])) ) {
                if($address_details->update(['name'=>$request->name, 'country_name'=>$countrydtl->name, 'country_id'=>$request->country_id,'state_name'=>$request->state_name,'city_name'=>$cityname,'city_id'=>$request->city_id,'pincode'=>$request->pincode,'address'=>$request->address,'mobile'=>$request->mobile/*,'email'=>$request->email*/])){
                    return response()->json(['success'=>'Address updated successfully.']);
                }else{
                    echo json_encode(array('type'=>'error', 'title'=>'Error!', 'message'=>'Sorry! There was an unexpected error. Try again!'));
                }
            }else{
                $user_data      = Auth::user();
                $countrydtl = Country::where(['id' => $request->country_id])->first();
                $citydtl = City::where(['id' => $request->city_id])->first();

                if($citydtl->check_other == 'N'){
                    $cityname = $citydtl->name;
                }else{
                    $cityname = $request->city_name;
                }
                //dd($cityname);
                $user_address   = new UserAddress;
                if($User_address_data=$user_address->create(['user_id'=>$user_data->id,'name'=>$request->name, 'country_name'=>$countrydtl->name, 'country_id'=>$request->country_id,'state_name'=>$request->state_name,'city_name'=>$cityname,'city_id'=>$request->city_id,'pincode'=>$request->pincode,'address'=>$request->address,'mobile'=>$request->mobile/*,'email'=>$request->email*/])){
                    $address_id = $User_address_data->id;
                    return response()->json(['success'=>'Address added successfully.','address_id'=>$address_id]);
                    //return redirect()->route('site.users.myAddresses')->with($request->except(['_method', '_token']));
                }else{
                   echo json_encode(array('type'=>'error', 'title'=>'Error!', 'message'=>'Sorry! There was an unexpected error. Try again!'));
                }
            }            
        }
    }


    /* Edit address */
    public function editAddress( $id = null, Request $request ) {
        $user_data       = Auth::user();
        $decryp_id       = Common::flower_encrypt_decrypt($id,'d');
        $address_details = UserAddress::where('id',$decryp_id)->first();
        $country_list    = Country::where(['is_block' => 'N'])->get();
        //dd($country_list);

        $city_list       = City::where(['country_id'=>$address_details->country_id,'is_block' => 'N'])->get(); //Saved country respective city
        //dd($city_list);

        return view('site.user.edit_address', ['user_data' => $user_data, 'address_details' => $address_details, 'country_list' => $country_list, 'city_list' => $city_list, 'id' => $id]);
    }


    /* My addresses */
    public function deleteAddress( Request $request ) {
        if( $request->id != NULL ){
            $address_check = UserAddress::where('id',$request->id)->count();
            if( $address_check > 0 ){
                if(UserAddress::where(['id' => $request->id])->delete()){
                    echo json_encode(array('type'=>'success', 'title'=>'Deleted!', 'message'=>'Address has been deleted successfully.'));
                }else{
                    echo json_encode(array('type'=>'error', 'title'=>'Error!', 'message'=>'Sorry! There was an unexpected error. Try again!'));
                }
            }else{
                echo json_encode(array('type'=>'error', 'title'=>'Error!', 'message'=>'Sorry! There was an unexpected error. Try again!'));
            }
        }
    }

    /* Billing address */
    public function myBillingAddress( Request $request ) {
        $user_data       = Auth::user();
        if( $user_data != null ) {
            $billing_address_details = UserAddress::where([
                                                        'user_id'     => $user_data->id,
                                                        'address_type'=> 'BA',
                                                    ])
                                                    ->first();
        }
        return view('site.user.my_billing_address', ['billing_address_details'=>$billing_address_details]);
    }

    /* Add Billing address */
    public function addBillingAddress( Request $request ) {
        $user_data      = Auth::user();
        $user_address   = new UserAddress;

        if ($request->isMethod('post')) {
            $validator = \Validator::make($request->all(), [
                'name'      => 'required',
                'address'   => 'required',
                'country_id'=> 'required',
                'state_id'  => 'required',
                'city_id'   => 'required',
                'pincode'   => 'required',
                'mobile'    => 'required',
                'email'     => 'required|email',
            ]);

            if ($validator->fails()) {
                $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
                return redirect()->back();
            }

            if($user_address->create(['user_id'=>$user_data->id,'name'=>$request->name,'country_id'=>$request->country_id,'state_id'=>$request->state_id,'city_id'=>$request->city_id,'pincode'=>$request->pincode,'address'=>$request->address,'mobile'=>$request->mobile,'email'=>$request->email,'address_type'=>'BA'])){
                $request->session()->flash('alert-success', 'Billing address added successfully.');
                return redirect()->route('site.users.my-billing-address')->with($request->except(['_method', '_token']));
            }else{
                $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
                return redirect()->back()->with($request->except(['_method', '_token']));
            }
        }
        return view('site.user.add_billing_address', ['user_data' => $user_data]);
    }

    /* Edit address */
    public function editBillingAddress( $id = null, Request $request ) {
        $user_data               = Auth::user();
        $decryp_id               = Common::flower_encrypt_decrypt($id,'d');
        $billing_address_details = UserAddress::where([
                                                    'id'          => $decryp_id,
                                                    'address_type'=> 'BA'
                                                ])
                                                ->first();
        if ($billing_address_details == null ) {
            return redirect()->route('site.users.dashboard');;
        }

        if ($request->isMethod('POST')) {
            $validator = \Validator::make($request->all(), [
                'name'      => 'required',
                'address'   => 'required',
                'country_id'=> 'required',
                'state_id'  => 'required',
                'city_id'   => 'required',
                'pincode'   => 'required',
                'mobile'    => 'required',
                'email'     => 'required|email',
            ]);

            if ($validator->fails()) {
                $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
                return redirect()->back();
            }

            if( $billing_address_details->update($request->except(['_method', '_token', 'redirect', 'accountedit', 'country_name', 'state_name', 'city_name'])) ) {
                $request->session()->flash('alert-success', 'Billing address updated successfully.');
                return redirect()->route('site.users.my-billing-address')->with($request->except(['_method', '_token']));
            }else{
                $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
                return redirect()->back()->with($request->except(['_method', '_token']));
            }
        }
        return view('site.user.edit_billing_address', ['user_data' => $user_data, 'billing_address_details' => $billing_address_details, 'id' => $id]);
    }

    /* My Orders */
    public function myOrders( Request $request ) {
        $user_data = Auth::user();

        $order_list = Order::where([
                                    'user_id' => Auth::user()->id,
                                    'type'    => 'order'
                                ])
                                ->orWhere([
                                    ['order_status','OP'],
                                    ['order_status','CM']
                                ])
                                ->orderby('purchase_date','DESC')
                                //->paginate(5);
                                ->get();
                                

        $i = 0; $ordered = []; $applied_coupon = []; $total_cart_price = 0; $occasion_product_price = 0; $product_shipping_cost = 0;
        if( isset($order_list) && $order_list->count() > 0 ) {
            foreach( $order_list as $orderList ) {
                $i = 0;
                foreach( $orderList->order_detail_site as $productLists ) {
                    //dd($productLists);
                    //If Extra Addons NOT exist
                    if( $productLists->order_details_id == 0 ) {
                        //If Gift Addons NOT exist
                        if( $productLists->gift_addon_id == 0 ) {

                            $product_image_name = '';
                            if( isset($productLists->product->default_product_image) && $productLists->product->default_product_image != null ) {
                                $product_image_name = $productLists->product->default_product_image['name'];
                            }

                            $ordered[$orderList->id][$i]['category_id']      = $productLists->category_id;
                            $ordered[$orderList->id][$i]['occasion_id']      = $productLists->occasion_id;
                            $ordered[$orderList->id][$i]['product_id']       = $productLists->product->id;
                            $ordered[$orderList->id][$i]['gift_addon_id']    = $productLists->gift_addon_id;
                            $ordered[$orderList->id][$i]['product_name']     = $productLists->product->product_name;
                            $ordered[$orderList->id][$i]['image']            = $product_image_name;
                            $ordered[$orderList->id][$i]['qty']              = $productLists->qty;

                            $ordered[$orderList->id][$i]['delivery_pincode'] = $productLists->delivery_pincode;
                            $ordered[$orderList->id][$i]['delivery_date']    = $productLists->delivery_date;
                            $ordered[$orderList->id][$i]['shippingmethod_id']= $productLists->shippingmethod_id;
                            $ordered[$orderList->id][$i]['shippingmethod_name']= $productLists->shippingmethod_name;
                            $ordered[$orderList->id][$i]['ship_price']       = $productLists->ship_price;

                            if( $productLists->product_attr_id != 0 ) {
                                foreach ( $productLists->product->order_product_all_attributes as $key => $value) {
                                    if( $value->id == $productLists->product_attr_id ) {
                                        $ordered[$orderList->id][$i]['attribute_name'] = $value->title;
                                    }
                                }
                            }
                            else{
                                $ordered[$orderList->id][$i]['attribute_name'] = '';
                            }

                            //If extra addon exists
                            if( isset($productLists->order_related_detail) && ($productLists->order_related_detail != null) ) {

                                $ordered[$orderList->id][$i]['product_unit_price']       = $productLists->unit_price;

                                $ordered[$orderList->id][$i]['unit_price']       = $productLists->unit_price + $productLists->order_related_detail->unit_price;
                                $ordered[$orderList->id][$i]['total_price']      = $productLists->total_price + $productLists->order_related_detail->total_price;
                                $total_cart_price                   = $total_cart_price + $productLists->total_price + $productLists->order_related_detail->total_price;

                                //For occasion related product (Total Price)//
                                if( $productLists->product->occasions_id != null ) {
                                    $occasion_product_price = $occasion_product_price + $productLists->unit_price + $productLists->order_related_detail->total_price;
                                }
                                //For occasion related product (Total Price)//
                            }
                            else{   //If NOT extra addon exists
                                $ordered[$orderList->id][$i]['product_unit_price']       = $productLists->unit_price;
                                $ordered[$orderList->id][$i]['unit_price']               = $productLists->unit_price;
                                $ordered[$orderList->id][$i]['total_price']              = $productLists->total_price;
                                $total_cart_price                           = $total_cart_price + $productLists->total_price;

                                //For occasion related product (Total Price)//
                                if( $productLists->product->occasions_id != null ) {
                                    $occasion_product_price = $occasion_product_price + $productLists->total_price;
                                }
                                //For occasion related product (Total Price)//
                            }
                        }
                        else{   //If Gift Addons exist
                            $giftaddon_image_name = '';
                            //$giftaddon_image_name = $productLists->extraaddon_detail->image;
                            if( isset($productLists->extraaddon_detail->default_product_image) && $productLists->extraaddon_detail->default_product_image != null ) {
                                $giftaddon_image_name = $productLists->extraaddon_detail->default_product_image->name;
                            }

                            $ordered[$orderList->id][$i]['category_id']      = $productLists->category_id;
                            $ordered[$orderList->id][$i]['occasion_id']      = $productLists->occasion_id;
                            $ordered[$orderList->id][$i]['product_id']       = $productLists->product_id;
                            $ordered[$orderList->id][$i]['gift_addon_id']    = $productLists->gift_addon_id;
                            //$ordered[$orderList->id][$i]['product_name']     = $productLists->extraaddon_detail->title;
                            $ordered[$orderList->id][$i]['product_name']     = $productLists->extraaddon_detail->product_name;
                            $ordered[$orderList->id][$i]['image']            = $giftaddon_image_name;
                            $ordered[$orderList->id][$i]['qty']              = $productLists->qty;

                            $ordered[$orderList->id][$i]['delivery_pincode'] = $productLists->delivery_pincode;
                            $ordered[$orderList->id][$i]['delivery_date']    = $productLists->delivery_date;
                            $ordered[$orderList->id][$i]['shippingmethod_id']= $productLists->shippingmethod_id;
                            $ordered[$orderList->id][$i]['shippingmethod_name']= $productLists->shippingmethod_name;
                            $ordered[$orderList->id][$i]['ship_price']       = $productLists->ship_price;
                            
                            $ordered[$orderList->id][$i]['product_unit_price']= $productLists->unit_price;
                            $ordered[$orderList->id][$i]['unit_price']       = $productLists->unit_price;
                            $ordered[$orderList->id][$i]['total_price']      = $productLists->total_price;
                            $total_cart_price                                = $total_cart_price + $productLists->total_price;

                            $ordered[$orderList->id][$i]['attribute_name'] = '';
                        }
                        //If Gift Addons exist
                    }
                    $i++;
                }

                //If coupon data exist in Applied Coupon table
                if($orderList->order_coupon_data != null) {
                    $odr_id = $orderList->order_coupon_data->order_id;
                    $applied_coupon[$odr_id]['coupon_id']              = $orderList->order_coupon_data->coupon_id;
                    $applied_coupon[$odr_id]['coupon_code']            = $orderList->order_coupon_data->coupon_code;
                    $applied_coupon[$odr_id]['coupon_discount_amount'] = $orderList->order_coupon_data->coupon_discount_amount;
                }
            }
        }
        return view('site.user.my_orders', ['user_data' => $user_data, 'order_list' => $order_list, 'ordered' => $ordered, 'applied_coupon' => $applied_coupon]);
    }

    

    /* Edit users */
    public function edit( $id = null, Request $request ) {
        if($id == null){
            return redirect()->route('admin.dashboard');
        }
        $id = base64_decode($id);
        $users = User::find($id);
        if($request->isMethod('PUT')){
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
            ]);
            if($request->mobile == null){
                $request->offsetSet('mobile', '');
            }
            if($users->update($request->except(['_method', '_token', 'redirect']))){
                $request->session()->flash('alert-success', 'User successfully updated.');
                if($request->query('redirect') != null){
                    return redirect($request->query('redirect'));
                }else{
                    return redirect()->route('admin.user.list');
                }
            }else{
                $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
                return redirect()->back()->with($request->except(['_method', '_token']));
            }
        }
        return view('admin.user.edit', ['users' => $users]);
    }

    /* View user Detail */
    public function view( $id = null, Request $request ) {
        if($id == null){
            return redirect()->route('admin.dashboard');
        }
        $id = base64_decode($id);
        $users = User::find($id);
        return view('admin.user.view', ['users' => $users]);
    }

    /* Delete users */
    public function delete( $id = null, Request $request ) {
        if($id == null){
            return redirect()->route('admin.dashboard');
        }
        $id = base64_decode($id);
        if(User::where(['id' => $id, 'user_type' => 'C'])->delete()){
            $request->session()->flash('alert-success', 'User successfully deleted.');
            return redirect()->route('admin.user.list');
        }else{
            $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
            return redirect()->back();
        }
    }

    /* change user status - block or unblock */
    public function status( $id = null, $status = null, Request $request ) {
        if($id == null || $status == null){
            return redirect()->route('admin.dashboard');
        }
        $id = base64_decode($id);
        $block = 'Y';
        $blockText = 'blocked';
        switch($status){
            case 'Y':
                $block = 'N';
                $blockText = 'unblocked';
                break;
            case 'N':
                $block = 'Y';
                $blockText = 'blocked';
                break;
            default:
                $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
                return redirect()->back();
        }
        if(User::where(['id' => $id, 'user_type' => 'C'])->update(['is_block' => $block])){
            $request->session()->flash('alert-success', 'User successfully '.$blockText);
            return redirect()->back();
        }else{
            $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
            return redirect()->back();
        }
    }

    /* Email verify */
    public function verify( $token = null, Request $request ) {
        $user = User::where('email_token',$token)->first();
        if( !empty($user)){
            $user->email_verified = 'Y';
            $user->status         = 'A';
            $user->email_token    = '';

            if($user->save()){
                Mail::to($user->email)->queue(new ThankyouEmailVerification($user));
                //BCC mail
                  $bccemails = ['auto-update@germanflorist.de'];
                  Mail::bcc($bccemails)->queue(new ThankyouEmailVerification($user));
                //END BCC mail
                $request->session()->flash('alert-success', 'Email verification successful.');
                return redirect()->route('site.login');
            }
        }else{
            $request->session()->flash('alert-success', 'Your account has already been activated.');
            return redirect()->route('site.login');
        }
    }


    /****************************** Checkout Process ****************************/

    /* Checkout Login process for Normal Users start here */
    public function checkoutLoginProcess( Request $request ) {
        $flag = 0; $total_orders = 0;
        if($request->isMethod('POST')) {
            $userid   = isset($request->userid)?$request->userid:0;
            $usertype = isset($request->usertype)?$request->usertype:'C';

            $email    = isset($request->email)?$request->email:'';
            $password = isset($request->password)?$request->password:'';


            //For NORMAL user section start here
            if( $usertype == 'C' && $email != '' && $password != '' ) {
                if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password,'user_type'=>['C','GU']])) {

                    // The user is active, not suspended, and exists.
                    if(Auth::user()->email_verified=='N') {
                        Auth::guard('web')->logout();
                        $flag++;
                        return response()->json(['type'=>'account inactive', 'msg'=>'Oops! Your Email is not verified. Click on the link mailed to you for verification.']);
                    }
                    if(!$flag) {

                        //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart start//
                        if ($request->session()->has('Cart.session_id')) {
                            $sessionId = $request->session()->get('Cart.session_id');
                            $this->mergeCartDetails( $sessionId );
                        }
                        //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart end//

                        //Update header cart count start//
                        $header_order = Order::where(['user_id'=>Auth::user()->id, 'type'=>'cart'])->orderBy('id', 'desc')->first();
                        if( $header_order != null )
                            $total_orders = OrderDetail::where([['order_id',$header_order->id],['order_details_id',0]])->count();
                        //Update header cart count end//

                        /*if ( $request->session()->has('Cart.session_id') ) {
                            Session::put('Cart.session_id', '');
                            Session::save();
                        }*/

                        $user_id    = Auth::user()->id;
                        $user_type  = Auth::user()->user_type;

                        return response()->json(['type'=>'success', 'total_orders'=>$total_orders, 'msg'=>'Login success.', 'user_id'=>$user_id, 'user_type'=>$user_type]);
                    }
                }

            }
            //For NORMAL user section end here

            return response()->json(['type'=>'not match', 'msg'=>'Oops! Your credentials are wrong.']);
        }
        return response()->json(['type'=>'unknown error', 'msg'=>'Sorry! There was an unexpected error, please try again.']);
    }
    /* Checkout Login process for Normal Users end here */

    /* Check */
    public function checkoutGuestLoginProcess( Request $request ) {
        $flag = 0; $total_orders = 0;
        if($request->isMethod('POST')) {
            $name     = isset($request->checkout_guest_name)?$request->checkout_guest_name:'';
            $mobile   = isset($request->checkout_guest_mobile)?$request->checkout_guest_mobile:'';
            $email    = isset($request->checkout_guest_email)?$request->checkout_guest_email:'';
            $usertype = isset($request->usertype)?$request->usertype:'GU';

            $password = $this->randPassword(8);

            //Checking email id already exist or not
            if( User::where('email',$email)->count() == 0 ) {
                $guest['name']           = $name;
                $guest['email']          = $email;
                $guest['password']       = Hash::make($password);
                $guest['mobile']         = $mobile;
                $guest['user_type']      = $usertype;
                $guest['email_verified'] = 'Y';
                $guest['created_at']     = date('Y-m-d H:i:s');
                $guest['updated_at']     = date('Y-m-d H:i:s');
                $guest['last_login']     = date('Y-m-d H:i:s');
                $guest['is_block']       = 'N';
                $guest['status']         = 'A';

                if( $guest_user    = User::create($guest) ) {
                    $guest_user_id = $guest_user->id;
                    $guest['raw_password']   = $password;

                    //Mail::to($email)->queue(new EmailGuestUser($guest));
                    //BCC mail
                      //$bccemails = ['auto-update@germanflorist.de'];
                      //Mail::bcc($bccemails)->queue(new EmailGuestUser($guest));
                    //END BCC mail

                    //Making guest user to normal user
                    User::where(['id'=>$guest_user_id])->update(['user_type'=>'C']);

                    //Registration done now logged in with the email and password
                    if (Auth::guard('web')->attempt(['email' => $email, 'password' => $password,'user_type'=>['C','GU']])) {

                        // The user is active, not suspended, and exists.
                        if(Auth::user()->email_verified=='N') {
                            Auth::guard('web')->logout();
                            $flag++;
                            return response()->json(['type'=>'account inactive', 'msg'=>'Oops! Your Email is not verified. Click on the link mailed to you for verification.']);
                        }
                        if(!$flag) {

                            //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart start//
                            if ($request->session()->has('Cart.session_id')) {
                                $sessionId = $request->session()->get('Cart.session_id');
                                $this->mergeCartDetails( $sessionId );
                            }
                            //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart end//

                            //Update header cart count start//
                            $header_order = Order::where(['user_id'=>Auth::user()->id, 'type'=>'cart'])->orderBy('id', 'desc')->first();
                            if( $header_order != null )
                                $total_orders = OrderDetail::where([['order_id',$header_order->id],['order_details_id',0]])->count();
                            //Update header cart count end//

                            /*if ( $request->session()->has('Cart.session_id') ) {
                                Session::put('Cart.session_id', '');
                                Session::save();
                            }*/

                            $user_id    = Auth::user()->id;
                            $user_type  = Auth::user()->user_type;

                            return response()->json(['type'=>'success', 'total_orders'=>$total_orders, 'msg'=>'Login success.', 'user_id'=>$user_id, 'user_type'=>$user_type]);
                        }
                    }
                }
                else{
                    return response()->json(['type'=>'error', 'msg'=>'An error occurred during processing. Please try again.']);
                }
            }
            else{
                return response()->json(['type'=>'account exist', 'msg'=>'Oops! This email is already associated with us, please use another one.']);
            }
        }
    }

    /***************************** Checkout Process End ************************/


    /************************ Order Invoice Section Start **********************/
    public function generateInvoice( $order_id = null, Request $request ) {
        if( $order_id == null ) {
            $request->session()->flash('alert-danger', "Sorry! Order details doesn't not exist.");
            return redirect()->route('site.users.dashboard');
        }

        $orderid   = Common::flower_encrypt_decrypt($order_id,'d');
        $order_dtl = Order::where(['id'=>$orderid, 'user_id'=>Auth::user()->id])->first();

        $order_details = $this->order_dtl($orderid);
        //dd($order_details);

        $generate_pdf =PDF::loadView('site.user.generate_invoice',compact('order_details','order_dtl'))->setPaper('a4');
        //$generate_pdf =PDF::loadView('site.user.generate_invoice')->setPaper('a4');

        return $generate_pdf->download($order_dtl->unique_order_id.'.pdf');

        return view('site.user.generate_invoice');
    }
    /************************ Order Invoice Section End ***********************/



    /************************ Social Login Section *************************/

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToFacebookProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from Facebook.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleFacebookProviderCallback()
    {
        $user = Socialite::driver('facebook')->user();

        return $user->token;
    }



}
