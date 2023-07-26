<?php

namespace App\Http\Controllers\site;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailPlaceOrder;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Currency;
use App\Models\OrderCurrency;
use App\Models\Country;
use App\Models\City;
use App\Models\UserAddress;
use App\Models\OrderMessage;
use App\Models\AppliedCoupon;
use App\Models\Common;
use Auth;
use DB;

class CheckoutController extends Controller
{
    public function __construct(){
        //$this->middleware('auth');
        $users = new User;
    }

    /*------------------ CHECKOUT PROCESS -------------------*/
    public function cartCheckout( Request $request ) {
        if( $request->isMethod('POST') ) {
            //dd($request);
            if ( $request->session()->has('Cart.session_id') ) {
                $cart_data                = $this->get_cart_item_details();
                $cart_data['final_price'] = $request->final_price;

                Session::put('Cart.order_details', $cart_data);
                Session::save();

                //dd($cart_data['item_dtl']);
                //return redirect()->route('checkout-process')->with(['request_data' => $request]);

                if(isset($cart_data['item_dtl'])){
            
                    if(count($cart_data['item_dtl']) == 0){
                        return redirect('cart');
                        exit;
                    }else{
                        $country_id_for_address = $cart_data['item_dtl'][0]['delivery_country_id'];
                        //dd($country_id_for_address);
                        Session::put('country_id_for_address',$country_id_for_address);
                        $city_id_for_address = $cart_data['item_dtl'][0]['delivery_city_id'];
                        //dd($city_id_for_address);
                        Session::put('city_id_for_address',$city_id_for_address);
        
                        $country_list = Country::get();
                        //$cities_list  = City::where(['country_id' => $country_id_for_address,'is_block' => 'N'])->get();
                        //dd($cities_list);
                        $del_country_dtl = Country::where(['id' => $country_id_for_address,'is_block' => 'N'])->first();
                        $del_city_dtl = City::where(['id' => $city_id_for_address,'is_block' => 'N'])->first();
                    }
                    
                    /*if(Auth::user()){
                        return redirect(route('site.checkout-message'));
                    }*/
                    return view('site.checkout.checkout', ['del_country_dtl'=>$del_country_dtl,'del_city_dtl'=>$del_city_dtl, 'country_list'=>$country_list]);
                } else {
                    return redirect('cart');
                    exit;
                }
            }
            else{

            }
        }
    }

    /* Main Checkout Page */
    public function checkoutProcess( Request $request ) {
        $country_list = [];
        //dd($country_list);
        $cart_data = $this->get_cart_item_details();
        //dd($cart_data);
        if(isset($cart_data['item_dtl'])){
            
            if(count($cart_data['item_dtl']) == 0){
                return redirect('cart');
                exit;
            }else{
                $country_id_for_address = $cart_data['item_dtl'][0]['delivery_country_id'];
                //dd($country_id_for_address);
                Session::put('country_id_for_address',$country_id_for_address);
                $city_id_for_address = $cart_data['item_dtl'][0]['delivery_city_id'];
                //dd($city_id_for_address);
                Session::put('city_id_for_address',$city_id_for_address);

                $country_list = Country::get();
                //$cities_list  = City::where(['country_id' => $country_id_for_address,'is_block' => 'N'])->get();
                //dd($cities_list);
                $del_country_dtl = Country::where(['id' => $country_id_for_address,'is_block' => 'N'])->first();
                $del_city_dtl = City::where(['id' => $city_id_for_address,'is_block' => 'N'])->first();
            }
            
            /*if(Auth::user()){
                return redirect(route('site.checkout-message'));
            }*/
            return view('site.checkout.checkout', ['del_country_dtl'=>$del_country_dtl,'del_city_dtl'=>$del_city_dtl, 'country_list'=>$country_list]);
        } else {
            return redirect('cart');
            exit;
        }
    }

     /* Checkout Step for Delivery Address */
    public function checkoutStepDeliveryAddress( Request $request ) {
        $address_list = '';
        if( $request->isMethod('POST') ) {
            $address_list = UserAddress::where([
                                                'user_id'     => Auth::user()->id,
                                                //'country_id'  => Session::get('country_id_for_address'),
                                                'city_id'     => Session::get('city_id_for_address'),
                                                'address_type'=> 'DA'
                                            ])
                                            ->orderby('id','desc')
                                            ->get();
            //dd($address_list);
            return view('site.checkout.delivery_address', ['address_list' => $address_list, 'last_address_id' => 0]);
        }
    }

    public function addNewDeliveryAddress( Request $request ) {
        if( $request->isMethod('POST') ) {
            if( Auth::user() ) {
                $insert['user_id']    = Auth::user()->id;
                $insert['name']       = isset($request->name)?ucwords($request->name):'';
                $insert['country_name'] = isset($request->country_name)?$request->country_name:'';
                $insert['country_id'] = isset($request->country_id)?$request->country_id:'';
                $insert['state_name'] = isset($request->state_name)?$request->state_name:'';
                $insert['city_name']    = isset($request->city_name)?$request->city_name:'';
                $insert['city_id']    = isset($request->city_id)?$request->city_id:'';
                $insert['pincode']    = isset($request->pincode)?$request->pincode:'';
                $insert['address']    = isset($request->address)?$request->address:'';
                $insert['mobile']     = isset($request->mobile)?$request->mobile:'';
                $insert['email']      = isset($request->email)?$request->email:'';

                $new_address          = UserAddress::create($insert);
                $last_address_id      = $new_address->id;
                $address_list         = '';

                $address_list = UserAddress::where([
                                                'user_id'     => Auth::user()->id,
                                                'address_type'=> 'DA',
                                                'city_id' => $request->city_id
                                            ])
                                            ->orderby('id','desc')
                                            ->get();                

                $addresses = view('site.checkout.delivery_address', ['address_list' => $address_list, 'last_address_id' => $last_address_id]);

                //Set delivery address id in session
                Session::put('delivery_address_id',$last_address_id);

                echo $addresses;
            }else{
                echo 0;
            }
        }
    }

    // Insert radio button Selected Delivery Address into Session Cart //
    public function deliveryAddressUpdateCart( Request $request ) {
        if( $request->isMethod('POST') ) {
            if( Auth::user() ) {
                $selected_address_id = isset($request->selected_address_id)?$request->selected_address_id:0;
                //Set delivery address id in session
                Session::put('delivery_address_id',$selected_address_id);
                echo 1;
            }else{
                echo 0;
            }
        }
    }
    /* Checkout Step for Delivery Address */

    public function checkouteditAddress( $id = null, Request $request ) {
        $user_data       = Auth::user();
        $decryp_id       = Common::flower_encrypt_decrypt($id,'d');
        $address_details = UserAddress::where('id',$decryp_id)->first();
        $country_list    = Country::where(['is_block' => 'N'])->get();
        //dd($country_list);

        $city_list       = City::where(['country_id'=>$address_details->country_id,'is_block' => 'N'])->get(); //Saved country respective city
        //dd($city_list);

        return view('site.checkout.checkout_edit_address', ['user_data' => $user_data, 'address_details' => $address_details, 'country_list' => $country_list, 'city_list' => $city_list, 'id' => $id]);
    }

    /* Checkout Step for Billing Address */
    public function checkoutStepBillingAddress( Request $request ) {
        if( $request->isMethod('POST') ) {
            if( Auth::user() ) {
                $delivery_address_id = isset($request->delivery_address_id)?$request->delivery_address_id:0;
                Session::put('delivery_address_id',$delivery_address_id);

                $billing_address =UserAddress::where([['user_id',Auth::user()->id],['address_type','BA']])->first();
                //$country_list = Country::where(['is_block' => 'N'])->get();
                //dd($country_list);
                //$all_country_cities = '';

                if( $billing_address != null ) {
                    //Set billing address id in session
                    Session::put('billing_address_id',$billing_address->id);

                    /*$city_list = City::where(['country_id'=>$billing_address->country_id, 'is_block'=>'N'])->get();
                    if($city_list != null){
                        $select = '';
                        $all_country_cities = '<option value="">Select</option>';
                        foreach ($city_list as $value) {
                            if($value->id == $billing_address->city_id){
                                $all_country_cities .= '<option value="'.$value->id.'" selected="selected">'.$value->name.'</option>';
                            }else{
                                $all_country_cities .= '<option value="'.$value->id.'">'.$value->name.'</option>';
                            }                            
                        }
                    }*/

                    return response()->json(['status'=>'exist', 'billing_address'=>$billing_address/*, 'country_list'=>$country_list, 'all_country_cities'=>$all_country_cities*/]);
                }else{
                    return response()->json(['status'=>'not_exist', 'name'=>Auth::user()->name, 'mobile'=>Auth::user()->mobile, 'email'=>Auth::user()->email]);
                }
            }else{
                return response()->json(['status'=>'failed']);
            }
        }
    }

    // Add or Update billing address //
    public function addUpdateBillingAddress( Request $request ) {
        
        if( $request->isMethod('POST') ) {
            if( Auth::user() ) {
                if( $request->billing_address_id != 0 ) {   //Update existing billing address
                    $update = UserAddress::where([['id',$request->billing_address_id],['user_id',Auth::user()->id],['address_type','BA']])->first();

                    //Set billing address id in session
                    Session::put('billing_address_id',$request->billing_address_id);

                    $update['name']         = isset($request->name)?ucwords($request->name):'';
                    //$update['country_id']   = isset($request->country_id)?$request->country_id:0;
                    //$update['country_name'] = isset($request->country_name)?$request->country_name:'';
                    if(isset($request->country_name)){
                        $con = explode("|", $request->country_name);
                        $update['country_id']   = $con[1];
                        $update['country_name'] = $con[0];
                    }else{
                        $update['country_id']   = 0;
                        $update['country_name'] = '';
                    }
                    $update['state_name']   = isset($request->state_name)?$request->state_name:'';
                    $update['city_id']      = isset($request->city_id)?$request->city_id:0;
                    $update['city_name']    = isset($request->city_name)?$request->city_name:'';
                    $update['pincode']      = isset($request->pincode)?$request->pincode:'';
                    $update['address']      = isset($request->address)?$request->address:'';
                    $update['mobile']       = isset($request->mobile)?$request->mobile:'';
                    //$update['email']      = isset($request->email)?$request->email:'';
                    //$update['company']    = isset($request->company)?$request->company:'';

                    $billing_address_id   = isset($request->billing_address_id)?$request->billing_address_id:0;
                    $update->save();

                    $billing_details = UserAddress::where([['id',$billing_address_id],['user_id',Auth::user()->id],['address_type','BA']])->first();

                    /*$city_id      = $billing_details->city_id;
                    $country_id   = $billing_details->country_id;*/

                    return response()->json(['type'=>'success', 'billing_details'=>$billing_details, /*'city_id'=>$city_id, 'country_id'=>$country_id,*/ 'address_type'=>'updated', 'msg'=>'Your billing details updated successfully.']);
                }
                else{  //Insert new billing address

                    if(isset($request->country_name)){
                        $con = explode("|", $request->country_name);
                        $con_id = $con[1];
                        $con_name = $con[0];
                    }else{
                        $con_id = 0;
                        $con_name = '';
                    }

                    $insert['user_id']      = Auth::user()->id;
                    $insert['name']         = isset($request->name)?ucwords($request->name):'';
                    //$insert['country_id']   = isset($request->country_id)?$request->country_id:0;
                    //$insert['country_name'] = isset($request->country_name)?$request->country_name:0;
                    if(isset($request->country_name)){
                        $con = explode("|", $request->country_name);
                        $insert['country_id']   = $con[1];
                        $insert['country_name'] = $con[0];
                    }else{
                        $insert['country_id']   = 0;
                        $insert['country_name'] = '';
                    }
                    $insert['state_name']   = isset($request->state_name)?$request->state_name:'';
                    $insert['city_id']      = isset($request->city_id)?$request->city_id:0;
                    $insert['city_name']    = isset($request->city_name)?$request->city_name:'';
                    $insert['pincode']      = isset($request->pincode)?$request->pincode:'';
                    $insert['address']      = isset($request->address)?$request->address:'';
                    $insert['mobile']       = isset($request->mobile)?$request->mobile:'';
                    //$insert['email']      = isset($request->email)?$request->email:'';
                    //$insert['company']    = isset($request->company)?$request->company:'';
                    $insert['address_type'] = 'BA';

                    $new_address          = UserAddress::create($insert);
                    $billing_address_id   = $new_address->id;

                    //Set billing address id in session
                    Session::put('billing_address_id',$billing_address_id);
                }

                $billing_details = UserAddress::where([['id',$billing_address_id],['user_id',Auth::user()->id],['address_type','BA']])->first();

                $city_id      = $billing_details->city_id;
                $country_id   = $billing_details->country_id;

                return response()->json(['type'=>'success', 'billing_details'=>$billing_details, 'city_id'=>$city_id, 'country_id'=>$country_id, 'address_type'=>'saved', 'msg'=>'Your billing details saved successfully.']);
            }else{
                return response()->json(['type'=>'error', 'msg'=>'An error occurred during processing. Please try again by reloading the page.']);
            }
        }else{
            return response()->json(['type'=>'error', 'msg'=>'An error occurred during processing. Please try again by reloading the page.']);
        }
    }

    /* Checkout Step for Message Start */
    public function checkoutStepExistingMessage( Request $request ) {
        if( $request->isMethod('POST') ) {
            $cart_data = $this->get_cart_item_details();
            Session::put('Cart.order_details', $cart_data);
            Session::save();

            if( Auth::user() && !empty($cart_data) ) {
                $orderid            = isset($cart_data['order_id'])?$cart_data['order_id']:0;
                $billing_address_id = isset($request->billing_address_id)?$request->billing_address_id:0;

                //Set billing address id in session
                Session::put('billing_address_id',$billing_address_id);

                $existing_message = OrderMessage::where([['user_id',Auth::user()->id],['order_id',$orderid]])->first();

                if( $existing_message != null ) {
                    return response()->json(['status'=>'exist','existing_message'=>$existing_message]);
                }else{
                    return response()->json(['status'=>'not exist']);
                }
            }else{
                return response()->json(['status'=>'not exist']);
            }
        }
    }

    // Add or update order related message //
    public function addUpdateMessage( Request $request ) {
        if( $request->isMethod('POST') ) {
            //dd($request->all());
            if( Auth::user() && Session::has('Cart.order_details') ) {
                $session_cart_data = Session::get('Cart.order_details');
                $orderid           = isset($session_cart_data['order_id'])?$session_cart_data['order_id']:0;

                if( $request->message_id != 0 ) {   //Update existing message
                    $update_msg = OrderMessage::where([['id',$request->message_id],['user_id',Auth::user()->id]])->first();

                    $update_msg['message_type_id'] = isset($request->message_type_id)?$request->message_type_id:0;
                    $update_msg['message_purpose'] = isset($request->message_purpose)?$request->message_purpose:'';
                    $update_msg['sender_name'] = isset($request->sender_name)?$request->sender_name:'';
                    $update_msg['sender_message'] = isset($request->sender_message)?$request->sender_message:'';
                    $update_msg['sender_special_instruction'] = isset($request->sender_special_instruction)?$request->sender_special_instruction:'';
                    $update_msg['sender_demand'] = isset($request->sender_demand)?$request->sender_demand:'';

                    $message_id   = $request->message_id;

                    $update_msg->save();


                    $message_details = OrderMessage::where([['id',$message_id],['user_id',Auth::user()->id]])->first();

                    return response()->json(['type'=>'success', 'message_details'=>$message_details, 'address_type'=>'updated', 'msg'=>'Your message updated successfully.']);
                }
                else{  //Insert new message
                    $insert_msg['user_id']          = Auth::user()->id;
                    $insert_msg['order_id']         = isset($orderid)?ucwords($orderid):0;
                    $insert_msg['message_type_id']  = isset($request->message_type_id)?$request->message_type_id:'';
                    $insert_msg['message_purpose']  = isset($request->message_purpose)?$request->message_purpose:'';
                    $insert_msg['sender_name']      = isset($request->sender_name)?$request->sender_name:'';
                    $insert_msg['sender_message']   = isset($request->sender_message)?$request->sender_message:'';
                    $insert_msg['sender_special_instruction'] = isset($request->sender_special_instruction)?$request->sender_special_instruction:'';
                    $insert_msg['sender_demand'] = isset($request->sender_demand)?$request->sender_demand:'';

                    $new_message  = OrderMessage::create($insert_msg);
                    $message_id   = $new_message->id;
                }

                if( Session::has('Cart.order_details') ) {
                    $cart_data               = $this->get_cart_item_details();
                    $cart_data['message_id'] = $message_id;

                    Session::put('Cart.order_details', $cart_data);
                    Session::save();
                }

                $message_details = OrderMessage::where([['id',$message_id],['user_id',Auth::user()->id]])->first();

                return response()->json(['type'=>'success', 'message_details'=>$message_details, 'address_type'=>'updated', 'msg'=>'Your message saved successfully.']);
            }else{
                return response()->json(['type'=>'error', 'msg'=>'An error occurred during processing. Please try again by reloading the page.']);
            }
        }else{
            return response()->json(['type'=>'error', 'msg'=>'An error occurred during processing. Please try again by reloading the page.']);
        }
    }
    /* Checkout Step for Message End */

    /* Checkout Step for Review Order Start */
    public function checkoutStepOrderSummary( Request $request ) {
        $ordersummary = array();
        if( $request->isMethod('POST') ) {

            $message_id = isset($request->message_id)?$request->message_id:0;
            if( Session::has('Cart.order_details') ) {
                $cart_data               = $this->get_cart_item_details();
                $cart_data['message_id'] = $message_id;

                Session::put('Cart.order_details', $cart_data);
                Session::save();
            }

            $ordersummary = $this->get_cart_item_details();
        }
        return view('site.checkout.order_summary', ['ordersummary' => $ordersummary]);
    }
    /* Checkout Step for Review Order end */

    /* Checkout Step for Review Order end */

    public function orderPlaced( Request $request ) {
        if( $request->isMethod('POST') ) {
            if( Session::has('Cart.order_details') ) {
                $userid =  Auth::user()->id;

                $c_data = Session::get('Cart.order_details');
                //dd($c_data);

                $shipping_price = 0;
                if( $c_data != null && isset($c_data['item_dtl']) ){
                    foreach ($c_data['item_dtl'] as $key => $value) {
                        $shipping_price = $shipping_price + $value['ship_price'];
                    }
                }

                if(Session::get('Cart.order_id') != null){
                    $order_id  = Session::get('Cart.order_id');
                }else{
                    $order_id  = $c_data['order_id'];
                }
                
                //dd(Session::get('delivery_address_id'));
                $unique_order_id           = 'HampersFactory';
                //$order_id                  = Session::get('Cart.order_id');
                $delivery_address_id       = Session::get('delivery_address_id');
                $billing_address_id        = Session::get('billing_address_id');
                $order_details             = Session::get('Cart.order_details');
                $coupon_id                 = Session::get('couponid');

                $coupon_discount_amount    = 0;
                if( Session::has('coupon_discount_amount') ) {
                    $coupon_discount_amount= Session::get('coupon_discount_amount');

                    AppliedCoupon::where([
                                        'order_id'        => $order_id,
                                        'coupon_id'       => $coupon_id
                                    ])
                                    ->update([
                                        'coupon_discount_amount' => $coupon_discount_amount
                                    ]);
                }

                $get_date_time             = @$this->get_date_time();
                $get_date_time             = isset($get_date_time)?$get_date_time:date('Y-m-d H:i:s');

                if( !empty($order_details) ) {
                    $unique_order_id       = $order_details['unique_order_id'];
                }

                $delivery_user_name = ''; $delivery_address = ''; $delivery_mobile = ''; $delivery_email = ''; $delivery_country = ''; $delivery_pincode = ''; $delivery_state = ''; $delivery_city = '';
                
                if( $delivery_address_id != 0 ) {
                    $delivery_address_detail = UserAddress::where(['id' => $delivery_address_id, 'user_id' => Auth::user()->id, 'address_type'=> 'DA'])->first();

                    if( $delivery_address_detail != null ) {  
                        $delivery_user_name = isset($delivery_address_detail->name)?$delivery_address_detail->name:NULL;
                        $delivery_address   = isset($delivery_address_detail->address)?$delivery_address_detail->address:NULL;
                        $delivery_mobile    = isset($delivery_address_detail->mobile)?$delivery_address_detail->mobile:NULL;
                        $delivery_pincode   = isset($delivery_address_detail->pincode)?$delivery_address_detail->pincode:NULL;
                        //echo "==>".$delivery_email     = isset($delivery_address_detail->email)?$delivery_address_detail->email:NULL;
                        $delivery_email     = isset($delivery_address_detail->email)?$delivery_address_detail->email:NULL;
                        $delivery_country   = isset($delivery_address_detail->country_name)?$delivery_address_detail->country_name:NULL;
                        $delivery_state     = isset($delivery_address_detail->state_name)?$delivery_address_detail->state_name:NULL;
                        $delivery_city      = isset($delivery_address_detail->city_name)?$delivery_address_detail->city_name:NULL;
                    }                                         
                }

                $billing_user_name = ''; $billing_address = ''; $billing_mobile = ''; $billing_email = ''; $billing_pincode = ''; $billing_country = ''; $billing_state = ''; $billing_city = '';

                if( $billing_address_id != 0 ) {
                    $billing_address_detail = UserAddress::where(['id' => $billing_address_id, 'user_id' => Auth::user()->id, 'address_type'=> 'BA'])->first();

                    if( $billing_address_detail != null ) {
                        $billing_user_name  = isset($billing_address_detail->name)?$billing_address_detail->name:NULL;
                        $billing_address    = isset($billing_address_detail->address)?$billing_address_detail->address:NULL;
                        $billing_mobile     = isset($billing_address_detail->mobile)?$billing_address_detail->mobile:NULL;
                        //echo "===>".$billing_email      = isset($billing_address_detail->email)?$billing_address_detail->email:NULL;
                        $billing_pincode    = isset($billing_address_detail->pincode)?$billing_address_detail->pincode:'';
                        /*$billing_country    = isset($billing_address_detail->country->name)?$billing_address_detail->country->name:'';*/
                        $billing_country    = isset($billing_address_detail->country_name)?$billing_address_detail->country_name:'';
                        $billing_state      = isset($billing_address_detail->state_name)?$billing_address_detail->state_name:'';
                        /*$billing_city       = isset($billing_address_detail->city->name)?$billing_address_detail->city->name:'';*/
                        $billing_city       = isset($billing_address_detail->city_name)?$billing_address_detail->city_name:'';
                    }
                }

                $ordered_amount = 0; $final_ordered_amount = 0; $final_ordered_amount_discounted = 0;

                $ordered_amount                  = $c_data['total_cart_price'] + $shipping_price;
                $final_ordered_amount            = $c_data['total_cart_price'] + $shipping_price;
                $final_ordered_amount_discounted = ($c_data['total_cart_price'] + $shipping_price) - $coupon_discount_amount;

                //dd($order_id);
                $encoded_order_id = base64_encode($order_id);
                $custom_value   = $encoded_order_id." ".$unique_order_id." ".$userid;


                echo 

                /****************** Payu ****************************/

                // $MERCHANT_KEY = "hcM4pvUY"; // add your id
                // $SALT = "X7TDqAyehQ"; // add your id
                // $PAYU_BASE_URL = "https://sandboxsecure.payu.in";



                /******FOR SUPER******/
                $MERCHANT_KEY = "FvquiG"; // add your id
                $SALT = "PbgHlZYG"; // add your id
                $PAYU_BASE_URL = "https://test.payu.in";

                // $MERCHANT_KEY = "5VSGHV";
                // $SALT = "F7vdpy5O";
                // $PAYU_BASE_URL = "https://secure.payu.in";

                ////$PAYU_BASE_URL = "https://secure.payu.in";
                $action = '';
                $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
                $posted = array();
                $posted = array(
                    'key' => $MERCHANT_KEY,
                    'txnid' => $txnid,
                    'amount' => $final_ordered_amount_discounted,
                    'firstname' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'productinfo' => $custom_value,
                    'surl' => '{{ route("site.thank-you") }}',
                    'furl' => '{{ route("site.payment_cancelled") }}',
                    'service_provider' => 'payu_paisa',
                );

                if (empty($posted['txnid'])) {
                    $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
                } else {
                    $txnid = $posted['txnid'];
                }

                $hash = '';
                $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";



                if (empty($posted['hash']) && sizeof($posted) > 0) {
                    $hashVarsSeq = explode('|', $hashSequence);
                    $hash_string = '';
                    foreach ($hashVarsSeq as $hash_var) {
                        $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
                        $hash_string .= '|';
                    }
                    $hash_string .= $SALT;

                    $hash = strtolower(hash('sha512', $hash_string));
                    $action = $PAYU_BASE_URL . '/_payment';
                    $a=1;
                } elseif (!empty($posted['hash'])) {
                    $hash = $posted['hash'];
                    $action = $PAYU_BASE_URL . '/_payment';
                    $a=2;
                }

                // print_r($posted); 
                // echo "<br>".$a."<br>";
                // echo $hash; die;

                /****************** Payu end ****************************/

                Order::where([
                    'id'      => $order_id,
                    'user_id' => Auth::user()->id,
                    'type'    => 'cart'
                ])
                    ->update([
                        'payment_method'    => '3',
                        //'total_amount'      => base64_decode($request->finalprice),
                        'payment_status'    => 'P',
                        'order_status'      => 'IP',
                        'type'              => 'order',
                        'txn_id'            => $txnid,
                        'delivery_pincode'  => $delivery_pincode,
                        'delivery_country'  => $delivery_country,
                        'delivery_state'    => $delivery_state,
                        'delivery_city'     => $delivery_city,
                        'delivery_user_name' => $delivery_user_name,
                        'delivery_address'  => $delivery_address,
                        'delivery_mobile'   => $delivery_mobile,
                        'delivery_email'    => $delivery_email,
                        'billing_pincode'   => $billing_pincode,
                        'billing_country'   => $billing_country,
                        'billing_state'     => $billing_state,
                        'billing_city'      => $billing_city,
                        'billing_user_name' => $billing_user_name,
                        'billing_address'   => $billing_address,
                        'billing_mobile'    => $billing_mobile,
                        'billing_email'     => $billing_email,
                        //'billing_company'   => $billing_company,
                        'purchase_date'     => $get_date_time,
                        'final_ordered_amount'  => $final_ordered_amount_discounted
                    ]);

                OrderDetail::where([
                    'order_id'        => $order_id
                ])
                    ->update([
                        'order_status' => 'OP'
                    ]);

                if (Session::has('currency')) {
                    $currency = Session::get('currency');
                } else {
                    $currency = 'INR';
                }
                $currency_data = Currency::where(['currency' => $currency, 'is_block' => 'N'])->first();
                if ($currency_data->count() > 0) {
                    $order_currency['order_id']        = $order_id;
                    $order_currency['order_currency']  = $currency_data->currency;
                    $order_currency['conversion_rate'] = $currency_data->value;
                    $order_currency['html_code']       = $currency_data->html_code;
                    $ordetail_submit                   = OrderCurrency::create($order_currency);
                }

                //Session::forget(['Cart.order_id', 'Cart.session_id', 'Cart.order_details', 'product_delivery_pin_code', 'delivery_address_id', 'billing_address_id', 'couponid', 'coupon_discount_amount']);
                $user_array = Auth::user();

                $html = '<script>
                            var hash = "' . $hash . '";
                            function submitPayuForm() {
                              if(hash == "") {
                                return;
                              }
                              var payuForm = document.forms.payuForm;
                                   payuForm.submit();
                            }
                          </script>
                            Processing.....
                                <form action="' . $action . '" method="post" name="payuForm"><br />
                                    <input type="hidden" name="key" value="' . $MERCHANT_KEY . '" /><br />
                                    <input type="hidden" name="hash" value="' . $hash . '"/><br />
                                    <input type="hidden" name="txnid" value="' . $txnid . '" /><br />
                                    <input type="hidden" name="amount" value="' . $final_ordered_amount_discounted . '" /><br />
                                    <input type="hidden" name="firstname" id="firstname" value="' . Auth::user()->name . '" /><br />
                                    <input type="hidden" name="email" id="email" value="' . Auth::user()->email . '" /><br />
                                    <input type="hidden" name="productinfo" value="'.$custom_value.'"><br />
                                    <input type="hidden" name="surl" value="' . route('site.thank-you') . '" /><br />
                                    <input type="hidden" name="furl" value="' . route('site.payment_cancelled') . '" /><br />
                                    ';

                if (!$hash) {
                    $html .=  '<input type="submit" value="Submit" />';
                }
                $html .= '</form>
                                    <script>
                                        submitPayuForm();
                                    </script>';
                echo $html;
                die;

            }else{
                $request->session()->flash('alert-danger', 'Something went wrong! Please try again later.');
                return redirect()->back();
            }
        }
    }

    public function thankYou(Request $request, $order_unique_id = null)
    {
        if ($request->status !== null && $request->status == 'success') {

            Session::forget(['Cart.order_id', 'Cart.session_id', 'Cart.order_details', 'product_delivery_pin_code', 'delivery_address_id', 'billing_address_id', 'couponid', 'coupon_discount_amount']);

            $getOrder = Order::where(['txn_id' => $request->txnid])->first();
            $order_id = $getOrder->id;

            $order = Order::where([
                'txn_id'      => $request->txnid,
                'user_id' => Auth::user()->id,
                'type'    => 'order'
            ]);
            $order->update([
                'payment_status'    => 'C',
                'order_status'      => 'OP',
                'type'              => 'order'
            ]);
            //$order_data = $order->first();
            $order_data = Order::find($order_id);
            $order_unique_id = $order_data->unique_order_id;

            $user_array = Auth::user();

            $order_data_1 = $this->order_dtl($order_data->id);

            // if (Auth::user()->mobile != null || Auth::user()->mobile != '') {
            //     $phone_number = Auth::user()->mobile;
            //     $text_sms_content = 'Hi ' . Auth::user()->name . ', thank you for placing your order ' . $order_unique_id . ' with us. You will be duly updated once your order is delivered and completed.';
            //     @$this->generate_sms($phone_number, $text_sms_content);
            // }

            /*Send email to user */
            //$main_order_data = Order::where(['id' => $order_data->id])->first();
            //Mail::to(Auth::user()->email)->queue(new EmailPlaceOrder($user_array, $order_data_1, $main_order_data));

            /*Send email to user */
            $main_order_data = Order::where(['id'=>$order_data->id])->first();
            Mail::to(Auth::user()->email)->queue(new EmailPlaceOrder($user_array,$order_data_1,$main_order_data));

            //Order send BCC Lists
            //$bccemails = ['auto-update@rakhinationwide.com'];
            //Mail::bcc($bccemails)->queue(new EmailPlaceOrder($user_array,$order_data_1,$main_order_data));

            /*Send email to admin */
            //Mail::to(config('global.admin_email_id'))->queue(new EmailPlaceOrderAdmin(base64_encode($order_data->id)));
        } else {
            return redirect('site.payment-error');
        }
        return view('site.checkout.thank_you', ['order_unique_id' => $order_unique_id]);
    }

    public function paymentCancelled(Request $request)
    {
        
        if ($request->status == "failure") {
            $order = Order::where([
                'txn_id'      => $request->txnid,
                'user_id' => Auth::user()->id,
                'type'    => 'order'
            ]);
            $order->update([
                'payment_status'    => 'P',
                'order_status'      => 'CL',
                'type'          => 'cancel'
            ]);
            if ($request->unmappedstatus == "userCancelled") {
                return view('site.checkout.payment_cancelled');
            } else {
                return view('site.checkout.payment_error');
            }
        }
        return view('site.checkout.payment_cancelled');
    }

    public function paymentError()
    {
        return view('site.checkout.payment_error');
    }

}