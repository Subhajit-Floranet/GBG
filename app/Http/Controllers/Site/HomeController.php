<?php
namespace App\Http\Controllers\Site;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Models\Cms;
use App\Models\Testimonial;
use App\Models\HomePageFeatureCategory;
use App\Models\Currency;
use Auth;


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

    
}