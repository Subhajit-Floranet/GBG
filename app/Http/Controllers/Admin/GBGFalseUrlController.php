<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GBGCategory;
use App\Models\GBGFalseUrl;
use App\Models\GBGFalseUrlProductSort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;


class GBGFalseUrlController extends CommonController
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

        if($this->checkPermission('falseurl','list') == false && $this->checkSuperPermission('falseurl','list') == false ){
            $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
            return redirect()->route('admin.home');
        }

        $websiteShortCode = 'gbg';

        $where = $orWhere = [];
        $result = GBGFalseUrl::join('categories','false_urls.category_id','=','categories.id')->get(["categories.name","false_urls.*"]);
        return view('admin.GBG.falseurl.list', ['result' => $result,  'request' => $request, 'websiteShortCode' => $websiteShortCode]);
    }

    public function add(Request $request){

        if($this->checkPermission('falseurl','list') == false && $this->checkSuperPermission('falseurl','list') == false ){
            $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
            return redirect()->route('admin.home');
        }

        $websiteShortCode = 'gbg';
        $websiteUrl = 'https://www.giftbasketsgermany.de/';
        
        //$catdata = GBGCategory::where(['is_block' == 'N'])->pluck('id','name');
        $obj = new GBGFalseUrl;
        if($request->isMethod('POST')){
            $request->validate([
                'falseurl'=>'required',
                'category'=>'required',
                'banneralt'=>'required',
                'bannerheading'=>'required',
                'falseurlcontenttop'=>'required',
                'falseurlcontentbottom'=>'required',
                'productsorder'=>'required',
                'falseurlmeta_title'=>'required',
                'falseurlmeta_description'=>'required',
                'falseurltagline'=>'required',
                'falseurltophead'=>'required'
            ]);

            $imageName = '';
            $a = 0;
            $imagefile=$request->file("fimage");
            if (isset($imagefile)){

                if(!is_dir(public_path() . '/uploads/falseurl/')){
                    mkdir(public_path() . '/uploads/falseurl/', 0777, true);
                }

                $imageName = strtotime(now()).rand(11111,99999).'-banner.'.$imagefile->getClientOriginalExtension();
                $imagefile->move(public_path() . '/uploads/falseurl/', $imageName);
            }

            if($cat = $obj->create([
                    'country_id'=> $a, 
                    'slug_url' => $request->falseurl,
                    'category_id' => $request->category, 
                    'banner_img' => $imageName,
                    'banner_img_alt' => $request->banneralt,
                    'banner_heading' => $request->bannerheading,
                    'content_top' => $request->falseurlcontenttop,
                    'content_bottom' => $request->falseurlcontentbottom,
                    'sort_order' => $request->productsorder,
                    'meta_title' => $request->falseurlmeta_title, 
                    'meta_description' => $request->falseurlmeta_description,
                    'tag_line' => $request->falseurltagline,
                    'tophead' => $request->falseurltophead
                   
            ]))
            {
                $request->session()->flash('alert-success', 'FalseURL successfully added.');
                return redirect()->route('admin.gbg.falseurl.list');
            }else{
                $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
                return redirect()->back()->with($request->except(['_method', '_token']));
            }
            
        }
        $catdata=GBGCategory::all();
        $orderdata=GBGFalseUrlProductSort::all();
        return view('admin.gbg.falseurl.add', ['request' => $request, 'catdata' => $catdata, 'orderdata' => $orderdata, 'websiteShortCode' => $websiteShortCode, 'websiteUrl' => $websiteUrl]);
    }

    public function edit($id = null, Request $request){
        
        if($this->checkPermission('falseurl','list') == false && $this->checkSuperPermission('falseurl','list') == false ){
            $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
            return redirect()->route('admin.home');
        }

        $websiteShortCode = 'gbg';
        $websiteUrl = 'https://www.giftbasketsgermany.de/';
        $pricedtl = $imageName = '';

        $id = base64_decode($id);
		$dataDetails  = GBGFalseUrl::where('id',$id)->first();
        $catdata=GBGCategory::all();
        $orderdata=GBGFalseUrlProductSort::all();
        $obj = new GBGFalseUrl;
         
        if($request->isMethod('POST')){
            $request->validate([
                'falseurl'=>'required',
                'category'=>'required',
                'banneralt'=>'required',
                'bannerheading'=>'required',
                'falseurlcontenttop'=>'required',
                'falseurlcontentbottom'=>'required',
                'productsorder'=>'required',
                'falseurlmeta_title'=>'required',
                'falseurlmeta_description'=>'required',
                'falseurltagline'=>'required',
                'falseurltophead'=>'required'
            ]);
            
            $imagefile=$request->file("fimage");
            if (isset($imagefile)){
                $imageName = strtotime(now()).rand(11111,99999).'-banner.'.$imagefile->getClientOriginalExtension();
                $imagefile->move(public_path() . '/uploads/falseurl/', $imageName);
            }
            $a = 0;
            $formid=$request->formid;

            $update_arr['country_id'] = $a;
            $update_arr['slug_url'] = $request->falseurl;
            $update_arr['category_id'] = $request->category;
            if(isset($imagefile)){
                $update_arr['banner_img'] = $imageName;
            }
            $update_arr['banner_img_alt'] = $request->banneralt;
            $update_arr['banner_heading'] = $request->bannerheading;
            $update_arr['content_top'] = $request->falseurlcontenttop;
            $update_arr['content_bottom'] = $request->falseurlcontentbottom;
            $update_arr['sort_order'] = $request->productsorder;
            $update_arr['meta_title'] = $request->falseurlmeta_title;
            $update_arr['meta_description'] = $request->falseurlmeta_description;
            $update_arr['tag_line'] = $request->falseurltagline;
            $update_arr['tophead'] = $request->falseurltophead;  
            
            if(GBGFalseUrl::where(['id' => $request->formid])->update($update_arr)){
                   
                $request->session()->flash('alert-success', 'FalseUrl successfully updated.');
                return redirect()->route('admin.gbg.falseurl.list');
            }else{
                $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
                return redirect()->back()->with($request->except(['_method', '_token']));
            }
        }
        
        return view('admin.GBG.falseurl.edit', ['dataDetails' => $dataDetails, 'catdata' => $catdata, 'orderdata' => $orderdata, 'request' => $request, 'websiteShortCode' => $websiteShortCode, 'websiteUrl' => $websiteUrl]);
    }


    public function delete($id = null, Request $request)
    {
        if($this->checkPermission('falseurl','list') == false && $this->checkSuperPermission('falseurl','list') == false ){
            $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
            return redirect()->route('admin.home');
        }
        
        $websiteShortCode = 'gbg'; 

        if($id == null){
            return redirect()->route('admin.home');
        }
        $id = base64_decode($id);
        $delobject=GBGFalseUrl::where(['id'=>$id])->first();
        if($delobject->banner_img != ''){
            unlink(public_path() . '/uploads/falseurl/'.$delobject->banner_img);
        }

        if( GBGFalseUrl::where(['id' => $id])->delete()){
            $request->session()->flash('alert-success', 'FalseUrl deleted successfully.');
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
        if(GBGFalseUrl::where(['id' => $id])->update(['is_block' => $block])){
            //$request->session()->flash('alert-success', 'Category successfully '.$blockText);
            //return redirect()->back();
            $prompt = array('status' => 1, 'id' => $id, 'event' => $blockText);

            echo json_encode($prompt);
        }else{
            $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
            return redirect()->back();
        }
    }

    public function deleteimage($id = null, Request $request){
        if($this->checkPermission('falseurl','list') == false && $this->checkSuperPermission('falseurl','list') == false ){
            $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
            return redirect()->route('admin.home');
        }
        
        $websiteShortCode = 'gbg';

        if($id == null){
            return redirect()->route('admin.home');
        }
        $id = base64_decode($id);
        $dataDetails  = GBGFalseUrl::find($id);
        unlink(public_path() . '/uploads/falseurl/'.$dataDetails->banner_img);

        if($dataDetails->update(['banner_img' => ''])){
            $request->session()->flash('alert-success', 'FalseUrl image deleted successfully.');
            return redirect()->back();
        }else{
            $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
            return redirect()->back();
        }
        
    }
}