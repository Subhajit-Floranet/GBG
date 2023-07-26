<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GBGCms;
use App\Models\GBGHomeFeatureManagement;
use App\Models\GBGProductCategory;
use App\Models\GBGCategory;
use App\Models\GBGProduct;
use Illuminate\Http\Request;
use Auth;

class GBGHomeFeatureManagementController extends CommonController
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

  
    // public function list(Request $request){

    //     if($this->checkPermission('cms','list') == false && $this->checkSuperPermission('cms','list') == false ){
    //         $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
    //         return redirect()->route('admin.home');
    //     }

    //     $websiteShortCode = 'gbg';

    //     $where = $orWhere = [];

    //     $result = GBGCms::orderBy('created_at', 'desc')->get();

    //     return view('admin.GBG.cms.list', ['result' => $result, 'request' => $request, 'websiteShortCode' => $websiteShortCode]);
    // }

    
    public function add(Request $request){

        if($this->checkPermission('homefeaturemanagement','list') == false && $this->checkSuperPermission('homefeaturemanagement','list') == false ){
            $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
            return redirect()->route('admin.home');
        }

        $websiteShortCode = 'gbg';

        $obj = new GBGHomeFeatureManagement;
        $category = GBGCategory::all();

        // if($request->isMethod('POST')){
        //     //dd($request);
        //     $request->validate([
        //         'title'=>'required',
        //         'content'=>'required',
        //         'meta_title'=>'required',
        //         'meta_description'=>'required'
        //     ]);

        //     if($cat = $obj->create([
        //             'title' => $request->title, 
        //             'content' => $request->content, 
        //             'slug' => $request->slug, 
        //             'meta_title' => $request->meta_title, 
        //             'meta_description' => $request->meta_description,
        //             'created_by' => Auth::guard('admin')->user()->id
        //         ]))
        //     {
        //         $request->session()->flash('alert-success', 'CMS successfully added.');
        //         return redirect()->route('admin.gbg.cms.list');
        //     }else{
        //         $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
        //         return redirect()->back()->with($request->except(['_method', '_token']));
        //     }
            
        // }

        return view('admin.gbg.homefeaturemanagement.add', ['request' => $request, 'category' => $category, 'websiteShortCode' => $websiteShortCode]);
    }

    public function categoryproduct(Request $request){
       
        $catid = $request->catid;
        //echo ($catid);
        $categoriesid = GBGProductCategory::where(['category_id' => $catid])->get();
        //print_r($categoriesid);
        $product=[];
        foreach($categoriesid as $categoriesid){
            //$product = GBGProduct::where(['id' => $categoriesid->product_id])->get();
            $product = GBGProductCategory::join('products','product_categories.product_id','=','')
        }
    //     foreach($product as $product){
    //         echo $product->product_name;
    //     }
      
    //    if($product){

    //         $prompt = array('product' => 'product');

    //         echo json_encode($prompt);
    //     }else{
    //         $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
    //         return redirect()->back();
    //     }
    }

    // public function edit($id = null, Request $request){

    //     if($this->checkPermission('cms','list') == false && $this->checkSuperPermission('cms','list') == false ){
    //         $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
    //         return redirect()->route('admin.home');
    //     }

    //     $websiteShortCode = 'gbg';

    //     $id = base64_decode($id);
	// 	$dataDetails  = GBGCms::where('id',$id)->first();

    //     $obj = new GBGCms;

    //     if($request->isMethod('POST')){
    //         //dd($request);
    //         $request->validate([
    //             'title'=>'required',
    //             'content'=>'required',
    //             'meta_title'=>'required',
    //             'meta_description'=>'required'
    //         ]);
            
    //         $update_arr['title'] = $request->title;
    //         $update_arr['content'] = $request->content;
    //         $update_arr['slug'] = $request->slug;
    //         $update_arr['meta_title'] = $request->meta_title;
    //         $update_arr['meta_description'] = $request->meta_description;

    //         if(GBGCms::where(['id' => $request->formid])->update($update_arr)){
                   
    //             $request->session()->flash('alert-success', 'CMS successfully updated.');
    //             return redirect()->route('admin.gbg.cms.list');
    //         }else{
    //             $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
    //             return redirect()->back()->with($request->except(['_method', '_token']));
    //         }
    //     }

    //     return view('admin.GBG.cms.edit', ['dataDetails' => $dataDetails, 'request' => $request, 'websiteShortCode' => $websiteShortCode]);
    // }

    // public function delete($id = null, Request $request)
    // {
    //     if($this->checkPermission('cms','list') == false && $this->checkSuperPermission('cms','list') == false ){
    //         $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
    //         return redirect()->route('admin.home');
    //     }

    //     if($id == null){
    //         return redirect()->route('admin.home');
    //     }
    //     $id = base64_decode($id);

    //     //$event_details = HomeNews::find($id);

    //     //@unlink(public_path() . '/uploaded/event_images/' . $event_details->eimg);

    //     if( GBGCms::where(['id' => $id])->delete()){
    //         $request->session()->flash('alert-success', 'CMS deleted successfully.');
    //         return redirect()->back();
    //     }else{
    //         $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
    //         return redirect()->back();
    //     }
    // }

    // public function status(Request $request)
    // {
    //     if($request->id == null || $request->status == null){
    //         return redirect()->route('admin.dashboard');
    //     }
    //     $id = base64_decode($request->id);
    //     //$block = 'N';
    //     //$blockText = 'blocked';
    //     switch($request->status){
    //         case 'N':
    //             $block = 'Y';
    //             $blockText = 'Block';
    //             break;
    //         case 'Y':
    //             $block = 'N';
    //             $blockText = 'Unblock';
    //             break;
    //         default:
    //             $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
    //             return redirect()->back();
    //     }
    //     if(GBGCms::where(['id' => $id])->update(['is_block' => $block])){
    //         //$request->session()->flash('alert-success', 'Category successfully '.$blockText);
    //         //return redirect()->back();
    //         $prompt = array('status' => 1, 'id' => $id, 'event' => $blockText);

    //         echo json_encode($prompt);
    //     }else{
    //         $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
    //         return redirect()->back();
    //     }
    // }
}