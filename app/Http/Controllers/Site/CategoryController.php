<?php

namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\FalseurlProductSortorder;
use App\Models\ProductSortbyOption;
use Config;
use DB;
use Session;

class CategoryController extends Controller
{
    public function index( $catgory_slug = null, $token = null, $breadcrumb = [], $data = [], Request $request ) {
        //die;
        //echo $data['urlpath'];
        if (is_null($catgory_slug)) {
            abort(404);
        }
        if($request->error_page){
            abort(404);
        }

        $product_sort_option = ProductSortbyOption::where(['status'=>'N'])->pluck('name','sortby');
        $dropdown_data = $product_sort_option->toArray();

        $catDetail = Category::where(['id'=>$data['cat_id']])->first();

        if($data['false_url_order_title'] == 'recent' || $request->sort_by == 'recent' ){
            $field        = 'products.id';
            $value        = 'DESC';
        }else if( $data['false_url_order_title'] == 'highest' || $request->sort_by == 'highest' ){
            $field        = 'products.price';
            $value        = 'DESC';
        }
        else if( $data['false_url_order_title'] == 'lowest' || $request->sort_by == 'lowest' ){
            $field        = 'products.price';
            $value        = 'ASC';
        }else{
            if($catDetail->cat_section == 'N' or  $catDetail->cat_section == 'F'){
                //$field        = 'products.id';
                $field        = 'product_categories.sort';
                $value        = 'ASC';
            } elseif($catDetail->cat_section == 'P') {
                $field        = 'products.price';
                $value        = 'ASC';
            } elseif($catDetail->cat_section == 'S') {
                $field        = 'products.id';
                $value        = 'ASC';
            }
        }

        $products = DB::table('products')
                        ->select('products.id', 'products.product_name', 'products.slug', 'products.price', 'products.actual_price', 'products.alt_key', 'products.delivery_delay_days', 'products.fnid', 'products.has_attribute')
                        ->where(['products.is_block'=>'N', 'product_type'=>'M']);
                        

        if($catDetail->cat_section == 'N' or  $catDetail->cat_section == 'F'){
            $products->join('product_categories', 'product_categories.product_id', '=', 'products.id')
                        ->where( 'product_categories.category_id', '=', $data['cat_id'] );
        } elseif($catDetail->cat_section == 'P') {
            $pricebrand = DB::table('price_brand')->where('category_id', '=', $data['cat_id'])->first();
            if($pricebrand->equation == 'between'){
                $products->whereBetween('products.price', [$pricebrand->from_price, $pricebrand->to_price]);
            } elseif ($pricebrand->equation == 'greater'){
                $products->where('products.price', '>=', $pricebrand->from_price);
            } elseif ($pricebrand->equation == 'less'){
                $products->where('products.price', '<', $pricebrand->to_price);
            }
        } elseif($catDetail->cat_section == 'S') {
            $products->where('actual_price', '>', 0);
        }


        

        $prod = $products->orderBy($field, $value)->limit(8)->get();

        //dd($prod);
        return view('site.product.listing')->with(['token' => $token, 'catgory_slug' => $catgory_slug, 'breadcrumb' => $breadcrumb, 'data' => $data, 'request' => $request, 'products'=>$prod, 'sortby_option_data' => $dropdown_data, 'sort'=>$request->sort_by]);
    }

    public function loadMore(Request $request) {
        $items_per_page = 8;
        $offset = ($request->page - 1) * $items_per_page; 
        $flag = 0;

        $cat_id = '';
        if ( $request->cat_id != '' ){
            $cat_id = $request->cat_id;
            $flag = 1;
        }else{
            abort(404);
        }

        

        if($flag == 1){
            $catDetail = Category::where(['id'=>$cat_id])->first();

            if($request->sort_by == 'recent' ){
                $field        = 'products.id';
                $value        = 'DESC';
            }else if( $request->sort_by == 'highest' ){
                $field        = 'products.price';
                $value        = 'DESC';
            }
            else if( $request->sort_by == 'lowest' ){
                $field        = 'products.price';
                $value        = 'ASC';
            }else{
                if($catDetail->cat_section == 'N' or  $catDetail->cat_section == 'F'){
                    //$field        = 'products.id';
                    $field        = 'product_categories.sort';
                    $value        = 'ASC';
                } elseif($catDetail->cat_section == 'P') {
                    $field        = 'products.price';
                    $value        = 'ASC';
                } elseif($catDetail->cat_section == 'S') {
                    $field        = 'products.id';
                    $value        = 'ASC';
                }
            }

            $products = DB::table('products')
                        ->select('products.id', 'products.product_name', 'products.slug', 'products.price', 'products.actual_price', 'products.alt_key', 'products.delivery_delay_days', 'products.fnid', 'products.has_attribute')
                        ->where(['products.is_block'=>'N', 'product_type'=>'M']);
                        
        
            if($catDetail->cat_section == 'N' or  $catDetail->cat_section == 'F'){
                $products->join('product_categories', 'product_categories.product_id', '=', 'products.id')
                            ->where( 'product_categories.category_id', '=', $cat_id );
            } elseif($catDetail->cat_section == 'P') {
                $pricebrand = DB::table('price_brand')->where('category_id', '=', $cat_id)->first();
                if($pricebrand->equation == 'between'){
                    $products->whereBetween('products.price', [$pricebrand->from_price, $pricebrand->to_price]);
                } elseif ($pricebrand->equation == 'greater'){
                    $products->where('products.price', '>=', $pricebrand->from_price);
                } elseif ($pricebrand->equation == 'less'){
                    $products->where('products.price', '<', $pricebrand->to_price);
                }
            }elseif($catDetail->cat_section == 'S') {
                $products->where('actual_price', '>', 0);
            }


            if(isset($request->cat_city_id)){
                if($request->cat_city_id != 0){
                    $products->join('product_cities', 'product_cities.product_id', '=', 'products.id')
                                ->where('product_cities.city_id','=', $request->cat_city_id);
                }
            }

            $prod = $products->orderBy($field, $value)->limit($items_per_page)->offset($offset)->get();
            
        }else{
            $prod = [];
        }

        return view('site.product.loadmore')->with(['products'=>$prod, 'pathurl'=>$request->pathurl]);
    }
}