<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GBGCms;
use App\Models\GBGCoupon;
use Illuminate\Http\Request;
use Auth;

class GBGCouponController extends CommonController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

  
    public function list(Request $request){

        if($this->checkPermission('coupon','list') == false && $this->checkSuperPermission('coupon','list') == false ){
            $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
            return redirect()->route('admin.home');
        }

        $websiteShortCode = 'gbg';

        $where = $orWhere = [];

        $result = GBGCoupon::orderBy('created_at', 'desc')->get();

        return view('admin.GBG.coupon.list', ['result' => $result, 'request' => $request, 'websiteShortCode' => $websiteShortCode]);
    }

    
    public function add(Request $request){

        if($this->checkPermission('coupon','list') == false && $this->checkSuperPermission('coupon','list') == false ){
            $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
            return redirect()->route('admin.home');
        }

        $websiteShortCode = 'gbg';

        $obj = new GBGCoupon;

        if($request->isMethod('POST')){
            //dd($request);
            $request->validate([
                'coupon_code'=>'required',
                'discount_type'=>'required',
                'amount'=>'required',
                'start_date'=>'required',
                'end_date'=>'required',
                'minimum_cart_amount'=>'required'
            ]);

            if($coupon = $obj->create([
                    'coupon_code' => $request->coupon_code, 
                    'type' => $request->discount_type, 
                    'amount' => $request->amount, 
                    'start_date' => $request->start_date, 
                    'end_date' => $request->end_date,
                    'minimum_cart_amount' => $request->minimum_cart_amount,
                    'created_by' => Auth::guard('admin')->user()->id
                ]))
            {
                $request->session()->flash('alert-success', 'Coupon successfully added.');
                return redirect()->route('admin.gbg.coupon.list');
            }else{
                $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
                return redirect()->back()->with($request->except(['_method', '_token']));
            }
            
        }

        return view('admin.gbg.coupon.add', ['request' => $request, 'websiteShortCode' => $websiteShortCode]);
    }

    public function edit($id = null, Request $request){

        if($this->checkPermission('coupon','list') == false && $this->checkSuperPermission('coupon','list') == false ){
            $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
            return redirect()->route('admin.home');
        }

        $websiteShortCode = 'gbg';

        $id = base64_decode($id);
		$dataDetails  = GBGCoupon::where(['id' => $id])->first();


        if($request->isMethod('POST')){
            //dd($request);
            $request->validate([
                'coupon_code'=>'required',
                'discount_type'=>'required',
                'amount'=>'required',
                'start_date'=>'required',
                'end_date'=>'required',
                'minimum_cart_amount'=>'required'
            ]);
            
            // $update_arr['coupon_code'] = $request->coupon_code;
            // $update_arr['type'] = $request->discount_type;
            // $update_arr['amount'] = $request->amount;
            // $update_arr['start_date'] = $request->start_date;
            // $update_arr['end_date'] = $request->end_date;
            // $update_arr['minimum_cart_amount'] = $request->minimum_cart_amount;

            //   print_r($update_arr);
            //   echo $id;
            //   die;

            $obj = GBGCoupon::find($id);
            $obj->coupon_code =  $request->coupon_code;
            $obj->type =  $request->discount_type;
            $obj->amount =  $request->amount;
            $obj->start_date =  $request->start_date;
            $obj->end_date =  $request->end_date;
            $obj->minimum_cart_amount =  $request->minimum_cart_amount;
            $obj->update();

             if($obj){
                $request->session()->flash('alert-success', 'Coupon successfully updated.');
                return redirect()->route('admin.gbg.coupon.list');
            }else{
                $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
                return redirect()->back()->with($request->except(['_method', '_token']));
            }
        }

        return view('admin.GBG.coupon.edit', ['dataDetails' => $dataDetails, 'request' => $request, 'websiteShortCode' => $websiteShortCode]);
    }

    public function delete($id = null, Request $request)
    {
        if($this->checkPermission('coupon','list') == false && $this->checkSuperPermission('coupon','list') == false ){
            $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
            return redirect()->route('admin.home');
        }

        if($id == null){
            return redirect()->route('admin.home');
        }
        $id = base64_decode($id);

        //$event_details = HomeNews::find($id);

        //@unlink(public_path() . '/uploaded/event_images/' . $event_details->eimg);

        if( GBGCoupon::where(['id' => $id])->delete()){
            $request->session()->flash('alert-success', 'Coupon deleted successfully.');
            return redirect()->back();
        }else{
            $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
            return redirect()->back();
        }
    }

    public function status(Request $request)
    {
        if($request->id == null || $request->status == null){
            return redirect()->route('admin.dashboard');
        }
        $id = base64_decode($request->id);
        //$block = 'N';
        //$blockText = 'blocked';
        switch($request->status){
            case 'N':
                $block = 'Y';
                $blockText = 'Block';
                break;
            case 'Y':
                $block = 'N';
                $blockText = 'Unblock';
                break;
            default:
                $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
                return redirect()->back();
        }
        if(GBGCoupon::where(['id' => $id])->update(['is_block' => $block])){
            //$request->session()->flash('alert-success', 'Category successfully '.$blockText);
            //return redirect()->back();
            $prompt = array('status' => 1, 'id' => $id, 'event' => $blockText);

            echo json_encode($prompt);
        }else{
            $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
            return redirect()->back();
        }
    }
}