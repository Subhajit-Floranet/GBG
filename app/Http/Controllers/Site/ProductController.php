<?php

namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSortbyOption;
use App\Models\ProductAttribute;
use App\Models\Addon;
use App\Models\AddonGroup;
use App\Models\ShippingDetail;
use App\Models\ProductRating;
use App\Models\City;
use Currency;
use DB;
use Session;

class ProductController extends Controller
{
	public function index( $catgory_slug = null, $product_slug = null, $token = null, $breadcrumb = [], Request $request, $data = [] ) {
        //die;
        //dd($catgory_slug);
        //dd($request);

        $selected_city_id = 0;
        // if(Session::has('selected_city_id')) {
        //     $selected_city_id = Session::get('selected_city_id');
        // }

		$product_details = Product::where(['slug' => $product_slug, 'is_block' => 'N', 'product_type' => 'M'])->first();

		if(!$product_details){
            abort('404');
            die();
        }

        $cityList = City::where(['is_block' => 'N'])->get();

        $all_extra_addons = [];
        $all_extra_addons = Product::where(['is_block'=>'N', 'addon'=>'Y', 'product_type'=>'A'])->get();

        // if(isset($product_details) && $product_details->extra_addon_group_id > 0) {
        //     $ExtraAddonGroupRelation = ProductExtraAddon::where('extra_addon_group_id', $product_details->extra_addon_group_id)->distinct('product_id')->orderBy('product_id','ASC')->pluck('product_id');
        //     if(isset($ExtraAddonGroupRelation) && count($ExtraAddonGroupRelation) > 0) {
        //         $all_extra_addons = Product::where(['is_block'=>'N', 'extra_addon'=>'Y', 'product_type'=>'A'])->whereIn('id',$ExtraAddonGroupRelation)->get();
        //     } else {
        //         $all_extra_addons = [];
        //     }
        // } else {
        //     $getFirstExtraAddonGroup = ProductExtraAddonGroup::where('is_block', 'N')->first();
        //     if($getFirstExtraAddonGroup){
        //         $ExtraAddonGroupRelation = ProductExtraAddon::where('extra_addon_group_id', $getFirstExtraAddonGroup->id)->distinct('product_id')->orderBy('product_id','ASC')->pluck('product_id');
        //         //dd($ExtraAddonGroupRelation);
        //         if(isset($ExtraAddonGroupRelation) && count($ExtraAddonGroupRelation) > 0) {
        //             $all_extra_addons = Product::where(['is_block'=>'N', 'extra_addon'=>'Y', 'product_type'=>'A'])->whereIn('id',$ExtraAddonGroupRelation)->get();
        //         }
        //     } else {
        //         $all_extra_addons = [];
        //     }
        // }

        

        //dd($all_extra_addons);

        //$shippingDtls = ShippingDetail::where(['is_block' => 'N'])->get();

        
        //dd($related_products);

        $relatedCombo = DB::table('product_related_combo')->where(['product_id'=>$product_details->id])->first();
        //dd($product_details->id);

        $productRating = ProductRating::where(['product_id'=>$product_details->id])->orderBy('created_at','DESC')->get();

		return view('site.product.product_details')->with(['token' => $token, 'catgory_slug' => $catgory_slug, 'product_slug' => $product_slug, 'product_details' => $product_details, 'breadcrumb' => $breadcrumb, 'data' => $data, 'all_extra_addons' => $all_extra_addons, ' selected_city_id' => $selected_city_id, 'cityList' => $cityList, 'request' => $request, 'relatedCombo' => $relatedCombo, 'productRating' => $productRating]);
	}

	//Get attribute details for product details page
    public function getAttributeDetails( Request $request ) {
        if ( $request->attr_id != '' ){
            $attr_detail = ProductAttribute::where('id',$request->attr_id)->first();
            $data = $attr_detail;
            //$data->price = 120;
            $data->price_converted = Currency::default($data->price);
            $data->price_converted_with_currency = Currency::default($data->price, ['need_currency' => 'Y']);
            $data->selected_currency = Currency::selected_currency();
            return $data;
        }else{
            return '';
        }
    }

    public function checkGiftAddon( Request $request ) {
        $hasgiftaddongroup = 'N';
        if ($request->isMethod('POST')) {
            $product_id = isset($request->product_id)?base64_decode($request->product_id):0;

            $productDtls = Product::where('id',$product_id)->first();

            if(isset($productDtls) && $productDtls->addon_group_id > 0) {
                $hasgiftaddongroup = 'Y';
            }else{
                $giftAddonGroup = AddonGroup::where(['is_block' => 'N'])->get();
                if(count($giftAddonGroup)>0){
                    $hasgiftaddongroup = 'Y';
                }else{
                    $hasgiftaddongroup = 'N';
                }
            }
            return response()->json(['hasgiftaddongroup'=>$hasgiftaddongroup]);
            
        }
    }

    //Get Gift Addons in product details page
    public function getGiftAddon( Request $request ) {
        $gift_addons = [];
        if ($request->isMethod('POST')) {
            $product_id = isset($request->product_id)?base64_decode($request->product_id):0;
            
            $productDtls = Product::where('id',$product_id)->first();
            
            if(isset($productDtls) && $productDtls->addon_group_id > 0) {
                $AddonGroupRelation = Addon::where('addon_group_id', $productDtls->addon_group_id)->distinct('product_id')->orderBy('sl_no','ASC')->pluck('product_id');
                if(isset($AddonGroupRelation) && count($AddonGroupRelation) > 0) {
                    $gift_addons = Product::where(['is_block'=>'N', 'addon'=>'Y', 'product_type'=>'A'])->whereIn('id',$AddonGroupRelation)->get();
                }
            } else {
                $getFirstAddonGroup = AddonGroup::where('is_block', 'N')->first();
                if($getFirstAddonGroup) {
                    $AddonGroupRelation = Addon::where('addon_group_id', $getFirstAddonGroup->id)->distinct('product_id')->orderBy('sl_no','ASC')->pluck('product_id');
                    if(isset($AddonGroupRelation) && count($AddonGroupRelation) > 0) {
                        $gift_addons = Product::where(['is_block'=>'N', 'addon'=>'Y', 'product_type'=>'A'])->whereIn('id',$AddonGroupRelation)->get();
                    }
                } else {
                    $gift_addons = [];
                }
                
            }

            //dd($request);

            
        }
        return view('site.product.gift_addon')->with(['gift_addons'=>$gift_addons, 'request' => $request]);
    }

    public function checkShippingOption( Request $request ) {
        if ($request->isMethod('POST')) {
            $product_id = isset($request->product_id)?base64_decode($request->product_id):0;

            $shippingDtls = ShippingDetail::where(['is_block' => 'N'])->get();

            if(count($shippingDtls) > 1){
                return response()->json(['status'=>'show']);
            }else{
               return response()->json(['status'=>'hide']); 
            }

        }
    }

    public function ShippingOptionDetail( Request $request ) {
        if ($request->isMethod('POST')) {
            $sid = isset($request->sid)?$request->sid:0;

            $shippingDtls = ShippingDetail::where(['id' => $sid, 'is_block' => 'N'])->first();

            
            return response()->json(['status'=>'success', 'shippingName' => $shippingDtls->shipping_name, 'shippingCharge' => $shippingDtls->shipping_charge]);
            

        }
    }


    /* Search by city with keywords*/
    public function searchByKeyword(Request $request )
    {
        //dd($request);
        $last_param = $request->query('query');

        //session(['selected_city' => $City_detail->slug, 'selected_city_id' => $City_detail->id, 'checkout.city_id' => $City_detail->slug]);
         
        $this->search_insert($request->query('query'));
        //die;
        //DB::enableQueryLog();

        $product_sort_option = ProductSortbyOption::where(['status'=>'N'])->pluck('name','sortby');
        $dropdown_data = $product_sort_option->toArray();


        if($request->sort_by == 'recent' ){
            $field        = 'products.id';
            $value        = 'DESC';
        }else if($request->sort_by == 'highest' ){
            $field        = 'products.price';
            $value        = 'DESC';
        }
        else if($request->sort_by == 'lowest' ){
            $field        = 'products.price';
            $value        = 'ASC';
        }else{
            $field        = 'products.id';
            $value        = 'DESC';
        }


        $products =  DB::table('products')
                        ->select('products.id', 'products.product_name', 'products.slug', 'products.price', 'products.actual_price', 'products.alt_key', 'products.delivery_delay_days', 'products.fnid', 'products.has_attribute')
                        ->where('products.is_block','=', 'N')
                        ->where(function($q) use ($last_param) {
                            $q->where([['products.product_name','like',"%$last_param%"]])
                              ->orWhere([['products.description','like',"%$last_param%"]])
                              ->orWhere([['products.content','like',"%$last_param%"]])
                              ->orWhere([['products.search_tag','like',"%$last_param%"]]);
                        })
                        ->orderBy($field, $value)->limit(8)->get();


        //dd(DB::getQueryLog());     
        //dd($products);         


        return view('site.product.searchbykeyword')->with(['token' => $request->query('query'), 'products'=>$products, 'sortby_option_data' => $dropdown_data, 'sort'=>$request->sort_by, 'request' => $request]);
    }

    /* LOADMORE - Search by keywords*/
    public function loadMoreSearchByKeyword( Request $request ){
        //dd($request);
        //die();
      

        if (is_null($request->query('query'))) {
            //throw new NotFoundHttpException;
            abort('404');
            die();
        }


        $items_per_page = 8;
        $offset = ($request->page - 1) * $items_per_page; 

        $last_param = $request->query('query');
        
        if($request->sort_by == 'recent' ){
            $field        = 'products.id';
            $value        = 'DESC';
        }else if($request->sort_by == 'highest' ){
            $field        = 'products.price';
            $value        = 'DESC';
        }
        else if($request->sort_by == 'lowest' ){
            $field        = 'products.price';
            $value        = 'ASC';
        }else{
            $field        = 'products.id';
            $value        = 'DESC';
        }
        

        $products =  DB::table('products')
                        ->select('products.id', 'products.product_name', 'products.slug', 'products.price', 'products.actual_price', 'products.alt_key', 'products.delivery_delay_days', 'products.fnid', 'products.has_attribute')
                        ->where('products.is_block','=', 'N')
                        ->where(function($q) use ($last_param) {
                            $q->where([['products.product_name','like',"%$last_param%"]])
                              ->orWhere([['products.description','like',"%$last_param%"]])
                              ->orWhere([['products.search_tag','like',"%$last_param%"]]);
                        })
                        ->orderBy($field, $value)->limit($items_per_page)->offset($offset)->get();

        return view('site.product.loadmore')->with(['products'=>$products, 'pathurl'=>$request->pathurl]);

    }

    public function build_calender(Request $request){
        $sameday = $nextday = $othersday = $nextTwoday = $saturday = $sunday = '';
        $counter = $counter2 = 0;
        $firstDayDiff = 0; $secondDayDiff = 1;
        

        $month = $request->month;
        $year = $request->year;

        $delayDay = $request->delaydays;


        $daysOfWeek = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
        $numberDays = date('t', $firstDayOfMonth);
        $dateComponents = getdate($firstDayOfMonth);
        $monthName = $dateComponents['month'];
        $dayOfWeek = $dateComponents['wday'];
        $dateToday = date('Y-m-d');
    
        //print_r($dateComponents); die;
    
        $prev_month = date('m', mktime(0, 0, 0, $month-1, 1, $year));
        $prev_year = date('Y', mktime(0, 0, 0, $month-1, 1, $year));
        $next_month = date('m', mktime(0, 0, 0, $month+1, 1, $year));
        $next_year = date('Y', mktime(0, 0, 0, $month+1, 1, $year));
    
        $calender = "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='deldate-calender'>";
        $calender .= "<tr>";

        $calender .= "<td class='deldate-month-change changemonth' data-month = ".$prev_month." data-year=".$prev_year." data-city=".$request->cityid." data-delaydays=".$request->delaydays."><a href='javascript:void(0)' class='deldate-arrow'><span class='material-icons-outlined text-dark'>arrow_back_ios</span></a></td>";
        $calender .= "<td class='deldate-month'>$monthName $year</td>";
        $calender .= "<td class='deldate-month-change changemonth' data-month = ".$next_month." data-year=".$next_year." data-city=".$request->cityid." data-delaydays=".$request->delaydays."><a href='javascript:void(0)' class='deldate-arrow'><span class='material-icons-outlined text-dark'>arrow_forward_ios</span></a></td>";

        $calender .= "</tr><tr><td colspan='3' class='deldate-days'>";
        $calender .= "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";

        
    
        foreach ($daysOfWeek as $day) {
            $calender .= "<td class='days-shor'>$day</td>";
        }
    
        $calender .= "</tr><tr>";
        $currentDay = 1;
    
        if($dayOfWeek > 0){
            for($k=0; $k<$dayOfWeek; $k++){
                $calender .= "<td class='disable-date empty'></td>";
            }
        }
    
        $month = str_pad($month, 2, "0", STR_PAD_LEFT);

        $dateStartFrom = date('Y-m-d');
        // if($nextdayAfterTime == 0){
        //     $dateStartFrom = date('Y-m-d');
        // }else{
        //     $dateStartFrom = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day'));
        // }
                
        $delayDaysDate = date('Y-m-d', strtotime($dateStartFrom . ' +'.$delayDay.' day'));



        //echo $dateStartFrom;
        //echo "l:".$delayDaysDate;
        
       
            
            

                
                
            
        while($currentDay <= $numberDays) {
            
            if($dayOfWeek == 7){
                $dayOfWeek = 0;
                $calender .= "</tr><tr>";
            }
    
            $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
            $date = "$year-$month-$currentDayRel";
            $dayName = strtolower(date('I', strtotime($date)));
            //$today = $date==date('Y-m-d')?'today':'';

            //echo $delayDaysDate;
    
            if($date < $delayDaysDate){
                $today = 'disable-date';
            }
            elseif($date == $dateStartFrom){
                $today = 'pick-date-today pickSelectDate';
            }
            else{
                $today = 'pick-date pickSelectDate';
            }
    
                
            

    
            

            $calender .= "<td class='$today' data-shipdate='$year-$month-$currentDayRel'>".(int)$currentDayRel."</td>";
    
            $currentDay++;
            $dayOfWeek++;
        }
    
        if($dayOfWeek < 7){
            $remainingDays = 7 - $dayOfWeek;
            for($i=0; $i<$remainingDays; $i++){
                $calender .= "<td class='disable-date empty'></td>";
            }
        }
    
        $calender .= "</tr></table>";

        $calender .= "</td></tr></table>";
        //$calender .= "<input type='hidden' id='CountryID' value='".$request->countryid."'><input type='hidden' id='CityID' value='".$request->cityid."'><input type='hidden' id='DelayDays' value=value='".$request->delaydays."'>";
    
        //return $calender;
        return view('site.calender')->with(['calender'=>$calender]);
    }

    public function reviewpost(Request $request){
                
        if ($request->isMethod('POST')) {

            $ratingProduct = ProductRating::insert([
                'product_id' => base64_decode($request->product_id),
                'rating' => $request->rating,
                'review' => $request->review,
                'user_name' => $request->user_name, 
                'delivery_place' => $request->delplace,
                'sender_place'=>$request->fromplace, 
                'user_email'=>$request->email
            ]);

            if($ratingProduct){
                return response()->json(['status'=>'success']);
            }
        }
    }

    public function changecity(Request $request){
        //dd($request);
        $city = City::where(['id' => $request->cid])->first();
        
        return response()->json(['type' => 'json', 'status' => 'success', 'city' => $city]);
        //return json_encode(['type' => 'json', 'status' => 'success', 'city' => $city]);
    }
}