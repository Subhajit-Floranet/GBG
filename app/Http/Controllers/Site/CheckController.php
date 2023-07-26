<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Category;
use App\Models\Product;
use App\Models\FalseUrl;
use App\Models\FalseurlProductSortorder;
use App\Model\Country;
use App\Models\City;


class CheckController extends Controller
{
    public function everyCombination($array)
    {
        $array_use = $array;
        $array_use1 = $array;
        $array_use = array_values($array_use);
        foreach ($array_use as $values) {
            $value = $values;
            unset($array_use[0]);
            $array_use = array_values($array_use);
            $combined_text = $value;
            $j = 0;
            foreach ($array_use1 as $value2) {
                for ($i = 0; $i <= count($array_use) - $j; $i++) {
                    if (isset($array_use[$i])) {
                        $combined_text .= '/' . $array_use[$i];
                    }
                }
                $combination[] = $combined_text;
                $combined_text = $value;
                $j++;
            }
        }
        return array_unique($combination);
    }

    public function depthPicker($arr, $temp_string, $collect)
    {
        
        if ($temp_string != ""){
            $collect[] = $temp_string;
        }
    
        
        for ($i = 0; $i < sizeof($arr); $i++) {
            $arrcopy = $arr;
            $elem = array_splice($arrcopy, $i, 1); // removes and returns the i'th element
            if (sizeof($arrcopy) > 0) {
                $final_string = trim($temp_string . "/" . $elem[0], '/');
                self::depthPicker($arrcopy, $final_string, $collect);
            } else {
                $collect[] = trim($temp_string . "/" . $elem[0], '/');
            }
        }
    }

    public function index($param = null, Request $request)
    {
        if (is_null($param)) {
            throw new NotFoundHttpException;
        }
        //dd($param);
        $original_param = $param;

        $breadcrumb = [];
        $data = [];
        $data_count = 0;
        
        // $params = explode('/', $param);
        // //print_r($params); die;
        // $breadcrum_params = $params;
        // $first_param = reset($params);
        // $last_param = last($params);

        $params = $original_param;
        $breadcrum_params = explode('/', $params);
        $last_param = $params;

        $updated_params = [];
        foreach ($breadcrum_params as $key => $value) {
            //$updated_params[] = str_replace('-', ' ', $value);
            $updated_params[] = $value;
        }
        $breadcrumb = $updated_params;



        //if(count($params) == 1 && City::where(['slug' => reset($params)])->first()){
        if(City::where(['slug' => $params])->first()){
            //dd("Its a city Default");
            $getCityDtl = City::where(['slug'=> $params])->first();
            //session(['selected_city' => $getCityDtl->slug, 'selected_city_id' => $getCityDtl->id, 'checkout.city_id' => $getCityDtl->id]);
            return app('App\Http\Controllers\Site\CityController')->index($params, $request);
        }else{
            //dd("Its not city");
        }

        $collect = array();
        //self::depthPicker($params, "", $collect);
        //$all_combitions = $collect;

        //$all_combitions = $this->everyCombination($params);
        //$anotherCombination = self::everyCombination($params);

        

        $false_url = FalseUrl::where('slug_url', $params)->where(['is_block' => 'N'])->first();
        
        if ($false_url) {
            //echo "false";
            $getCatDtl = Category::where(['id'=> $false_url->category_id])->first();
            //$getCatCity = City::where(['id'=> $false_url->city_id])->first();
            $data['cat_id'] = $false_url->category_id;
            $data['cat_slug'] = $getCatDtl->slug;
            $data['cat_name'] = $getCatDtl->name;
            $data['cat_page_head'] = '';
            //$data['cat_country_id'] = $false_url->country_id;
            $data['cat_city_id'] = $false_url->city_id;
            $data['banner_image_alt'] = $false_url->banner_image_alt;
            $data['banner_heading'] = $false_url->banner_heading;
            $data['cat_top_content'] = $false_url->content_top;
            $data['cat_image'] = $false_url->banner_img;
            $data['cat_bottom_content'] = $false_url->content_bottom;
            $data['cat_meta_title'] = $false_url->meta_title;
            $data['cat_meta_keyword'] = $false_url->meta_keyword;
            $data['cat_meta_description'] = $false_url->meta_description;
            $data['cat_tag_line'] = $false_url->tag_line;
            $data['cat_tophead'] = $false_url->tophead;
            $data['urlpath'] = $getCatDtl->slug."/";

            $false_url_sort_order = FalseurlProductSortorder::where(['id' => $false_url->sort_order])->first();
            $data['false_url_order_title'] = $false_url_sort_order->sortby;

            $data['dataTable'] = 'flase';

            //session(['selected_country' => $getCatCountry->slug, 'selected_country_id' => $getCatCountry->id, 'checkout.country_id' => $getCatCountry->id]);

            // if($getCatCity->id > 0){
            //     session(['selected_city' => $getCatCity->slug, 'selected_city_id' => $getCatCity->id, 'checkout.city_id' => $getCatCity->id]);
            // }

        }else{
            

                //if(count($params) == 1){
                    $category_url = Category::where('slug', $last_param)->where(['is_block' => 'N'])->first();

                    if ($category_url) {
                        //dd("one");
                        $data['cat_id'] = $category_url->id;
                        $data['cat_slug'] = $category_url->slug;
                        $data['cat_name'] = $category_url->name;
                        $data['cat_page_head'] = $category_url->page_head;
                        $data['cat_city_id'] = 0;
                        $data['banner_image_alt'] = $category_url->image_alt;
                        $data['banner_heading'] = $category_url->banner_heading;
                        $data['cat_top_content'] = $category_url->content_top;
                        $data['cat_image'] = $category_url->image;
                        $data['cat_bottom_content'] = $category_url->content_bottom;
                        $data['cat_meta_title'] = $category_url->meta_title;
                        $data['cat_meta_keyword'] = $category_url->meta_keyword;
                        $data['cat_meta_description'] = $category_url->meta_description;
                        $data['cat_tag_line'] = $category_url->tag_line;
                        $data['cat_tophead'] = $category_url->tophead;
                        $data['urlpath'] = $category_url->slug."/";

                        $data['false_url_order_title'] = '';
                        $data['dataTable'] = 'Category';
                        session(['selected_city' => '', 'selected_city_id' => 0]);
                    }else{
                        //abort('404');
                        if (Product::where(['slug' => $last_param, 'is_block' => 'N'])->exists()) {
                            //dd("detail");
                            //session(['selected_city' => $getCityDtl->slug, 'selected_city_id' => $getCityDtl->id, 'checkout.city_id' => $getCityDtl->id]);
                        } else {
                            abort('404');
                        }
                    }
                // }else{
                //     abort('404');
                // }
                    
                
        }

        if (Product::where(['slug' => $last_param, 'is_block'=>'N'])->exists()) {
            //array_pop($params);
            return app('App\Http\Controllers\Site\ProductController')->index((isset($data['cat_slug'])) ? $data['cat_slug'] : $params, $last_param, null, $breadcrumb, $request, $data);
        } else {
            return app('App\Http\Controllers\Site\CategoryController')->index($last_param, null, $breadcrumb, $data, $request);
        }
    }
}