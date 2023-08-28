<?php
namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller as RootController;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\ProductSortbyOption;
use DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CityController extends RootController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    public function index( $city_slug = null, Request $request ) {
        if (is_null($city_slug)) {
            throw new NotFoundHttpException;
        }
        //dd($country_slug);
        $city_details = City::where(['slug' => $city_slug])->first();

        //dd($city_details);
        
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
            $field        = 'products.description';
            $value        = 'DESC';
        }


        $products =  DB::table('products')
                        ->select('products.id', 'products.product_name', 'products.slug', 'products.price', 'products.actual_price', 'products.alt_key', 'products.delivery_delay_days', 'products.fnid', 'products.has_attribute')
                        ->where(['products.is_block'=>'N', 'product_type'=>'M'])
                        ->orderBy($field, $value)->limit(8)->get();


        //dd(DB::getQueryLog());     
        //dd($products);         


        return view('site.city.index')->with(['token' => $request->query('query'), 'products'=>$products, 'sortby_option_data' => $dropdown_data, 'sort'=>$request->sort_by, 'request' => $request, 'city_details' => $city_details]);
        
    }

    public function loadMoreCity( Request $request ){
        

        $items_per_page = 8;
        $offset = ($request->page - 1) * $items_per_page; 

        
        
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
            $field        = 'products.description';
            $value        = 'DESC';
        }
        

        $products =  DB::table('products')
                        ->select('products.id', 'products.product_name', 'products.slug', 'products.price', 'products.actual_price', 'products.alt_key', 'products.delivery_delay_days', 'products.fnid', 'products.has_attribute')
                        ->where(['products.is_block'=>'N', 'product_type'=>'M'])
                        ->orderBy($field, $value)->limit($items_per_page)->offset($offset)->get();

        return view('site.product.loadmore')->with(['products'=>$products, 'pathurl'=>$request->pathurl]);

    }
}