<?php
namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Cms;
use App\Models\Testimonial;
use App\Models\HomePageFeatureCategory;
use App\Models\Currency;
use App\Models\Order;
use App\Models\User;
use App\Models\City;
use App\Mail\EmailAdminBulkOrders;
use DB;


class HomeController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the front page
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        $featured_category = HomePageFeatureCategory::where(['is_block' => 'N'])->orderBy('sort','asc')->get();
        
        //Testimonial
        $testimonials = Testimonial::where('is_block','N')->select('name', 'place', 'content', 'send_place')->orderBy('id','ASC')->get();

        //Bottom content
        $home_bottom_content = Cms::where('id',1)->select('title','content','meta_title','meta_keyword','meta_description')->first();

        
        return view('site.home')->with(['featured_category' => $featured_category, 'testimonials'=>$testimonials, 'home_bottom_content'=>$home_bottom_content]);
    }

    public function set_currency(Request $request){
        //dd($request);
        $currency = Currency::where(['currency' => $request->currency])->first();
        if($currency === null){
            $request->session()->put('currency', 'euro');
            $request->session()->put('currency_html_code', '&#8364;');
        }else{
            $request->session()->put('currency', $currency->currency);
            $request->session()->put('currency_html_code', $currency->html_code);
        }
        
        if ($request->session()->has('currency')) {
            $currencyN = $request->session()->get('currency');
            $currencyCodeN = $request->session()->get('currency_html_code');
        }
        return json_encode(['type' => 'json', 'status' => 'success', 'currency' => $currencyN, 'currency_html_code' => $currencyCodeN]);
    }

    public function set_currency_order_summary(Request $request){
        if( $request->isMethod('POST') ) {
            $currency = Currency::where(['currency' => $request->currency_data])->first();
            if($currency === null){
                $request->session()->put('currency', 'USD');
                $request->session()->put('currency_html_code', '&&#36;');
            }else{
                $request->session()->put('currency', $currency->currency);
                $request->session()->put('currency_html_code', $currency->html_code);
            }
            return $request->currency_data;
        }
    }

    public function termsAndConditions(Request $request) {
        $page_details = Cms::where([
                                    'is_block'  => 'N',
                                    'id'        => 2
                                ])->first();

        if($page_details != null) {
            return view('site.innerpage')->with(['page_details'=>$page_details]);
        }else{
            return abort(404);
        }
    }

    public function aboutUs( Request $request) {
        //dd($request);
        $page_details = Cms::where([
                                    'is_block'  => 'N',
                                    'id'        => 5
                                ])->first();

        if($page_details != null) {
            return view('site.innerpage')->with(['page_details'=>$page_details]);
        }else{
            return abort(404);
        }
    }

    public function privacyPolicy( Request $request) {
        $page_details = Cms::where([
                                    'is_block'  => 'N',
                                    'id'        => 3
                                ])->first();
        if($page_details != null) {
            return view('site.innerpage')->with(['page_details'=>$page_details]);
        }else{
            return abort(404);
        }
    }

    public function refundPolicy($param = null, Request $request) {
        $page_details = Cms::where([
                                    'is_block'  => 'N',
                                    'id'        => 4
                                ])->first();

        if($page_details != null) {
            return view('site.innerpage')->with(['page_details'=>$page_details]);
        }else{
            return abort(404);
        }
    }

    public function shippingPolicy($param = null, Request $request) {
        $page_details = Cms::where([
                                    'is_block'  => 'N',
                                    'id'        => 11
                                ])->first();

        if($page_details != null) {
            return view('site.innerpage')->with(['page_details'=>$page_details]);
        }else{
            return abort(404);
        }
    }

    public function faq( Request $request) {
        $page_details = Cms::where([
                                    'is_block'  => 'N',
                                    'id'        => 6
                                ])->first();

        if($page_details != null) {
            return view('site.innerpage')->with(['page_details'=>$page_details]);
        }else{
            return abort(404);
        }
    }

    public function cancellationPolicy($param = null, Request $request) {
        $page_details = Cms::where([
                                    'is_block'  => 'N',
                                    'id'        => 10
                                ])->first();

        if($page_details != null) {
            return view('site.innerpage')->with(['page_details'=>$page_details]);
        }else{
            return abort(404);
        }
    }

    public function disclaimer($param = null, Request $request) {
        $page_details = Cms::where([
                                    'is_block'  => 'N',
                                    'id'        => 10
                                ])->first();

        if($page_details != null) {
            return view('site.innerpage')->with(['page_details'=>$page_details]);
        }else{
            return abort(404);
        }
    }

    public function deliveryLocations( Request $request) {
        $cities = City::where(['is_block'=>'N', 'check_other' => 'N'])->select('id','name','slug')->orderBy('name', 'ASC')->get();
        
        $page_details = Cms::where(['is_block'=>'N','slug'=>'delivery-locations'])->first();
        return view('site.deliverylocations')->with(['cities'=>$cities, 'page_details'=>$page_details]);
    }

    public function sitemap( Request $request) {
        
        $cities = City::where('is_block', 'N')->select('id','name','slug')->orderBy('name', 'ASC')->get();
        
        return view('site.sitemap')->with(['cities'=>$cities]);
    }

    public function payment($param = null, Request $request) {
        $page_details = Cms::where([
                                    'is_block'  => 'N',
                                    'id'        => 18
                                ])->first();
        if($page_details != null) {
            return view('site.innerpage')->with(['page_details'=>$page_details]);
        }else{
            return abort(404);
        }
    }

    public function bulkOrders(Request $request){
        if($request->isMethod('POST')) {
            
            $request->validate([
                'mobile' => 'required|numeric'
            ]);

            $details = [];
            $details['name']    = $request->name;
            $details['email']   = $request->email;
            $details['mobile']  = $request->mobile;
            $details['message'] = $request->message;
            
            Mail::to(config('global.admin_email_id'))->queue(new EmailAdminBulkOrders($details));
            $request->session()->flash('alert-success', 'Thank you for contacting with us. We will get back to you soon.');
            return redirect()->back();
        }
        $page_details = Cms::where([
                                    'is_block'  => 'N',
                                    'id'        => 8
                                ])->first();
        return view('site.bulk_orders')->with(['page_details'=>$page_details]);
    }

    public function orderStatus(Request $request) {
        if( $request->isMethod('POST') ) {
            $toid   = isset($request->order_id)?$request->order_id:'';
            $temail = isset($request->emailid)?$request->emailid:'';
            return redirect()->route('order-status-details', ['order_id'=>$toid,'email'=>$temail]);
        }
        return view('site.order_status');
    }

    public function orderStatusDetails( Request $request )
    {
        if($request != null){
            $order_id    = isset($request->order_id)?$request->order_id:'';
            $email          = isset($request->email)?$request->email:'';
            $order_dtl  = [];
            
            if( $order_id != '' && $email != '' ) {
                $order_dtl = Order::where([['unique_order_id', $order_id]])->first();
                if(!(isset($order_dtl->id) && $order_dtl->id>0)){
                    $request->session()->flash('alert-danger', 'Sorry! You have inserted wrong order-Id!');
                    return redirect()->route('order-status');
                }
                $user_dtl = User::where('id', $order_dtl->user_id)->first();
                if(!(isset($user_dtl->email) && $user_dtl->email==$email)){
                    $request->session()->flash('alert-danger', 'Please enter your registered email-Id!');
                    return redirect()->route('order-status');
                }
            $total_cart_price = 0.00; $occasion_product_price = 0;
            
            $cart_array = array();
            if( $order_dtl != null ) {
                if( isset($order_dtl->order_detail_admin) && count($order_dtl->order_detail_admin) > 0 ) {
                    //dd($order_dtl->order_detail_admin);
                    $i = 0;
                    foreach( $order_dtl->order_detail_admin as $productLists ) {

                        $extra_addon_array = []; $extra_addon_ids_array = [];
                        //If Extra Addons NOT exist
                        if( $productLists->order_details_id == 0 ) {
                            //If Gift Addons NOT exist
                            if( $productLists->gift_addon_id == 0 ) {

                                $product_image_name = '';
                                if( isset($productLists->product->default_product_image) && $productLists->product->default_product_image != null ) {
                                    $product_image_name = $productLists->product->default_product_image['name'];
                                }
                                $cart_array[$i]['order_detail_id']  = $productLists->id;
                                $cart_array[$i]['category_id']      = $productLists->category_id;
                                $cart_array[$i]['occasion_id']      = $productLists->occasion_id;
                                $cart_array[$i]['product_id']       = $productLists->product->id;
                                $cart_array[$i]['product_attr_id']  = $productLists->product_attr_id;
                                $cart_array[$i]['gift_addon_id']    = $productLists->gift_addon_id;
                                $cart_array[$i]['product_name']     = $productLists->product->product_name;
                                $cart_array[$i]['image']            = $product_image_name;
                                $cart_array[$i]['qty']              = $productLists->qty;

                                $cart_array[$i]['delivery_pincode'] = $productLists->delivery_pincode;
                                $cart_array[$i]['delivery_country'] = $order_dtl->delivery_country;
                                $cart_array[$i]['hold_reason']      = $order_dtl->hold_reason;
                                $cart_array[$i]['delivery_city']    = $productLists->delivery_city_name;
                                $cart_array[$i]['delivery_pincode'] = $productLists->delivery_pincode;
                                $cart_array[$i]['delivery_date']    = $productLists->delivery_date;
                                $cart_array[$i]['shippingmethod_id']= $productLists->shippingmethod_id;
                                $cart_array[$i]['shippingmethod_name']= $productLists->shippingmethod_name;
                                $cart_array[$i]['ship_price']       = $productLists->ship_price;
                                $cart_array[$i]['delivery_time_id'] = $productLists->delivery_time_id;
                                $cart_array[$i]['deliverytime']     = $productLists->deliverytime;
                                $cart_array[$i]['order_details_id_giftaddon'] = $productLists->order_details_id_giftaddon;
                                $cart_array[$i]['order_delivery_status'] = $order_dtl->order_delivery_status;

                                //If product attribute exist
                                //if(isset($productLists->product->order_product_all_attributes) && $productLists->product->order_product_all_attributes != null){
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

                                //If extra addon exists
                                if( isset($productLists->order_related_detail) && ($productLists->order_related_detail != null) ) {

                                    if( $productLists->order_related_detail->product_extras_addon_id != '' ) {
                                        if( strpos($productLists->order_related_detail->product_extras_addon_id, ',') !== false ) { //For multiple extra addon
                                            $extra_array  = explode(',', $productLists->order_related_detail->product_extras_addon_id);
                                            asort($extra_array);
                                            foreach ( $extra_array as $key_extra => $val_extra ) {
                                                $get_pro_extra = ProductExtra::where('id',$val_extra)->first();

                                                $extra_addon_array[]     = $get_pro_extra['title'];
                                                $extra_addon_ids_array[] = $get_pro_extra['id'];
                                            }
                                        }else{  //For single extra addon
                                            $get_pro_extra = ProductExtra::where('id',$productLists->order_related_detail->product_extras_addon_id)->first();

                                                $extra_addon_array[]     = $get_pro_extra['title'];
                                                $extra_addon_ids_array[] = $get_pro_extra['id'];
                                        }
                                    }
                                    asort($extra_addon_ids_array);
                                    $cart_array[$i]['product_extra_addon_name'] = $extra_addon_array;
                                    $cart_array[$i]['product_extra_addon_ids']  = $extra_addon_ids_array;

                                    $cart_array[$i]['product_unit_price']       = $productLists->unit_price;

                                    $cart_array[$i]['unit_price']       = $productLists->unit_price + $productLists->order_related_detail->unit_price;
                                    $cart_array[$i]['total_price']      = $productLists->total_price + $productLists->order_related_detail->total_price;
                                    $total_cart_price                   = $total_cart_price + $productLists->total_price + $productLists->order_related_detail->total_price;

                                    //For occasion related product (Total Price)//
                                    if( $productLists->product->occasions_id != null ) {
                                        $occasion_product_price = $occasion_product_price + $productLists->unit_price + $productLists->order_related_detail->total_price;
                                    }
                                    //For occasion related product (Total Price)//
                                }
                                else{   //If NOT extra addon exists
                                    $cart_array[$i]['product_extra_addon_name'] = $extra_addon_array;
                                    $cart_array[$i]['product_extra_addon_ids']  = $extra_addon_ids_array;
                                    $cart_array[$i]['product_unit_price']       = $productLists->unit_price;
                                    $cart_array[$i]['unit_price']               = $productLists->unit_price;
                                    $cart_array[$i]['total_price']              = $productLists->total_price;
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
                                //$giftaddon_image_name = $productLists->gift_addon_detail->image;
                                if( isset($productLists->extraaddon_detail->default_product_image) && $productLists->extraaddon_detail->default_product_image != null ) {
                                    $giftaddon_image_name = $productLists->extraaddon_detail->default_product_image->name;
                                }
                                
                                $cart_array[$i]['order_detail_id']  = $productLists->id;
                                $cart_array[$i]['category_id']      = $productLists->category_id;
                                $cart_array[$i]['occasion_id']      = $productLists->occasion_id;
                                $cart_array[$i]['product_id']       = $productLists->product_id;
                                $cart_array[$i]['product_attr_id']  = $productLists->product_attr_id;
                                $cart_array[$i]['gift_addon_id']    = $productLists->gift_addon_id;
                                //$cart_array[$i]['product_name']     = $productLists->gift_addon_detail->title;
                                $cart_array[$i]['product_name']     = $productLists->extraaddon_detail->product_name;
                                $cart_array[$i]['image']            = $giftaddon_image_name;
                                $cart_array[$i]['qty']              = $productLists->qty;

                                $cart_array[$i]['delivery_pincode'] = $productLists->delivery_pincode;
                                $cart_array[$i]['delivery_country'] = $order_dtl->delivery_country;
                                $cart_array[$i]['hold_reason']      = $order_dtl->hold_reason;
                                $cart_array[$i]['delivery_city']    = $productLists->delivery_city_name;
                                $cart_array[$i]['delivery_date']    = $productLists->delivery_date;
                                $cart_array[$i]['shippingmethod_id']= $productLists->shippingmethod_id;
                                $cart_array[$i]['shippingmethod_name']= $productLists->shippingmethod_name;
                                $cart_array[$i]['ship_price']       = $productLists->ship_price;
                                $cart_array[$i]['delivery_time_id'] = $productLists->delivery_time_id;
                                $cart_array[$i]['deliverytime']     = $productLists->deliverytime;
                                $cart_array[$i]['order_details_id_giftaddon']     = $productLists->order_details_id_giftaddon;
                                $cart_array[$i]['order_delivery_status'] = $order_dtl->order_delivery_status;

                                //If product attribute exist
                                if(isset($productLists->product->order_product_attribute) && $productLists->product->order_product_attribute['title'] != null){
                                    $cart_array[$i]['attribute_name']=$productLists->product->order_product_attribute['title'];
                                }
                                else{
                                    $cart_array[$i]['attribute_name'] = '';
                                }

                                $cart_array[$i]['product_extra_addon_name'] = $extra_addon_array;
                                $cart_array[$i]['product_extra_addon_ids']  = $extra_addon_ids_array;

                                $cart_array[$i]['product_unit_price']= $productLists->unit_price;
                                $cart_array[$i]['unit_price']       = $productLists->unit_price;
                                $cart_array[$i]['total_price']      = $productLists->total_price;
                                $total_cart_price                   = $total_cart_price+$productLists->total_price;
                            }
                        }   //If Extra Addons NOT exist condition end here
                        $i++;
                        unset($extra_addon_array); unset($extra_addon_ids_array);
                    }
                }
                //dd($cart_array);
            }
            //dd($cart_array);

            $addon_gift_array = [];
            if(count($cart_array)>0){
                foreach ($cart_array as $key => $cart_product) {
                    if($cart_product['gift_addon_id'] >0){
                        unset($cart_array[$key]);
                        $addon_gift_array[] = $cart_product;
                    }
                }
            }
            
        }
        return view('site.order_status_details', ['order_dtl' => $order_dtl, 'cart_array' => $cart_array, 'addon_gift_array' => $addon_gift_array,'order_id' => $order_id, 'email' => $email]);  
            
        }
    }

    
}