<?php
namespace App\Http\Controllers\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Coupon;
use App\Models\AppliedCoupon;
use App\Models\City;
use Auth;
use DB;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        //$this->middleware('auth');
    }

    /**
     * Show the front page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        //echo "Test";
        $cart_data = $this->get_cart_item_details();
		/*print"<pre>";
		print_r($cart_data);
		die;*/
        //dd( Session::get('Cart.session_id') );

        return view('site.cart', ['cart_data' => $cart_data]);
    }

    /*------------------add to cart-----------------------------------------*/
    public function addToCart(Request $request) {
        //die;
        if($request->isMethod('POST')){
            
            /*-----Get session id data----------*/
            if ($request->session()->has('Cart.session_id')) {
                $sessionId = $request->session()->get('Cart.session_id');
            } else {
               $sId = $request->session()->getId();
               session(['Cart.session_id'=> $sId]);
               $sessionId = $request->session()->get('Cart.session_id');
            }

            $unique_order_id = $this->Order_number();

            /**** INSERT INTO ORDER TABLE ***/
            $user_id = 0;
            $get_data = array();
            if( Auth::check() ) {
               $user_id = Auth::user()->id;
               //$conditions = ['session_id'=>$sessionId,'type'=>'cart'];
              $conditions = ['user_id'=>$user_id,'type'=>'cart'];
            }else{
               $conditions = ['session_id'=>$sessionId,'type'=>'cart'];
            }

            $order = Order::where($conditions)->first();
            // print"<pre>";
            // print_r($order);
            // die; 
            if($order != '' && $order != null){
                Order::where('id', $order->id)->update(['updated_at' => gmdate('Y-m-d H:i:s')]);
                $order_id = $order->id;
            }else{
                $order_array = array();
                $order_array['session_id'] = $sessionId;
                $order_array['user_id'] = $user_id;
                $order_array['unique_order_id'] = $unique_order_id;
                $order_array['ip_address'] = $_SERVER["REMOTE_ADDR"];
                $order_array['type'] = 'cart';
                $order_array['created_at'] = date('Y-m-d H:i:s');
                $order = Order::create($order_array);
                $order_id = $order->id;

                //DB::select( DB::raw("INSERT INTO orders_savebkup SELECT * FROM orders WHERE id=".$order->id) );
            }

            //dd($order);

            //Storing order id for Extra Addon Add to cart section
            session(['Cart.order_id'=> $order_id]);

            /**** INSERT INTO ORDER DETAIL TABLE ***/
            if(isset($request->product_id) && isset($request->quantity) ) {

                if(Session::has('checkout.city_id')) {
                    $session_city_id = Session::get('checkout.city_id');
                    
                    if( isset($order->order_detail) && count($order->order_detail) > 0 ) {
                        $order_delivery_city_id = $order->order_detail[0]->delivery_city_id;
                        if($order->order_detail[0]->delivery_city_id != $session_city_id ) {
                            $check_order_detail = OrderDetail::where(['order_id' => $order->id, 'delivery_city_id'=> $order_delivery_city_id])->delete();
                            AppliedCoupon::where(['order_id' => $order->id])->delete();
                        }
                    }
                }else{
                    $session_city_id = Session::get('Delivery.delivery_city_id');
                }

                if(Session::has('Delivery.delivery_city_id')) {
                    $delivery_city_id = Session::get('Delivery.delivery_city_id');
                    if($delivery_city_id <> $request->delivery_city_id){
                        
                        $check_order_detail = OrderDetail::where(['order_id' => $order->id, 'delivery_city_id'=> $delivery_city_id])->delete();
                        AppliedCoupon::where(['order_id' => $order->id])->delete();
                    }
                }else{
                    if( isset($order->order_detail) && count($order->order_detail) > 0 ) {
                        $order_delivery_city_id = $order->order_detail[0]->delivery_city_id;
                        if($order->order_detail[0]->delivery_city_id != $request->delivery_city_id ) {
                            $check_order_detail = OrderDetail::where(['order_id' => $order->id, 'delivery_city_id'=> $order_delivery_city_id])->delete();
                            AppliedCoupon::where(['order_id' => $order->id])->delete();
                        }
                    }
                }

                $category_id     = 0;
                $occasion_id     = 0;
                $product_id      = base64_decode($request->product_id);
                $product_attr_id = $request->product_attr_id;
                $qty             = $request->quantity;
                $delivery_pincode= null;

                $city = City::find($request->delivery_city_id);

                $delivery_country_id    = isset($session_country_id)?$session_country_id:110;
                $product_delivery_date  = isset($request->product_delivery_date)?$request->product_delivery_date:NULL;
                $shippingmethod_id      = isset($request->shippingmethod_id)?$request->shippingmethod_id:0;
                $shippingmethod_name    = isset($request->shippingmethod_name)?$request->shippingmethod_name:NULL;
                $ship_price             = isset($request->ship_price)?$request->ship_price:0;
                $delivery_city_name     = $city->name;

                $delivery_city_id       = isset($request->delivery_city_id)?$request->delivery_city_id:0;

                $giftaddon_ids          = $request->giftaddon_ids;

                session([
                    //'Delivery.delivery_country_id'  => $delivery_country_id,
                    'Delivery.product_delivery_date'=> $product_delivery_date,
                    'Delivery.delivery_city_id'     => $delivery_city_id,
                    'Delivery.delivery_city_name'   => $delivery_city_name,
                    //'Delivery.shippingmethod_id'    => $shippingmethod_id,
                    //'Delivery.shippingmethod_name'  => $shippingmethod_name,
                ]);

                $product_conditions = ['id'=>$product_id];
                //echo '<pre>'; print_r($product_conditions); die;

                $product_data = Product::where($product_conditions)->first();

                if(isset($product_data->has_attribute) && $product_data->has_attribute == 'N') {
                    $original_price = @$product_data->price;
                }else{
                    $product_attribute_data = ProductAttribute::where(['id'=>$product_attr_id,'product_id'=>$product_id])->first();
                    //echo $product_attr_id;
                    $original_price = @$product_attribute_data->price;
                }

                $check_oreder_detail = OrderDetail::where([
                                                        'order_id'               => $order->id,
                                                        'product_id'             => $product_id,
                                                        'delivery_country_id'    => $delivery_country_id,
                                                        'delivery_date'          => $product_delivery_date,
                                                        'product_attr_id'        => $product_attr_id
                                                    ])->first();



                if( $check_oreder_detail != null && $check_oreder_detail != '' ) {

                    $existorderdtl  = OrderDetail::where([
                                                        'id'                    => $check_oreder_detail->id,
                                                        'product_id'            => $product_id,
                                                        'delivery_country_id'   => $delivery_country_id,
                                                        'delivery_city_id'      => $delivery_city_id,
                                                        'delivery_date'         => $product_delivery_date,
                                                        'product_attr_id'       => $product_attr_id
                                                    ])->first();  //For main product update query

                    $updated_qty     = ($existorderdtl->qty + $qty);
                    $total           = ($original_price * $updated_qty);

                    $ordetail_submit = OrderDetail::where('id', $existorderdtl->id)
                                                    ->update([
                                                            'qty'           => $updated_qty,
                                                            'original_price'=> $original_price,
                                                            'unit_price'    => $original_price,
                                                            'total_price'   => $total,
                                                            'ship_price'    => $ship_price
                                                    ]);

                    
                } else {
                    //Inserting main product
                    $total                           = ($original_price * $request->quantity);
                    $orderDtlArray['order_id']       = $order->id;
                    $orderDtlArray['category_id']    = $category_id;
                    $orderDtlArray['occasion_id']    = $occasion_id;
                    $orderDtlArray['product_id']     = $product_id;
                    $orderDtlArray['product_attr_id']= $product_attr_id;
                    $orderDtlArray['qty']            = $qty;
                    $orderDtlArray['original_price'] = $original_price;
                    $orderDtlArray['unit_price']     = $original_price;
                    $orderDtlArray['total_price']    = $total;

                    $orderDtlArray['delivery_pincode']    = isset($request->delivery_pincode)?$request->delivery_pincode:NULL;

                    $orderDtlArray['delivery_country_id'] = isset($delivery_country_id)?$delivery_country_id:110;
                    $orderDtlArray['delivery_date']       = isset($request->product_delivery_date)?$request->product_delivery_date:NULL;
                    $orderDtlArray['shippingmethod_id']   = isset($request->shippingmethod_id)?$request->shippingmethod_id:0;
                    $orderDtlArray['shippingmethod_name'] = isset($request->shippingmethod_name)?$request->shippingmethod_name:NULL;
                    $orderDtlArray['ship_price']          = isset($request->ship_price)?$request->ship_price:0;
                    $orderDtlArray['delivery_city_id']    = isset($request->delivery_city_id)?$request->delivery_city_id:0;
                    $orderDtlArray['delivery_city_name']  = isset($delivery_city_name)?$delivery_city_name:'Kolkata';
                    $ordetail_submit                      = OrderDetail::create($orderDtlArray);

                    $mainOrderId = $ordetail_submit->id;

                    //Storing order_details_id_extraaddon for Extra Addon Section
                    $request->session()->put('order_details_id_giftaddon', $ordetail_submit->id);


                }

                if( $order != null && $ordetail_submit != null ) {
                    //========Success add to cart============//
                     return response()->json(['success'=>'Product successfully added to your shopping cart.']);
                     exit;
 
                }else{
                    return response()->json(['error'=>'An error occurred during processing. Please try again.']);
                    exit;
                }
                exit;


            }
        }
    }

    /*--------------------order number genarate-------------------*/
    function Order_number() {
        $ua = strtolower($_SERVER["HTTP_USER_AGENT"]);
        $isMob = is_numeric(strpos($ua, "mobile"));
        $forMobile = '';
        if($isMob){
            $forMobile = '-M';
        }
        $today = date("his");
        $rand = strtoupper(substr(uniqid(sha1(time())),0,4));
        return $unique = 'GBG-'.$today . $rand . $forMobile;
    }

    //Ajax Update Cart Section
    function ajxUpdateCart( Request $request ) {
        $re = 0;
        if($request->isMethod('POST')){
            $quantity = $request->qty;
            $order_detail_id = base64_decode($request->order_detail_id);
            $product_id = base64_decode($request->product_id);
            
            if( $order_detail_id != 0 && $quantity != 0 ) {
                $orderDtl               = OrderDetail::find($order_detail_id);
                $orderDtl['qty']        = $quantity;
                $orderDtl['total_price']= $orderDtl->unit_price * $quantity;

                if( $orderDtl->save() ){
                    //Updating extra addons according to main product
                    $order_related_detail   = OrderDetail::where('order_details_id', $order_detail_id)->first();
                    if( $order_related_detail != null ) {
                        $order_related_qty          = $quantity;
                        $order_related_total_price  = $order_related_detail->unit_price * $quantity;

                        OrderDetail::where('order_details_id', $order_detail_id)->update(['qty'=>$quantity,'total_price'=>$order_related_total_price]);

                        
                    }
                    //$re = 1;
                    
                }

                if( AppliedCoupon::where([['order_id',$orderDtl->order_id],['applied_for','WC']])->count() > 0 ) {
                    $cart_data = $this->get_cart_item_details();
                    
                    $applied_coupon_details = AppliedCoupon::where([['order_id',$orderDtl->order_id],['applied_for','WC']])->first();

                    if($cart_data['total_cart_price'] <= $applied_coupon_details->coupon_detail->minimum_cart_amount) {
                        AppliedCoupon::where('order_id',$orderDtl->order_id)->delete();

                        //Delete Session coupon id & coupon amount is available
                        if( Session::has('couponid') ) {
                            Session::forget('couponid');
                        }
                        if( Session::has('coupon_discount_amount') ) {
                            Session::forget('coupon_discount_amount');
                        }
                    }
                }

                return response()->json(['success'=>'Update successfull']);
            }
            
        }
        //echo $re;
    }

    /*------------------remove item from cart list section-----------*/
    function ajxRemoveItem( $order_dtl_id = null ) {
        $re = 0; $product_ids = [];
        $order_dtl_id = base64_decode($order_dtl_id);
        //dd($order_dtl_id);
        if( $order_dtl_id > 0 ){
            $get_order_detail = OrderDetail::find($order_dtl_id);
            //dd($get_order_detail);
            if( $get_order_detail != null && $get_order_detail->order_id > 0 ) {
                //Deleting if product extra addons exist
                OrderDetail::where('order_details_id',$get_order_detail->id)->delete();

                //Deleting if gift addons exist
                OrderDetail::where('order_details_id_giftaddon',$get_order_detail->id)->delete();

                $get_order_detail->delete();

                //Checking if applied coupon is related to Occasion
                if( AppliedCoupon::where([['order_id',$get_order_detail->order_id],['applied_for','OC']])->count() > 0 ) {
                    $cart_data = $this->get_cart_item_details();

                    $applied_coupon_details = AppliedCoupon::where([['order_id',$get_order_detail->order_id],['applied_for','OC']])->first();

                    $coupon_data = Coupon::where('id',$applied_coupon_details->coupon_id)->first();

                    $occasion_ids= [];
                    if( !empty( $cart_data['item_dtl'] ) ) {
                        foreach ( $cart_data['item_dtl'] as $key => $val ) {
                            if( $val['product_id'] != 0 && !in_array($val['product_id'], $product_ids) ) {
                                $product_ids[] = $val['product_id'];
                                $occasion_ids[]= $val['occasion_id'];
                            }
                        }
                        if( !empty( $product_ids ) ) {
                            $count = Product::where('occasions_id','!=',null)->whereIn('id',$product_ids)->count();
                            if( $count > 0 ) {
                                //Collecting COUPON related OCCASION ids
                                $coupon_occasion_ids = [];
                                if( $coupon_data->CouponOccation != null ) {
                                    foreach ($coupon_data->CouponOccation as $key => $val) {
                                        if( $val->occation_id != 0) {
                                            $coupon_occasion_ids[] = $val->occation_id;
                                        }
                                    }
                                }

                                //Checking ATLEAST ONE cart product occasion ids matched with coupon related occasion ids
                                if( !empty( $occasion_ids ) && !empty( $coupon_occasion_ids ) ) {
                                    $result = array_intersect($occasion_ids, $coupon_occasion_ids);
                                    if( empty( $result ) ) {
                                        AppliedCoupon::where('order_id',$get_order_detail->order_id)->delete();

                                        //Delete Session coupon id & coupon amount is available
                                        if( Session::has('couponid') ) {
                                            Session::forget('couponid');
                                        }
                                        if( Session::has('coupon_discount_amount') ) {
                                            Session::forget('coupon_discount_amount');
                                        }

                                    }
                                }
                            }else{
                                //Delete if any coupon is applied and atleast one Occasion related product exist
                                AppliedCoupon::where('order_id',$get_order_detail->order_id)->delete();

                                //Delete Session coupon id & coupon amount is available
                                if( Session::has('couponid') ) {
                                    Session::forget('couponid');
                                }
                                if( Session::has('coupon_discount_amount') ) {
                                    Session::forget('coupon_discount_amount');
                                }

                            }
                        }
                    }
                }
                //Checking if applied coupon is related to Occasion

                //Checking if applied coupon is related to Whole Cart
                else if( AppliedCoupon::where([['order_id',$get_order_detail->order_id],['applied_for','WC']])->count() > 0 ) {
                    $cart_data = $this->get_cart_item_details();
                    
                    $applied_coupon_details = AppliedCoupon::where([['order_id',$get_order_detail->order_id],['applied_for','WC']])->first();

                    if($cart_data['total_cart_price'] <= $applied_coupon_details->coupon_detail->minimum_cart_amount) {
                        AppliedCoupon::where('order_id',$get_order_detail->order_id)->delete();
                    }
                }
                //Checking if applied coupon is related to Whole Cart

                //Checking if Atleast 1 Main product exist otherwise making empty cart
                $main_product_count = OrderDetail::where([['order_id','=',$get_order_detail->order_id],['product_id','!=',0]])->count();
                if( $main_product_count == 0 ) {
                    OrderDetail::where(['order_id'=>$get_order_detail->order_id])->delete();
                }

                //Checking if NO product exist then delete main ORDER
                $count_order_dtl = OrderDetail::where(['order_id'=>$get_order_detail->order_id])->count();
                if( $count_order_dtl == 0 ) {
                    //Delete if any coupon is applied
                    AppliedCoupon::where('order_id',$get_order_detail->order_id)->delete();

                    $order = Order::find($get_order_detail->order_id);
                    $order->delete();
                }else{
                    $get_order_detail->delete();
                }

                
            }
        }
        return redirect()->route('cart');
    }


    /* Coupon Apply on Form Submit */
    public function ApplyCoupon( Request $request ) {
        if($request->isMethod('POST')){

           // if( Auth::user() ) {

                $orderid    = isset($request->orderid)?$request->orderid:'';
                $couponcode = isset($request->couponcode)?$request->couponcode:'';

                $applied_coupon_exist = AppliedCoupon::where('order_id',$orderid)->count();

                if( $applied_coupon_exist == 0 ) {
                    if( $couponcode != '' ) {
                        $datetime = @$this->get_date_time();

                        $now = isset($datetime)?$datetime:date('Y-m-d H:i:s');

                        $conditions[] = ['is_block','N'];
                        $conditions[] = ['coupon_code',$couponcode];
                        $conditions[] = ['start_date', '<=', $now];
                        $conditions[] = ['end_date', '>=', $now];

                        $coupon_data = Coupon::where($conditions)->first();
                        //dd( $coupon_data );

                        if( $coupon_data != null ) {

                            $get_cart_details = @$this->get_cart_item_details();

                            //Is related to cart (Minimum cart value) START
                            if( $coupon_data->related_to == 'C' ) {

                                //If Coupon code is not for promotion
                                if($coupon_data->promotion == 'N'){
                                    //If cart value is greater than Minimum cart value START
                                    if( $get_cart_details['total_cart_price'] > $coupon_data->minimum_cart_amount ) {

                                        //Coupon already used for this order or not START
                                        $applied_coupon_count = AppliedCoupon::where([['order_id',$orderid],['coupon_id',$coupon_data->id]])->count();
                                        if( $applied_coupon_count == 0 ) {
                                            //Coupon insertion into Applied Coupon table
                                            $applied_coupon['order_id']    = $orderid;
                                            $applied_coupon['coupon_id']   = $coupon_data->id;
                                            $applied_coupon['amount']      = $coupon_data->amount;
                                            $applied_coupon['coupon_code'] = $coupon_data->coupon_code;
                                            $applied_coupon['coupon_type'] = $coupon_data->type;
                                            $applied_coupon['start_date']  = $coupon_data->start_date;
                                            $applied_coupon['end_date']    = $coupon_data->end_date;

                                            AppliedCoupon::create($applied_coupon);

                                            //Setting coupon id in the SESSION
                                            Session::put('couponid',$coupon_data->id);

                                            $request->session()->flash('alert-success', 'Coupon applied successfully.');
                                            return redirect()->route('cart')->with(['couponcode'=>$couponcode]);
                                        }
                                        else{
                                            $request->session()->flash('alert-danger', 'This coupon is already being used for this order.');
                                            return redirect()->route('cart');
                                        }
                                        //Coupon already used for this order or not END
                                    }else{
                                        $min_coupon_amount = 0;
                                        $min_coupon_amount = $this->get_currency($coupon_data->minimum_cart_amount, ['need_currency' => true, 'number_format' => 2]);

                                        $request->session()->flash('alert-danger', 'To use this coupon minimum cart value should be greater than '.$min_coupon_amount);

                                        //$request->session()->flash('alert-danger', 'To use this coupon minimum cart value should be greater than '.Currency::default($coupon_data->minimum_cart_amount, ['need_currency' => true, 'number_format' => 2]));

                                        return redirect()->route('cart');
                                    }
                                    //If cart value is greater than Minimum cart value END
                                } else if($coupon_data->promotion == 'Y'){
                                    if( Auth::user() ) {
                                        $get_count = Promotional::where([['coupon_code','=',$coupon_data->id],['email','=',Auth::user()->email],['is_used','=','N']])->count();
                                        if( $get_count != 0 ) {
                                            
                                            if( $get_cart_details['total_cart_price'] > $coupon_data->minimum_cart_amount ) {

                                                //Coupon already used for this order or not START
                                                $applied_coupon_count = AppliedCoupon::where([['order_id',$orderid],['coupon_id',$coupon_data->id]])->count();
                                                if( $applied_coupon_count == 0 ) {
                                                    //Coupon insertion into Applied Coupon table
                                                    $applied_coupon['order_id']    = $orderid;
                                                    $applied_coupon['coupon_id']   = $coupon_data->id;
                                                    $applied_coupon['amount']      = $coupon_data->amount;
                                                    $applied_coupon['coupon_code'] = $coupon_data->coupon_code;
                                                    $applied_coupon['coupon_type'] = $coupon_data->type;
                                                    $applied_coupon['start_date']  = $coupon_data->start_date;
                                                    $applied_coupon['end_date']    = $coupon_data->end_date;
        
                                                    AppliedCoupon::create($applied_coupon);
        
                                                    //Setting coupon id in the SESSION
                                                    Session::put('couponid',$coupon_data->id);
        
                                                    $request->session()->flash('alert-success', 'Coupon applied successfully.');
                                                    return redirect()->route('cart')->with(['couponcode'=>$couponcode]);
                                                }
                                                else{
                                                    $request->session()->flash('alert-danger', 'This coupon is already being used for this order.');
                                                    return redirect()->route('cart');
                                                }
                                                //Coupon already used for this order or not END
                                            }else{
                                                $min_coupon_amount = 0;
                                                $min_coupon_amount = $this->get_currency($coupon_data->minimum_cart_amount, ['need_currency' => true, 'number_format' => 2]);
        
                                                $request->session()->flash('alert-danger', 'To use this coupon minimum cart value should be greater than '.$min_coupon_amount);
                                                return redirect()->route('cart');
                                            }

                                        }else{
                                            $request->session()->flash('alert-danger', 'This coupon is invalid or already used or for selected users only.');
                                            return redirect()->route('cart');
                                        }
                                    }else{
                                        $request->session()->flash('alert-danger', 'This coupon is valid for selected users only. Please use after login  ');
                                        return redirect()->route('cart');
                                    }
                                }


                            }
                            //Is related to cart (Minimum cart value) END
                            //Is related to User START
                            // else if( $coupon_data->related_to == 'U' ) {
                            //     if( Auth::user() ) {
                            //         $get_count = CouponUser::where([['coupon_id','=',$coupon_data->id],['user_id','=',Auth::user()->id],['is_used','=','N']])->count();
                            //         if( $get_count != 0 ) {
                            //             //Coupon already used for this order or not START
                            //             $applied_coupon_count = AppliedCoupon::where([['order_id',$orderid],['coupon_id',$coupon_data->id]])->count();
                            //             if( $applied_coupon_count == 0 ) {
                            //                 //Coupon insertion into Applied Coupon table
                            //                 $applied_coupon['order_id']    = $orderid;
                            //                 $applied_coupon['coupon_id']   = $coupon_data->id;
                            //                 $applied_coupon['amount']      = $coupon_data->amount;
                            //                 $applied_coupon['coupon_code'] = $coupon_data->coupon_code;
                            //                 $applied_coupon['coupon_type'] = $coupon_data->type;
                            //                 $applied_coupon['start_date']  = $coupon_data->start_date;
                            //                 $applied_coupon['end_date']    = $coupon_data->end_date;

                            //                 AppliedCoupon::create($applied_coupon);

                            //                 //Setting coupon id in the SESSION
                            //                 Session::put('couponid',$coupon_data->id);

                            //                 $request->session()->flash('alert-success', 'Coupon applied successfully.');
                            //                 return redirect()->route('site.cart')->with(['couponcode'=>$couponcode]);
                            //             }
                            //             else{
                            //                 $request->session()->flash('alert-danger', 'This coupon is already being used for this order.');
                            //                 return redirect()->route('site.cart');
                            //             }
                            //             //Coupon already used for this order or not END
                            //         }else{
                            //             $request->session()->flash('alert-danger', 'This coupon is invalid or already used or for selected users only.');
                            //             return redirect()->route('site.cart');
                            //         }
                            //     }else{
                            //         $request->session()->flash('alert-danger', 'This coupon is valid for selected users only.');
                            //         return redirect()->route('site.cart');
                            //     }
                            // }
                            //Is related to User END
                            //Is related to Occasion START
                            // else if( $coupon_data->related_to == 'O' ) {
                            //     //dd( $coupon_data );
                            //     $product_ids = []; $occasion_ids = [];
                            //     if( !empty($get_cart_details['item_dtl']) ) {
                            //         foreach( $get_cart_details['item_dtl'] as $key_item => $val_item ) {
                            //             if( $val_item['product_id'] != 0 ) {
                            //                 $product_ids[] = $val_item['product_id'];
                            //                 $occasion_ids[]= $val_item['occasion_id'];
                            //             }
                            //         }
                            //     }
                            //     //Checking occasion related product exist in cart or not
                            //     $count = Product::where('occasions_id','!=',null)->whereIn('id',$product_ids)->count();
                            //     if( $count > 0 ) {
                            //         //Collecting COUPON related OCCASION ids
                            //         $coupon_occasion_ids = [];
                            //         if( $coupon_data->CouponOccation != null ) {
                            //             foreach ($coupon_data->CouponOccation as $key => $val) {
                            //                 if( $val->occation_id != 0) {
                            //                     $coupon_occasion_ids[] = $val->occation_id;
                            //                 }
                            //             }
                            //         }
                            //         //dd($coupon_occasion_ids);

                            //         if( !empty( $occasion_ids ) && !empty( $coupon_occasion_ids ) ) {
                            //             //Checking cart product occasion ids matched with coupon related occasion ids
                            //             $result = array_intersect($occasion_ids, $coupon_occasion_ids);
                            //             if( !empty( $result ) ) {   //Processing for coupon apply
                            //                 //Coupon already used for this order or not START
                            //                 $applied_coupon_count = AppliedCoupon::where([['order_id',$orderid],['coupon_id',$coupon_data->id]])->count();
                            //                 if( $applied_coupon_count == 0 ) {
                            //                     //Coupon insertion into Applied Coupon table
                            //                     $applied_coupon['order_id']    = $orderid;
                            //                     $applied_coupon['coupon_id']   = $coupon_data->id;
                            //                     $applied_coupon['amount']      = $coupon_data->amount;
                            //                     $applied_coupon['applied_for'] = 'OC';
                            //                     $applied_coupon['coupon_code'] = $coupon_data->coupon_code;
                            //                     $applied_coupon['coupon_type'] = $coupon_data->type;
                            //                     $applied_coupon['start_date']  = $coupon_data->start_date;
                            //                     $applied_coupon['end_date']    = $coupon_data->end_date;

                            //                     AppliedCoupon::create($applied_coupon);

                            //                     //Setting coupon id in the SESSION
                            //                     Session::put('couponid',$coupon_data->id);

                            //                     $request->session()->flash('alert-success', 'Coupon applied successfully.');
                            //                     return redirect()->route('site.cart')->with(['couponcode'=>$couponcode]);
                            //                 }
                            //                 else{
                            //                     $request->session()->flash('alert-danger', 'This coupon is already being used for this order.');
                            //                     return redirect()->route('site.cart');
                            //                 }
                            //                 //Coupon already used for this order or not END
                            //             }else{
                            //                 $request->session()->flash('alert-danger', 'This coupon is for some selected occasions.');
                            //                 return redirect()->route('site.cart');
                            //             }
                            //         }else{
                            //             $request->session()->flash('alert-danger', 'Something went wrong! Please try again later.');
                            //             return redirect()->route('site.cart');
                            //         }
                            //     }else{
                            //         $request->session()->flash('alert-danger', 'No occasion related product exist in cart.');
                            //         return redirect()->route('site.cart');
                            //     }
                            // }
                            //Is related to Occasion END
                        }else{
                            $request->session()->flash('alert-danger', 'This coupon is invalid or expired.');
                            return redirect()->route('site.cart');
                        }
                    }else{
                        $request->session()->flash('alert-danger', 'Something went wrong! Please try again later.');
                        return redirect()->route('site.cart');
                    }
                }
                else{
                    $request->session()->flash('alert-danger', 'A coupon is already applied, please remove and then try.');
                    return redirect()->route('site.cart');
                }
            //}
            /* else{
                $request->session()->flash('alert-danger', 'Please log in to apply coupon.');
                return redirect()->route('site.cart');
            } */
        }
    }

    //Remove applied coupon
    public function removeAppliedCoupon( $id = null, $order_id = null, Request $request ) {
        $id       = isset($id)?base64_decode($id):0;
        $order_id = isset($order_id)?base64_decode($order_id):0;

        if( $id != 0 && $order_id != 0 ) {
            AppliedCoupon::where([['id',$id],['order_id',$order_id]])->delete();

            //Delete Session coupon id & coupon amount is available
            if( Session::has('couponid') ) {
                Session::forget('couponid');
            }
            if( Session::has('coupon_discount_amount') ) {
                Session::forget('coupon_discount_amount');
            }

            $request->session()->flash('alert-success', 'Coupon removed successfully.');
            return redirect()->route('site.cart');
        }else{
            $request->session()->flash('alert-danger','Something went wrong! Please try again later.');
            return redirect()->route('site.cart');
        }
    }

    public function giftAddonAddToCart( Request $request ) {
        if( $request->isMethod('POST') ){
            if( $request->session()->has('Cart.order_id') ) {
                $order_id = $request->session()->get('Cart.order_id');

                //Getting last inserted order details id (main product)
                $order_details_id_giftaddon = 0;
                if( $request->session()->has('order_details_id_giftaddon') ) {
                    $order_details_id_giftaddon = Session::get('order_details_id_giftaddon');
                }

                if( isset($order_id) && isset($request->giftaddon_ids) ) {
                    $giftaddon_ids  = $request->giftaddon_ids;

                    $deli_country_id    = Session::get('Delivery.delivery_country_id');
                    $deli_city_id       = Session::get('Delivery.delivery_city_id');
                    $deli_city_name     = Session::get('Delivery.delivery_city_name');
                    $deli_date          = Session::get('Delivery.product_delivery_date');
                    $shipmethod_id      = Session::get('Delivery.shippingmethod_id');
                    $shipmethod_name    = Session::get('Delivery.shippingmethod_name');                    

                    $delivery_country_id= isset($deli_country_id)?$deli_country_id:110;
                    $delivery_city_id   = isset($deli_city_id)?$deli_city_id:0;
                    $delivery_city_name = isset($deli_city_name)?$deli_city_name:'Kolkata';
                    $delivery_date      = isset($deli_date)?$deli_date:NULL;
                    $shippingmethod_id  = isset($shipmethod_id)?$shipmethod_id:0;
                    $shippingmethod_name= isset($shipmethod_name)?$shipmethod_name:NULL;

                    if( strpos($giftaddon_ids, ',') !== false ) {
                        $gift_array  = explode(',', $giftaddon_ids);
                        asort($gift_array);
                        foreach ( $gift_array as $key_gift => $val_gift ) {
                            $check_order_detail = OrderDetail::where([
                                                'order_id'                   => $order_id,
                                                'gift_addon_id'              => $val_gift,
                                                'order_details_id_giftaddon' => $order_details_id_giftaddon,
                                                'delivery_country_id'        => $delivery_country_id,
                                                'delivery_city_id'           => $delivery_city_id,
                                                'delivery_date'              => $delivery_date,
                                                'shippingmethod_id'          => $shippingmethod_id
                                            ])->first();
                            if( $check_order_detail != null && $check_order_detail != '' ) {
                                //Updating gift addon
                                $updated_qty                       = ($check_order_detail->qty + 1);
                                $total_price                       = ($check_order_detail->unit_price * $updated_qty);
                                $check_order_detail['qty']         = $updated_qty;
                                $check_order_detail['total_price'] = $total_price;
                                $ordetail_submit                   = $check_order_detail->save();
                            }else{
                                $get_gift_price = Product::where('id',$val_gift)->pluck('price')->first();
                                //Inserting gift addon
                                $orderDtlArray['order_id']                   = $order_id;
                                $orderDtlArray['order_details_id_giftaddon'] = $order_details_id_giftaddon;
                                $orderDtlArray['product_id']                 = 0;
                                $orderDtlArray['product_attr_id']            = 0;
                                $orderDtlArray['gift_addon_id']              = $val_gift;
                                $orderDtlArray['qty']                        = 1;
                                $orderDtlArray['original_price']             = $get_gift_price;
                                $orderDtlArray['unit_price']                 = $get_gift_price;
                                $orderDtlArray['total_price']                = $get_gift_price;

                                $orderDtlArray['delivery_country_id']        = $delivery_country_id;
                                $orderDtlArray['delivery_city_id']           = $delivery_city_id;
                                $orderDtlArray['delivery_city_name']         = $delivery_city_name;
                                $orderDtlArray['delivery_date']              = $delivery_date;
                                $orderDtlArray['shippingmethod_id']          = $shippingmethod_id;
                                $orderDtlArray['shippingmethod_name']        = $shippingmethod_name;
                                $orderDtlArray['ship_price']                 = 0;

                                $ordetail_submit = OrderDetail::create($orderDtlArray);
                            }
                        }
                    }else{
                        $check_order_detail = OrderDetail::where([
                                                'order_id'                   => $order_id,
                                                'gift_addon_id'              => $giftaddon_ids,
                                                'order_details_id_giftaddon' => $order_details_id_giftaddon,
                                                'delivery_country_id'        => $delivery_country_id,
                                                'delivery_city_id'           => $delivery_city_id,
                                                'delivery_date'              => $delivery_date,
                                                'shippingmethod_id'          => $shippingmethod_id
                                            ])->first();

                        if( $check_order_detail != null && $check_order_detail != '' ) {
                            //Updating gift addon
                            $updated_qty                       = ($check_order_detail->qty + 1);
                            $total_price                       = ($check_order_detail->unit_price * $updated_qty);
                            $check_order_detail['qty']         = $updated_qty;
                            $check_order_detail['total_price'] = $total_price;
                            $ordetail_submit                   = $check_order_detail->save();
                        }else{
                            $get_gift_price =Product::where('id',$giftaddon_ids)->pluck('price')->first();
                            //Inserting gift addon
                            $orderDtlArray['order_id']                   = $order_id;
                            $orderDtlArray['order_details_id_giftaddon'] = $order_details_id_giftaddon;
                            $orderDtlArray['product_id']                 = 0;
                            $orderDtlArray['product_attr_id']            = 0;
                            $orderDtlArray['gift_addon_id']              = $giftaddon_ids;
                            $orderDtlArray['qty']                        = 1;
                            $orderDtlArray['original_price']             = $get_gift_price;
                            $orderDtlArray['unit_price']                 = $get_gift_price;
                            $orderDtlArray['total_price']                = $get_gift_price;

                            $orderDtlArray['delivery_country_id']        = $delivery_country_id;
                            $orderDtlArray['delivery_city_id']           = $delivery_city_id;
                            $orderDtlArray['delivery_city_name']         = $delivery_city_name;
                            $orderDtlArray['delivery_date']              = $delivery_date;
                            $orderDtlArray['shippingmethod_id']          = $shippingmethod_id;
                            $orderDtlArray['shippingmethod_name']        = $shippingmethod_name;
                            $orderDtlArray['ship_price']                 = 0;

                            $ordetail_submit = OrderDetail::create($orderDtlArray);
                        }
                    }
                    $header_cart_count = 0;
                    $header_cart_count = OrderDetail::where([['order_id',$order_id],['order_details_id',0]])->count();
                    return response()->json(['success'=>'Gift addon successfully added to cart.', 'header_cart_count'=>$header_cart_count]);
                    exit;
                }else{
                    return response()->json(['error'=>'An error occurred during processing. Please try again by reloading the page.']);
                    exit;
                }
            }else{
                return response()->json(['error'=>'An error occurred during processing. Please try again by reloading the page.']);
                exit;
            }
            exit;
        }
    }

}