<?php
namespace App\Http;
use DB;
use Auth;
use DateTime;
use Session;
use App\Models\Category;
use App\Models\User;
use App\Models\Country;
use App\Models\City;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductRating;
use App\Models\ProductImage;
use App\Models\Currency;
use App\Models\AppliedCoupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Setting;
use App\Models\ProductAttribute;

class Helper{
    public static function get_meta($meta_data = [], $data = []){
        if(!isset($data['city_slug'])){
          $meta_keyword = str_replace('[country]', 'India', $meta_data['keyword']);
          $meta_keyword = str_replace('[city]', 'Kolkata', $meta_keyword);
          $meta_description = str_replace('[country]', 'India', $meta_data['description']);
          $meta_description = str_replace('[city]', 'Kolkata', $meta_description);
        }else{
          $meta_keyword = str_replace('[country]', $data['country_name'], $meta_data['keyword']);
          $meta_keyword = str_replace('[city]', $data['city_name'], $meta_keyword);
          $meta_description = str_replace('[country]', $data['country_name'], $meta_data['description']);
          $meta_description = str_replace('[city]', $data['city_name'], $meta_description);
        }
        return ['meta_keyword' => $meta_keyword, 'meta_description' => $meta_description];
    }

    public static function get_currency() {
        $home_currency = Currency::where('is_block','=','N')->orderBy('id','ASC')->get();
        return $home_currency;
    }

    public static function getHomepageProductsByCatId( $cat_id = null, $limit = 4 ) {
        $products = $product_ids = [];

        $cat_dtl = Category::where(['id' => $cat_id])->first();

        $products['product'] = Product::select('products.id', 'products.product_name', 'products.delivery_delay_days', 'products.price', 'products.slug', 'products.alt_key', 'products.has_attribute')
                                        ->join('home_page_products', 'home_page_products.product_id', '=', 'products.id')
                                        ->where(['products.is_block'=>'N', 'home_page_products.cat_id' => $cat_id])
                                        ->orderBy('home_page_products.sort','ASC')->take($limit)->get();
        
        $products['path'] = $cat_dtl->slug;
        return $products;
    }

    public static function getdefaultProductImage($product_id = null) {
        $defaultImg = ProductImage::where(['product_id' => $product_id, 'attr_id' => 0, 'default_image' => 'Y'])->select('name', 'thumb')->first();
        return $defaultImg;
    }

    public static function get_delaydays_by_cutTime($countryTimeNow, $cutTime) {
        //dd(date('m/d/Y h:m', $countryTimeNow));
        if ($countryTimeNow >= strtotime($cutTime)) {
            $dDays = 1;
        }else{
            $dDays = 0;
        }

        return $dDays;
        //return date('h:i:s:u', $countryTimeNow);
    }

    public static function getDeliveryDelayDays($delaydays = 0){
        $result = null;
        
        date_default_timezone_set('Europe/Berlin');
        $todayDateTime = date('Y-m-d H:i:s');

        $samedayCutTime = date('Y-m-d 11:00:00', strtotime("-1 day"));
        $otherCutTime = date('Y-m-d 15:00:00', strtotime("-1 day"));

        $nextSamedayCutTime = date('Y-m-d 11:00:00');
        $nextOtherCutTime = date('Y-m-d 15:00:00');

        if($delaydays == 0){
            if(strtotime($todayDateTime) >= strtotime($samedayCutTime) && strtotime($todayDateTime) < strtotime($nextSamedayCutTime)){
                $delaydays = $delaydays;
            }else{
                $delaydays = $delaydays + 1;
            }
        }else{
            if(strtotime($todayDateTime) >= strtotime($otherCutTime) && strtotime($todayDateTime) < strtotime($nextOtherCutTime))
            {
                $delaydays = $delaydays;
            }else{
                $delaydays = $delaydays + 1;
            }
        }
               
        // $otherCutTime = date('Y-m-d 13:00:00', strtotime("-1 day"));
        // $nextOtherCutTime = date('Y-m-d 13:00:00');

        // if(strtotime($todayDateTime) >= strtotime($otherCutTime) && strtotime($todayDateTime) < strtotime($nextOtherCutTime))
        // {
        //     $delaydays = $delaydays;
        // }else{
        //     $delaydays = $delaydays + 1;
        // }

        return $delaydays;
    }

    public static function get_earliest_delivery_date($delaydays = 0, $pid = 0){

        $result = null; 
        
        date_default_timezone_set('Europe/Berlin');
        $todayDateTime = date('Y-m-d H:i:s');

        $samedayCutTime = date('Y-m-d 11:00:00', strtotime("-1 day"));
        $otherCutTime = date('Y-m-d 15:00:00', strtotime("-1 day"));

        $nextSamedayCutTime = date('Y-m-d 11:00:00');
        $nextOtherCutTime = date('Y-m-d 15:00:00');

        if($delaydays == 0){
            if(strtotime($todayDateTime) >= strtotime($samedayCutTime) && strtotime($todayDateTime) < strtotime($nextSamedayCutTime)){
                $delaydays = $delaydays;
            }else{
                $delaydays = $delaydays + 1;
            }
        }else{
            if(strtotime($todayDateTime) >= strtotime($otherCutTime) && strtotime($todayDateTime) < strtotime($nextOtherCutTime))
            {
                $delaydays = $delaydays;
            }else{
                $delaydays = $delaydays + 1;
            }
        }
        

        if($delaydays > 1){
            $result = date('jS M, y', strtotime($todayDateTime. ' + '.$delaydays.' days'));
        } elseif($delaydays == 1){
            $result = "Tomorrow";
        }else {
            $result = "Today";
        }

        return $result;
    }

    public static function get_group_price($product_id = null) {
        $attrProduct = ProductAttribute::where(['product_id' => $product_id, 'is_block' => 'N'])->orderBy('sl_no', 'asc')->first();
        return $attrProduct;
    }

    public static function getRatingSchema($catid = null){
        $result = DB::table('schema_rating')->where(['cat_id'=>$catid])->first();
        return $result;
    }

    public static function getRatingSchemaCitywise($cityid = null){
        $result = DB::table('schema_rating_city')->where(['city_id'=>$cityid])->first();
        return $result;
    }

    public static function getSimilarProducts( $cat_slug = null, $productid = null, $limit = 4 ) {
        $products = $product_ids = [];

        if($cat_slug != ''){
            $cat_dtl = Category::where(['slug' => $cat_slug])->first();
            if($cat_dtl){
                $catID = $cat_dtl->id;
            }else{
                $cat_dtl = ProductCategory::where(['product_id' => $productid])->first();
                $catID = $cat_dtl->category_id;
            }
            
        }else{
            $cat_dtl = ProductCategory::where(['product_id' => $productid])->first();
            if($cat_dtl){
                $catID = $cat_dtl->category_id;
            }else{
                $catID = 1; //For Addon
            }
        }
        

        $product_ids = DB::table('products')
                            ->join('product_categories', 'product_categories.product_id', '=', 'products.id')
                            ->where(['products.is_block'=>'N', 'product_categories.category_id' => $catID])
                            ->pluck('products.id', 'products.id');

        //dd($products_ids);
        
        $products['product'] = Product::whereIn('id', $product_ids)->inRandomOrder()->take($limit)->get();
        
        return $products;
    }

    public static function getProductReviewNRating($pid = 0){

        $result = [];
        $product_rate = 0;
        //select(DB::raw('count(*) as review'), DB::raw('sum(rating) as totalrate'))
        $reviewData = ProductRating::where(['product_id' => $pid, 'is_block' => 'N'])->get();

        if(count($reviewData) > 0){
            foreach ($reviewData as $value) {
                $product_rate = $product_rate + $value->rating;
            }
        }

        $result['rating'] = $product_rate;
        $result['review'] = count($reviewData);

        return $result;
    }

    //Get Coupon details
    public static function get_coupon_details( $order_id = null ) {
        $get_data = [];
        if( $order_id != null ) {
            //$get_coupon_id = AppliedCoupon::where('order_id',$order_id)->pluck('coupon_id')->first();
            $get_coupon_id = AppliedCoupon::where('order_id',$order_id)->first();
            if( $get_coupon_id != null ) {
                /*$ip = '103.251.83.170';
                //$ip = '110.142.215.61';
                //$ip = $_SERVER['REMOTE_ADDR'];
                $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));

                if( isset($query) && $query['status'] == 'success' ) {
                    date_default_timezone_set($query['timezone']);
                    $datetime = date('Y-m-d H:i:s');
                }else{
                    $datetime = date('Y-m-d H:i:s');
                }*/

                date_default_timezone_set('Asia/Kolkata');
                $datetime = date('Y-m-d H:i:s');

                $now = isset($datetime)?$datetime:date('Y-m-d H:i:s');

                /*$coupon_data = Coupon::where([['id',$get_coupon_id],['is_block','N'],['start_date','<=',$now],['end_date','>=',$now]])->first();
                if( $coupon_data == null ) {
                    AppliedCoupon::where([['order_id',$order_id],['coupon_id',$get_coupon_id]])->delete();
                }
                */

                $coupon_data = AppliedCoupon::where([['id',$get_coupon_id->id],['start_date','<=',$now],['end_date','>=',$now]])->count();
                if( $coupon_data == 0 ) {
                    AppliedCoupon::where([['order_id',$order_id],['coupon_id',$get_coupon_id->id]])->delete();
                }
            }
            $get_data = AppliedCoupon::where('order_id',$order_id)->first();
        }
        return $get_data;
    }

    public static function siteSetting($purpose = null){
        $result = [];
        
        $result = Setting::where(['purpose'=>$purpose,'is_block'=>'N'])->orderBy('sort','asc')->get();
        
        return $result;
    }

    public static function flower_encrypt_decrypt( $string, $action = 'e' ) {
        $secret_key = 'c7tpe291z';
        $secret_iv = 'GfY7r512';

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

        if( $action == 'e' ) {
            $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        }
        else if( $action == 'd' ){
            $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }
        return $output;
    }

    public static function getCities( $country_id = null ) {
        if( $country_id != '' ) {
            $cities = City::where('country_id',$country_id)->where('is_block','N')->orderBy('name')->get();
            //dd($cities);
            return $cities;
        }
    }

    public static function contactCapcha($length = 6){
        $validCharacters = "123456789mnbvcxzasdfghjklpoiuytrewwq";
        $validCharNumber = strlen($validCharacters);
     
        $result = "";
     
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, $validCharNumber - 1);
            $result .= $validCharacters[$index];
        }

        return $result;
    }

    public static function getAllCountries(){
        $country=Country::orderBy('name')->get();
        return $country;
    }
}