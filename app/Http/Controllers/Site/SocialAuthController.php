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
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductExtra;
use App\Models\AppliedCoupon;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Crypt;
use PDF;

class SocialAuthController extends RootController
{
    public function gmailregister(Request $request){
        $login=0;
        if($request->isMethod('POST')){
          if(Session::has('email'))
            $email=Session::get('email');
          else
            $email=$_POST['email'];
           
             $check_user_exists = User::where('email',$email)->first();     	
             if($check_user_exists != null){
                if(isset($check_user_exists->status) && $check_user_exists->status == 'A'){              
                Auth::guard('web')->loginUsingID($check_user_exists->id,true);
                //Login success
                  $login=1;
                    //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart start//
                    if ($request->session()->has('Cart.session_id')) {
                        $sessionId = $request->session()->get('Cart.session_id');
                        $this->mergeCartDetails( $sessionId );
                    }
                    //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart end//
                }else{
                //Fail login due to inactive user
                    $login=2;
                    $request->session()->flash('alert-danger','Account is inactive.');
                }
            }else{
           
                $user = new User;
                $user->email = $email;
                $user->password = Hash::make($email);
                $user->name = ucfirst($_POST['name']);
                $user->user_type = 'G';
                $user->status = 'A';
                //$user->profilepic = $file_name;
                if($user->save()){
                    Auth::guard('web')->attempt(['email' =>$email,'password' => $email]);
                    //Login success
                    $login=1;
                    //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart start//
                    if ($request->session()->has('Cart.session_id')) {
                        $sessionId = $request->session()->get('Cart.session_id');
                        $this->mergeCartDetails( $sessionId );
                    }
                    //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart end//
                }
          }
          ob_start();
          ob_flush();
        }
        echo json_encode($login);
    }

    public function fbregister(Request $request){
        $login=0;
        if($request->isMethod('POST')){
            if(Session::has('email'))
                $email=Session::get('email');
            else
                $email=$_POST['email'];

            $check_user_exists=User::where('email','=',$email)->first();
            if($check_user_exists != null){
                if(isset($check_user_exists->status) && $check_user_exists->status == 'A'){
                    Auth::guard('web')->loginUsingID($check_user_exists->id,true);
                    //Login success
                    $login=1;
                    //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart start//
                    if ($request->session()->has('Cart.session_id')) {
                        $sessionId = $request->session()->get('Cart.session_id');
                        $this->mergeCartDetails( $sessionId );
                    }
                    //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart end//
                }else{
                    //Fail login due to inactive user
                    $login=2;
                    $request->session()->flash('alert-danger','Account is inactive.');
                }
            }else{
                $user = new User;
                $user->email = $email;
                $user->password = Hash::make($email);
                $user->name = ucfirst($_POST['first_name']).' '.$_POST['last_name'];
                $user->user_type = 'F';
                $user->status = 'A';
                if($user->save()){
                    Auth::guard('web')->attempt(['email' =>$email,'password' => $email]);
                    //Login success
                    $login=1;
                    //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart start//
                    if ($request->session()->has('Cart.session_id')) {
                        $sessionId = $request->session()->get('Cart.session_id');
                        $this->mergeCartDetails( $sessionId );
                    }
                    //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart end//
                }
            }
            ob_start();
            ob_flush();
        }
        echo json_encode($login);
    }

    public function gmailregistercheckout(Request $request){
		$login=0;
		if($request->isMethod('POST')){
			if(Session::has('email'))
				$email = Session::get('email');
			else
				$email = $_POST['email'];
      
			$check_user_exists = User::where('email',$email)->first();      
			if($check_user_exists !== null){
				if(isset($check_user_exists->status) && $check_user_exists->status == 'A'){              
					Auth::guard('web')->loginUsingID($check_user_exists->id,true);
					//Login success
					$login=1;

					//Update CURRENT SESSION Cart details with PREVIOUS SESSION cart start//
					if ($request->session()->has('Cart.session_id')) {
                        //echo "hello"; die;
						$sessionId = $request->session()->get('Cart.session_id');
                        //echo $sessionId; die;
						self::mergeCartDetails( $sessionId );
					}
					//Update CURRENT SESSION Cart details with PREVIOUS SESSION cart end//

					//Update header cart count start//
					$header_order = Order::where(['user_id'=>Auth::user()->id, 'type'=>'cart'])->orderBy('id', 'desc')->first();
					$total_orders = 0;
					if( $header_order != null ){
						$total_orders = OrderDetail::where([['order_id',$header_order->id],['order_details_id',0]])->count();
					}
					//Update header cart count end//
					//echo "===>".$total_orders; die;
					
					$user_id    = Auth::user()->id;
                    $user_type  = Auth::user()->user_type;

                    return response()->json(['type'=>'success', 'total_orders'=>$total_orders, 'msg'=>'Login success.', 'user_id'=>$user_id, 'user_type'=>$user_type]);
				}else{
					//Fail login due to inactive user
					$login=2;
					return response()->json(['type'=>'error', 'msg'=>'Your account is inactive.']);
				}
			}else{
				//$picture = explode('?',$_POST['picture']);
				//$picture[1] = "sz=150";
				//$pic = implode('?', $picture);
				//$picture[1] = "sz=150";
				//$pic = implode('?', $picture);
				//$file_name = 'g-img'.rand('999','9999').time().".jpg";
				//$image = file_get_contents($pic);

				//$path=public_path('uploaded/profile_pic/'.$file_name);
				//dd($path);

				//file_put_contents($path, $image);
				$user = new User;
				$user->email = $email;
				$user->password = Hash::make($email);
				//$user->name = ucfirst($_POST['first_name']).' '.$_POST['last_name'];
                $user->name = ucfirst($_POST['first_name']);
				$user->user_type = 'G';
				$user->status = 'A';
				//$user->profilepic = $file_name;
				if($user->save()){
					Auth::guard('web')->attempt(['email' =>$email,'password' => $email]);
					//Login success
					$login=1;

					//Update CURRENT SESSION Cart details with PREVIOUS SESSION cart start//
					if ($request->session()->has('Cart.session_id')) {
						$sessionId = $request->session()->get('Cart.session_id');              
						self::mergeCartDetails( $sessionId );
					}
					//Update CURRENT SESSION Cart details with PREVIOUS SESSION cart end//

					//Update header cart count start//
					$header_order = Order::where(['user_id'=>Auth::user()->id, 'type'=>'cart'])->orderBy('id', 'desc')->first();
					$total_orders = 0;
					if( $header_order != null ){
						$total_orders = OrderDetail::where([['order_id',$header_order->id],['order_details_id',0]])->count();
					}
					//Update header cart count end//
					//echo "===>".$total_orders; die;
					
					$user_id    = Auth::user()->id;
                    $user_type  = Auth::user()->user_type;

                    return response()->json(['type'=>'success', 'total_orders'=>$total_orders, 'msg'=>'Login success.', 'user_id'=>$user_id, 'user_type'=>$user_type]);
				}
			}
			ob_start();
			ob_flush();
		}
		//echo json_encode($login);
    }

    public function fbregistercheckout(Request $request){
		//dd($request);
		//$login=0;
		if($request->isMethod('POST')){
			$total_orders = 0;
			if(Session::has('email'))
				$email=Session::get('email');
			else
				$email=$_POST['email'];

			$check_user_exists=User::where('email','=',$email)->first();
			//dd($check_user_exists);              
			if($check_user_exists !== null){
				if(isset($check_user_exists->status) && $check_user_exists->status == 'A'){
					Auth::guard('web')->loginUsingID($check_user_exists->id,true);
					//Login success
					$login=1;

					//Update CURRENT SESSION Cart details with PREVIOUS SESSION cart start//
					if ($request->session()->has('Cart.session_id')) {
						$sessionId = $request->session()->get('Cart.session_id');
						self::mergeCartDetails( $sessionId );
					}
					//Update CURRENT SESSION Cart details with PREVIOUS SESSION cart end//

					//Update header cart count start//
					$header_order = Order::where(['user_id'=>Auth::user()->id, 'type'=>'cart'])->orderBy('id', 'desc')->first();
					$total_orders = 0;
					if( $header_order != null ){
						$total_orders = OrderDetail::where([['order_id',$header_order->id],['order_details_id',0]])->count();
					}
					//Update header cart count end//
					//echo "===>".$total_orders; die;
					
					$user_id    = Auth::user()->id;
                    $user_type  = Auth::user()->user_type;

                    return response()->json(['type'=>'success', 'total_orders'=>$total_orders, 'msg'=>'Login success.', 'user_id'=>$user_id, 'user_type'=>$user_type]);
				}else{
					//Fail login due to inactive user
					$login=2;
					return response()->json(['type'=>'error', 'msg'=>'Your account is inactive.']);
				}
			}else{
				$user = new User;
				$user->email = $email;
				$user->password = Hash::make($email);
				$user->name = ucfirst($_POST['first_name']).' '.$_POST['last_name'];
				$user->user_type = 'F';
				$user->email_verified = 'Y';
				$user->status = 'A';
				if($user->save()){
					Auth::guard('web')->attempt(['email' =>$email,'password' => $email]);
					//Login success
					$login=1;
            
					//Update CURRENT SESSION Cart details with PREVIOUS SESSION cart start//
					if ($request->session()->has('Cart.session_id')) {
						$sessionId = $request->session()->get('Cart.session_id');              
						self::mergeCartDetails( $sessionId );
					}
					//Update CURRENT SESSION Cart details with PREVIOUS SESSION cart end//

					//Update header cart count start//
					$header_order = Order::where(['user_id'=>Auth::user()->id, 'type'=>'cart'])->orderBy('id', 'desc')->first();
					$total_orders = 0;
					if( $header_order != null ){
					  $total_orders = OrderDetail::where([['order_id',$header_order->id],['order_details_id',0]])->count();
					}
					//Update header cart count end//
					//echo "===>".$total_orders; die;
					
					$user_id    = Auth::user()->id;
                    $user_type  = Auth::user()->user_type;

                    return response()->json(['type'=>'success', 'total_orders'=>$total_orders, 'msg'=>'Login success.', 'user_id'=>$user_id, 'user_type'=>$user_type]);
				}
			}
			ob_start();
			ob_flush();
		}
        //echo json_encode($login);
    }

    //------------------FOR MERGE CART---------------------------------------------

    public function get_date_time() {
        /*$ip = '103.251.83.170';
        //$ip = '110.142.215.61';
        //$ip = $_SERVER['REMOTE_ADDR'];
        $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));

        if( isset($query) && $query['status'] == 'success' ) {
            date_default_timezone_set($query['timezone']);
            return date('Y-m-d H:i:s');
        }else{
            date_default_timezone_set('Asia/Kolkata');
            return date('Y-m-d H:i:s');
        }*/
        date_default_timezone_set('Asia/Kolkata');
        return date('Y-m-d H:i:s');
    }

    //Getting IP wise details and current time
    public static function get_time(){
        /*$ip = '103.251.83.170';
        //$ip = '110.142.215.61';
        //$ip = $_SERVER['REMOTE_ADDR'];
        $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));

        if( isset($query) && $query['status'] == 'success' ) {
            date_default_timezone_set($query['timezone']);
            return date('H:i');
        }else{
            date_default_timezone_set('Asia/Kolkata');
            return date('H:i');
        }*/
        date_default_timezone_set('Asia/Kolkata');
        return date('H:i');
    }

    public function get_cart_item_details() {
        if (session()->has('Cart.session_id')) {
            $sessionId = session()->get('Cart.session_id');
        }else{
            $sId = session()->getId();
            session(['Cart.session_id'=> $sId]);
            $sessionId = session()->get('Cart.session_id');
        }
    
    
        $user_id = 0;
        $get_data = array();
        $cart_detail_array = array();
        if( Auth::check() ){
            $user_id = Auth::user()->id;
            $conditions = ['user_id'=>$user_id,'type'=>'cart'];
            //$conditions = ['session_id'=>$sessionId,'type'=>'cart'];
        }else{
            $conditions = ['session_id'=>$sessionId,'type'=>'cart'];
        }

        //print_r($conditions); die;
       
        $order_dtl = Order::where($conditions)->first();
        //dd($order_dtl);

        $total_cart_price = 0.00; $occasion_product_price = 0;
        $cart_array = array();
        if( $order_dtl != null ) {

            //If Delivery Date cross the Current Date then delete that product
            $now        = self::get_date_time();
            $only_date  = date('Y-m-d',strtotime($now));

            //DB::enableQueryLog();

            /*Delete those product from CART whose delivery date is less than Current Date*/
            $delete_data = OrderDetail::where([
                                        ['order_id','=',$order_dtl->id],
                                        ['delivery_date','<',$only_date],
                                        ['order_status','=','IP'],
                                        ['is_shipped','=','N'],
                                        ['is_delivered','=','N']
                                    ])->delete();

            /*Get those product from CART whose delivery date is equals to Current Date*/
            $getdata = OrderDetail::where([
                                        ['order_id','=',$order_dtl->id],
                                        ['delivery_date','=',$only_date],
                                        ['order_status','=','IP'],
                                        ['is_shipped','=','N'],
                                        ['is_delivered','=','N']
                                    ])->get();

            //dd(DB::getQueryLog());     
            //dd($getdata);
            //If Delivery Date cross the Current Date then delete that product

            //In Gift Extra addon is programmed as Gift Addon//
            //dd($order_dtl);
            //dd($order_dtl->order_detail);
            if( isset($order_dtl->order_detail) && count($order_dtl->order_detail) > 0 ) {
                $i = 0;
                foreach( $order_dtl->order_detail as $productLists ) {
                    //dd($productLists);

                    //If Extra Addons NOT exist
                    if( $productLists->order_details_id == 0 ) {
                        //If Gift Addons NOT exist
                        if( $productLists->gift_addon_id == 0 ) {

                            $product_image_name = '';

                            if( isset($productLists->product->default_product_image) && $productLists->product->default_product_image != null ) {
                                $product_image_name = $productLists->product->default_product_image->name;
                            }
                            $cart_array[$i]['order_detail_id']      = $productLists->id;
                            $cart_array[$i]['order_details_id_giftaddon']= $productLists->order_details_id_giftaddon;
                            $cart_array[$i]['category_id']          = $productLists->category_id;
                            $cart_array[$i]['occasion_id']          = $productLists->occasion_id;
                            $cart_array[$i]['product_id']           = $productLists->product->id;
                            $cart_array[$i]['product_attr_id']      = $productLists->product_attr_id;
                            $cart_array[$i]['gift_addon_id']        = $productLists->gift_addon_id;
                            $cart_array[$i]['product_name']         = $productLists->product->product_name;
                            $cart_array[$i]['image']                = $product_image_name;
                            $cart_array[$i]['qty']                  = $productLists->qty;

                            $cart_array[$i]['delivery_country']     = $productLists->country_detail->name;
                            $cart_array[$i]['delivery_country_id']  = $productLists->delivery_country_id;
                            $cart_array[$i]['delivery_city_id']     = $productLists->delivery_city_id;
                            $cart_array[$i]['delivery_city_name']   = $productLists->delivery_city_name;
                            $cart_array[$i]['delivery_date']        = $productLists->delivery_date;
                            $cart_array[$i]['shippingmethod_id']    = $productLists->shippingmethod_id;
                            $cart_array[$i]['shippingmethod_name']  = $productLists->shippingmethod_name;
                            $cart_array[$i]['ship_price']           = $productLists->ship_price;

                            $cart_array[$i]['product_unit_price']   = $productLists->unit_price;
                            $cart_array[$i]['unit_price']           = $productLists->unit_price;
                            $cart_array[$i]['total_price']          = $productLists->total_price;
                            $total_cart_price                       = $total_cart_price + $productLists->total_price;

                            //For occasion related product (Total Price)//
                            if( $productLists->product->occasions_id != null ) {
                                $occasion_product_price = $occasion_product_price + $productLists->total_price;
                            }
                            //For occasion related product (Total Price)//

                            if( $productLists->product_attr_id != 0 ) {
                                foreach ( $productLists->product->order_product_all_attributes as $key => $value) {
                                    if( $value->id == $productLists->product_attr_id ) {
                                        $cart_array[$i]['attribute_name'] = $value->title;
                                    }
                                }
                            }
                            else{
                                $cart_array[$i]['attribute_name'] = '';
                            }
                        }
                        else{   //If Gift Addons exist

                            if(isset($productLists->extraaddon_detail) && $productLists->extraaddon_detail !=null){

                                // $giftaddon_image_name = '';
                                // $giftaddon_image_name = $productLists->extraaddon_detail->image;

                                $giftaddon_image_name = '';
                                $addonProduct = Product::where(['id' => $productLists->gift_addon_id])->select('product_name')->first();
                                $addonProductImage = ProductImage::where(['product_id' => $productLists->gift_addon_id, 'default_image' => 'Y'])->select('name')->first();
                                $giftaddon_image_name = $addonProductImage->name;
                                
                                $cart_array[$i]['order_detail_id']     = $productLists->id;
                                $cart_array[$i]['order_details_id_giftaddon']= $productLists->order_details_id_giftaddon;
                                $cart_array[$i]['category_id']         = $productLists->category_id;
                                $cart_array[$i]['occasion_id']         = $productLists->occasion_id;
                                $cart_array[$i]['product_id']          = $productLists->product_id;
                                $cart_array[$i]['gift_addon_id']       = $productLists->gift_addon_id;
                                //$cart_array[$i]['product_name']        = $productLists->extraaddon_detail->title;
                                //$cart_array[$i]['image']               = $giftaddon_image_name;
                                $cart_array[$i]['product_name']        = $addonProduct->product_name;
                                $cart_array[$i]['image']               = $giftaddon_image_name;
                                $cart_array[$i]['qty']                 = $productLists->qty;

                                $cart_array[$i]['delivery_country']    = $productLists->country_detail->name;
                                $cart_array[$i]['delivery_country_id'] = $productLists->delivery_country_id;
                                $cart_array[$i]['delivery_city_id']    = $productLists->delivery_city_id;
                                $cart_array[$i]['delivery_city_name']  = $productLists->delivery_city_name;

                                $cart_array[$i]['delivery_date']       = $productLists->delivery_date;
                                $cart_array[$i]['shippingmethod_id']   = $productLists->shippingmethod_id;
                                $cart_array[$i]['shippingmethod_name'] = $productLists->shippingmethod_name;
                                $cart_array[$i]['ship_price']          = $productLists->ship_price;

                                $cart_array[$i]['product_unit_price']  = $productLists->unit_price;
                                $cart_array[$i]['unit_price']          = $productLists->unit_price;
                                $cart_array[$i]['total_price']         = $productLists->total_price;

                                $total_cart_price                      = $total_cart_price+$productLists->total_price;

                                //If product attribute exist
                                if(isset($productLists->product->order_product_attribute) && $productLists->product->order_product_attribute['title'] != null){
                                    $cart_array[$i]['attribute_name']=$productLists->product->order_product_attribute['title'];
                                }
                                else{
                                    $cart_array[$i]['attribute_name'] = '';
                                }
                            }
                        }
                    }   //If Extra Addons NOT exist condition end here
                    $i++;
                }
            }
        }
        //dd($cart_array);

        $total_cart_count_item = count($cart_array);
        //dd($order_dtl);
        if( $order_dtl != null ){
            $cart_detail_array = array('order_id'=>$order_dtl['id'], 'unique_order_id'=>$order_dtl['unique_order_id'], 'existing_order_session_id'=>$order_dtl['session_id'], 'item_dtl'=>$cart_array, 'total_item'=>$total_cart_count_item, 'total_cart_price'=>$total_cart_price, 'occasion_product_price'=>$occasion_product_price);
        }
        //dd($cart_detail_array);
        return $cart_detail_array;
    }

    //Get Cart details based on SESSION ID
    public function get_cart_item_details_using_sessionid() {
        $session_id = 0;
        if (session()->has('Cart.session_id')) {
            $session_id = session()->get('Cart.session_id');
        }       
        $conditions = ['session_id'=>$session_id,'type'=>'cart'];
       
        $order_details = Order::where($conditions)->first();
        //dd($order_details);
        
        $total_cart_price = 0; $cart_session_array = array(); $product_related_details = array();

        if( $order_details != null ) {
            if( isset($order_details->order_detail) && count($order_details->order_detail) > 0 ) {
                $i = 0;
                foreach( $order_details->order_detail as $productLists ) {
                    //dd($productLists);

                    //If Gift Addons NOT exist
                    if( $productLists->gift_addon_id == 0 ) {
                        $product_image_name = '';
                        if( isset($productLists->product->default_product_image) && $productLists->product->default_product_image != null ) {
                            $product_image_name = $productLists->product->default_product_image['name'];
                        }
                        $cart_session_array[$i]['order_detail_id']  = $productLists->id;
                        $cart_session_array[$i]['product_id']       = $productLists->product->id;
                        $cart_session_array[$i]['order_details_id_giftaddon']= $productLists->order_details_id_giftaddon;
                        $cart_session_array[$i]['category_id']      = $productLists->category_id;
                        $cart_session_array[$i]['occasion_id']      = $productLists->occasion_id;
                        $cart_session_array[$i]['product_attr_id']  = $productLists->product_attr_id;
                        $cart_session_array[$i]['gift_addon_id']    = $productLists->gift_addon_id;
                        $cart_session_array[$i]['product_name']     = $productLists->product->product_name;
                        $cart_session_array[$i]['qty']              = $productLists['qty'];

                        $cart_session_array[$i]['delivery_country'] = $productLists->country_detail->name;
                        $cart_session_array[$i]['delivery_country_id'] = $productLists->delivery_country_id;
                        $cart_session_array[$i]['delivery_city_name'] = $productLists->delivery_city_name;
                        $cart_session_array[$i]['delivery_city_id'] = $productLists->delivery_city_id;

                        $cart_session_array[$i]['delivery_date']      = $productLists->delivery_date;
                        $cart_session_array[$i]['shippingmethod_id']  = $productLists->shippingmethod_id;
                        $cart_session_array[$i]['shippingmethod_name']= $productLists->shippingmethod_name;
                        $cart_session_array[$i]['ship_price']         = $productLists->ship_price;

                        $cart_session_array[$i]['unit_price']         = $productLists->unit_price;
                        $cart_session_array[$i]['total_price']        = $productLists->total_price;
                        $total_cart_price                             = $total_cart_price + $productLists->total_price;
                    }
                    else{   //If Gift Addons exist
                        $giftaddon_image_name = '';
                        $giftaddon_image_name = $productLists->extraaddon_detail->image;
                        
                        $cart_session_array[$i]['order_detail_id']  = $productLists->id;
                        $cart_session_array[$i]['product_id']       = $productLists->product_id;
                        $cart_session_array[$i]['order_details_id_giftaddon']= $productLists->order_details_id_giftaddon;
                        $cart_session_array[$i]['category_id']      = $productLists->category_id;
                        $cart_session_array[$i]['occasion_id']      = $productLists->occasion_id;
                        $cart_session_array[$i]['product_attr_id']  = $productLists->product_attr_id;
                        $cart_session_array[$i]['gift_addon_id']    = $productLists->gift_addon_id;
                        $cart_session_array[$i]['product_name']     = $productLists->extraaddon_detail->title;
                        $cart_session_array[$i]['image']            = $giftaddon_image_name;
                        $cart_session_array[$i]['qty']              = $productLists->qty;

                        $cart_session_array[$i]['delivery_country'] = $productLists->country_detail->name;
                        $cart_session_array[$i]['delivery_country_id'] = $productLists->delivery_country_id;
                        $cart_session_array[$i]['delivery_city_name'] = $productLists->delivery_city_name;
                        $cart_session_array[$i]['delivery_city_id'] = $productLists->delivery_city_id;

                        $cart_session_array[$i]['delivery_date']      = $productLists->delivery_date;
                        $cart_session_array[$i]['shippingmethod_id']  = $productLists->shippingmethod_id;
                        $cart_session_array[$i]['shippingmethod_name']= $productLists->shippingmethod_name;
                        $cart_session_array[$i]['ship_price']         = $productLists->ship_price;

                        $cart_session_array[$i]['order_details_primary_id'] = 0;
                        
                        $cart_session_array[$i]['unit_price']       = $productLists->unit_price;
                        $cart_session_array[$i]['total_price']      = $productLists->total_price;
                        $total_cart_price                           = $total_cart_price+$productLists->total_price;
                    }
                    $i++;
                }
            }
        }
        //dd($cart_session_array);

        $total_cart_count_item = count($cart_session_array);
        
        $session_cart_detail_array = array('order_id'=>$order_details['id'],'items'=>$cart_session_array,'total_item'=>$total_cart_count_item,'total_cart_price'=>$total_cart_price,'product_related_details'=>$product_related_details);
        
        return $session_cart_detail_array;
    }

    //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart start//
    public function mergeCartDetails( $sessionId = null ) {
        if( Auth::check() ){

            $order_dtls = self::get_cart_item_details();    //Existing order details
            //echo '<pre>sanjay'; print_r($order_dtls); die;

            $order_details_sessionwise = self::get_cart_item_details_using_sessionid();  //Session ID wise order details
            //echo '<pre>kar'; print_r($order_details_sessionwise['items']); die;

            if( !empty($order_details_sessionwise) && !empty($order_details_sessionwise['items']) ) {

                //Session related details
                $session_cart_orderid    = $order_details_sessionwise['order_id'];

                //Existing order related details
                $existing_cart_order_id  = isset($order_dtls['order_id'])?$order_dtls['order_id']:0;
                
                //print_r($order_dtls['item_dtl']);
                //echo $session_cart_orderid;
                //echo $existing_cart_order_id; die;
                //echo gmdate("Y-m-d H:i:s"); die;
                //$k=1;
                //Loop for Session Cart related details
                Order::where('id', $existing_cart_order_id)->update(['updated_at' => gmdate('Y-m-d H:i:s'), 'ip_address' => $_SERVER["REMOTE_ADDR"]]);

                foreach ( $order_details_sessionwise['items'] as $key => $value ) {
                    //echo '<pre>'; print_r($value); die;

                    //Session related details
                    $session_order_detail_id = isset($value['order_detail_id'])?$value['order_detail_id']:0;
                    $product_id              = isset($value['product_id'])?$value['product_id']:0;
                    $product_attr_id         = isset($value['product_attr_id'])?$value['product_attr_id']:0;
                    $gift_addon_id           = isset($value['gift_addon_id'])?$value['gift_addon_id']:0;
                    $qty                     = isset($value['qty'])?$value['qty']:1;

                    $unit_price              = isset($value['unit_price'])?$value['unit_price']:0;
                    $total_price             = isset($value['total_price'])?$value['total_price']:0;

                    $order_details_primary_id= isset($value['order_details_primary_id'])?$value['order_details_primary_id']:0;

                    $session_delivery_country   = isset($value['delivery_country'])?$value['delivery_country']:NULL;
                    $session_delivery_city_name = isset($value['delivery_city_name'])?$value['delivery_city_name']:NULL;


                    $session_delivery_country_id = isset($value['delivery_country_id'])?$value['delivery_country_id']:0;
                    $session_delivery_city_id = isset($value['delivery_city_id'])?$value['delivery_city_id']:0;

                    $session_delivery_date      = isset($value['delivery_date'])?$value['delivery_date']:NULL;
                    $session_shippingmethod_id  = isset($value['shippingmethod_id'])?$value['shippingmethod_id']:0;
                    $session_shippingmethod_name= isset($value['shippingmethod_name'])?$value['shippingmethod_name']:NULL;
                    $session_ship_price         = isset($value['ship_price'])?$value['ship_price']:0;


                    if( !empty( $order_dtls ) && !empty( $order_dtls['item_dtl'] ) && $session_order_detail_id != 0 ) {

                        //Loop for EXISTING order details start here
                        foreach ( $order_dtls['item_dtl'] as $key1 => $val1 ) {

                            $quantity = 0; $totalprice = 0; $product_totalprice = 0;

                            //If This is NOT Gift Addon start here
                            if( $val1['product_id'] != 0 ) {
                                
                                //If Country ID change then delete the previous Country related order 26.05.2019
                                if( $val1['delivery_country_id'] != $session_delivery_country_id ) {
                                    $check_order_detail = OrderDetail::where([
                                                                'id'                 => $val1['order_detail_id'],
                                                                'delivery_country_id'=> $val1['delivery_country_id']
                                                                    ])
                                                            ->delete();
                                }

                                if( $val1['delivery_city_id'] != $session_delivery_city_id ) {
                                    $check_order_detail = OrderDetail::where([
                                                                'id'                 => $val1['order_detail_id'],
                                                                'delivery_city_id'   => $val1['delivery_city_id']
                                                                    ])
                                                            ->delete();
                                }

                                //echo $product_id."==".$val1['product_id']."==".$session_delivery_country_id."==".$val1['delivery_country_id']."==".$session_delivery_city_id."==".$val1['delivery_city_id']."==".$session_delivery_date."==".$val1['delivery_date']."==".$session_shippingmethod_id."==".$val1['shippingmethod_id']; die;

                                //If Product id Matched start
                                if( $product_id == $val1['product_id'] && $session_delivery_country_id == $val1['delivery_country_id'] && $session_delivery_city_id == $val1['delivery_city_id'] && $session_delivery_date == $val1['delivery_date'] && $session_shippingmethod_id == $val1['shippingmethod_id'] ) {

                                    //Gift Addon NOT EXIST section start
                                    if($val1['gift_addon_id'] == 0) {
                                        if( $gift_addon_id == 0 && $session_delivery_date == $val1['delivery_date'] && $session_shippingmethod_id == $val1['shippingmethod_id'] ) {

                                            $quantity           = $qty + $val1['qty'];
                                            $product_totalprice = $unit_price * $quantity;
                                            $totalprice         = $product_totalprice;

                                            //This is for ONLY product
                                            OrderDetail::where([
                                                        'id'                 => $val1['order_detail_id'],
                                                        'order_id'           => $existing_cart_order_id,
                                                        'delivery_country_id'=>$session_delivery_country_id,
                                                        'delivery_city_id'   => $session_delivery_city_id,
                                                        'delivery_date'      => $session_delivery_date,
                                                        'shippingmethod_id'  => $session_shippingmethod_id
                                                    ])
                                                    ->update([
                                                            'qty'           => $quantity,
                                                            'total_price'   => $product_totalprice
                                                    ]);
                                            //In previous query existing ORDER details updated so DELETE session related one
                                            OrderDetail::where([
                                                        'id'                 => $session_order_detail_id,
                                                        'order_id'           => $session_cart_orderid,
                                                        'delivery_country_id'=>$session_delivery_country_id,
                                                        'delivery_city_id'   => $session_delivery_city_id,
                                                        'delivery_date'      => $session_delivery_date,
                                                        'shippingmethod_id'  => $session_shippingmethod_id
                                                    ])->delete();                                        
                                        }
                                    }
                                    //PRODUCT ATTRIBUTE and EXTRA ADDON both NOT exist in EXISTING order end
                                }
                                //If Product id Matched end
                            }
                            //If This is NOT Gift Addon end here

                            //GIFT ADDON matching start//
                            else{
                                if( $gift_addon_id != 0 ) {
                                    //If matched with previous GIFT ADDON id then UPDATE start
                                    if( $gift_addon_id == $val1['gift_addon_id'] && $session_delivery_country_id == $val1['delivery_country_id'] && $session_delivery_city_id == $val1['delivery_city_id'] && $session_delivery_date == $val1['delivery_date'] && $session_shippingmethod_id == $val1['shippingmethod_id'] ) {
                                        $quantity   = $qty + $val1['qty'];
                                        $totalprice = $unit_price * $quantity;

                                        OrderDetail::where([
                                                        'order_id'          => $existing_cart_order_id,
                                                        'gift_addon_id'     => $val1['gift_addon_id'],
                                                        'delivery_country_id'=>$session_delivery_country_id,
                                                        'delivery_city_id'  => $session_delivery_city_id,
                                                        'delivery_date'     => $session_delivery_date,
                                                        'shippingmethod_id' => $session_shippingmethod_id
                                                    ])
                                                    ->update([
                                                        'qty'=>$quantity,
                                                        'total_price'=>$totalprice
                                                    ]);

                                        //In previous query existing ORDER details updated so DELETE session related one
                                        OrderDetail::where([
                                                        'order_id'           => $session_cart_orderid,
                                                        'gift_addon_id'      => $val1['gift_addon_id'],
                                                        'delivery_country_id'=>$session_delivery_country_id,
                                                        'delivery_city_id'   => $session_delivery_city_id,
                                                        'delivery_date'      => $session_delivery_date,
                                                        'shippingmethod_id'  => $session_shippingmethod_id
                                                    ])
                                                    ->delete();
                                    }
                                    //If matched with previous GIFT ADDON id then UPDATE end
                                    
                                    //If NOT matched with previous GIFT ADDON id then UPDATE the ORDER ID only start
                                    else{
                                        OrderDetail::where([
                                                        'order_id'           => $session_cart_orderid,
                                                        'gift_addon_id'      => $gift_addon_id,
                                                        'delivery_country_id'=>$session_delivery_country_id,
                                                        'delivery_city_id'   => $session_delivery_city_id,
                                                        'delivery_date'      => $session_delivery_date,
                                                        'shippingmethod_id'  => $session_shippingmethod_id
                                                    ])
                                                    ->update([
                                                        'order_id'=>$existing_cart_order_id
                                                    ]);
                                    }
                                    //If NOT matched with previous GIFT ADDON id then UPDATE the ORDER ID only end

                                    $quantity = 0; $totalprice = 0;
                                }
                            }
                            //GIFT ADDON matching end//
                        }
                        //Loop for EXISTING order details end here
                    }
                    else{
                        if( $session_order_detail_id != 0 ) {
                            //echo "===>".$order_details_sessionwise['order_id']; die;
                            Order::where('id',$existing_cart_order_id)->delete();
                            Order::where('id',$order_details_sessionwise['order_id'])->update(['user_id'=>Auth::user()->id, 'updated_at' => date('Y-m-d H:i:s')]);
                            
                        }
                    }

                    unset($product_id); unset($product_attr_id); unset($gift_addon_id); unset($product_extra_addon_ids);

                    //$k++;
                }

                //For those that doesn't match with the EXISTING ORDER details just change the ORDER ID and others
                if( !empty($order_dtls) && !empty($order_dtls['item_dtl']) ) {
                    //Update the session order id with existing one
                    OrderDetail::where('order_id',$session_cart_orderid)->update(['order_id'=>$existing_cart_order_id]);

                    //If Session id related NO ORDER DETAILS exist then delete that order (bcz all have been moved to LOGGED IN user's account)
                    if(OrderDetail::where('order_id',$session_cart_orderid)->count() == 0) {
                        Order::where('id',$session_cart_orderid)->delete();
                    }

                    //Storing order id for Gift Addon Add to cart section
                    session([
                            'Cart.order_id' => $order_dtls['order_id']
                            ]);
                }

                //Storing order id
                //Session::put('Cart.order_id', $existing_cart_order_id);
            }
            //dd($order_details_sessionwise);
        }
    }
    //Update CURRENT SESSION Cart details with PREVIOUS SESSION cart end//



    

}