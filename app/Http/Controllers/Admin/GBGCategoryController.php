<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GBGCategory;
use App\Models\GBGPriceBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;


class GBGCategoryController extends CommonController
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

        if($this->checkPermission('category','list') == false && $this->checkSuperPermission('category','list') == false ){
            $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
            return redirect()->route('admin.home');
        }

        $websiteShortCode = 'gbg';

        $where = $orWhere = [];

        $result = GBGCategory::orderBy('created_at', 'desc')->get();

        return view('admin.GBG.category.list', ['result' => $result, 'request' => $request, 'websiteShortCode' => $websiteShortCode]);
    }

    
    public function add(Request $request){

        if($this->checkPermission('category','list') == false && $this->checkSuperPermission('category','list') == false ){
            $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
            return redirect()->route('admin.home');
        }

        $websiteShortCode = 'gbg';
        

        $obj = new GBGCategory;

        if($request->isMethod('POST')){
            //dd($request);
            $request->validate([
                'ctitle'=>'required | unique:mysqlgbg.categories,name',
                'chead'=>'required',
                'cbannerhead'=>'required',
                'ccontenttop'=>'required',
                'ccontentbottom'=>'required',
                'cmeta_title'=>'required',
                'cmeta_description'=>'required',
                'ctagline'=>'required',
                'ctophead'=>'required'
            ]);

            $imageName = '';

            $imagefile=$request->file("cimage");
            if (isset($imagefile)){

                if(!is_dir(public_path() . '/uploads/banner/')){
                    mkdir(public_path() . '/uploads/banner/', 0777, true);
                }

                $imageName = strtotime(now()).rand(11111,99999).'-banner.'.$imagefile->getClientOriginalExtension();
                //$imagefile->move(public_path(). \uploads\banner\, $imageName);
                $imagefile->move(public_path() . '/uploads/banner/', $imageName);
            }

            if($cat = $obj->create([
                    'name' => $request->ctitle,
                    'page_head' => $request->chead, 
                    'image' => $imageName,
                    'banner_heading' => $request->cbannerhead,
                    'menu_head_only' => $request->cmenuhead,
                    'cat_section' => $request->ctype,
                    'content_top' => $request->ccontenttop,
                    'content_bottom' => $request->ccontentbottom,
                    'meta_title' => $request->cmeta_title, 
                    'meta_description' => $request->cmeta_description,
                    'tag_line' => $request->ctagline,
                    'tophead' => $request->ctophead,
                    'slug'=> str_replace(' ', '-', strtolower($request->ctitle))
            ]))
          
            {
                $id=$cat->id;
                if ($request->ctype=="P"){
                    $obj2 = new GBGPriceBrand();
                    $obj2->category_id=$id;
                    $obj2->from_price=$request->from_price;
                    $obj2->to_price=$request->to_price;
                    $obj2->equation=$request->equation;
                    $obj2->save();
                }

                $request->session()->flash('alert-success', 'Category successfully added.');
                return redirect()->route('admin.gbg.category.list');
            }else{
                $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
                return redirect()->back()->with($request->except(['_method', '_token']));
            }
            
        }
        return view('admin.gbg.category.add', ['request' => $request, 'websiteShortCode' => $websiteShortCode]);
    }

    public function edit($id = null, Request $request){

        if($this->checkPermission('category','list') == false && $this->checkSuperPermission('category','list') == false ){
            $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
            return redirect()->route('admin.home');
        }

        $websiteShortCode = 'gbg';
        $pricedtl = $imageName = '';

        $id = base64_decode($id);
		$dataDetails  = GBGCategory::where('id',$id)->first();

        $dataPriceBrand = GBGPriceBrand::where('category_id', $id)->first();

        if($dataPriceBrand){
            $pricedtl = $dataPriceBrand;
        }else{
            $pricedtl = '';
        }
        
        $obj = new GBGCategory;

        if($request->isMethod('POST')){
            //dd($request);
            $request->validate([
                'ctitle'=>'required',
                'chead'=>'required',
                'cbannerhead'=>'required',
                'ccontenttop'=>'required',
                'ccontentbottom'=>'required',
                'cmeta_title'=>'required',
                'cmeta_description'=>'required',
                'ctagline'=>'required',
                'ctophead'=>'required'
            ]);
            
            $imagefile=$request->file("cimage");
            if (isset($imagefile)){
                $imageName = strtotime(now()).rand(11111,99999).'-banner.'.$imagefile->getClientOriginalExtension();
                $imagefile->move(public_path() . '/uploads/banner/', $imageName);
            }

            $formid=$request->formid;

            $update_arr['name'] = $request->ctitle;
            $update_arr['page_head'] = $request->chead;
            if(isset($imagefile)){
                $update_arr['image'] = $imageName;
            }
            $update_arr['banner_heading'] = $request->cbannerhead;
            $update_arr['menu_head_only'] = $request->cmenuhead;
            $update_arr['cat_section'] = $request->ctype;
            $update_arr['content_top'] = $request->ccontenttop;
            $update_arr['content_bottom'] = $request->ccontentbottom;
            $update_arr['meta_title'] = $request->cmeta_title;
            $update_arr['meta_description'] = $request->cmeta_description;
            $update_arr['tag_line'] = $request->ctagline;
            $update_arr['tophead'] = $request->ctophead;  
            
            $dataPriceBrandUpd= GBGPriceBrand::where('category_id', $formid)->first();

            if ($request->ctype=="P"){
                if($dataPriceBrandUpd){
                    $dataPriceBrandUpd->from_price=$request->from_price;
                    $dataPriceBrandUpd->to_price=$request->to_price;
                    $dataPriceBrandUpd->equation=$request->equation;
                    $dataPriceBrandUpd->update();
                }else{
                    $obj2 = new GBGPriceBrand();
                    $obj2->category_id=$formid;
                    $obj2->from_price=$request->from_price;
                    $obj2->to_price=$request->to_price;
                    $obj2->equation=$request->equation;
                    $obj2->save();
                }
            }else{
                if($dataPriceBrandUpd){
                   $object=GBGPriceBrand::where('id', $dataPriceBrandUpd->id)->delete();
                }
            }
            
            if(GBGCategory::where(['id' => $request->formid])->update($update_arr)){
                   
                $request->session()->flash('alert-success', 'Catgory successfully updated.');
                return redirect()->route('admin.gbg.category.list');
            }else{
                $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
                return redirect()->back()->with($request->except(['_method', '_token']));
            }
        }
         return view('admin.GBG.category.edit', ['dataDetails' => $dataDetails, 'request' => $request, 'websiteShortCode' => $websiteShortCode, 'pricedtl' => $pricedtl]);
    }

    public function delete($id = null, Request $request)
    {
        if($this->checkPermission('category','list') == false && $this->checkSuperPermission('category','list') == false ){
            $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
            return redirect()->route('admin.home');
        }
        
        $websiteShortCode = 'gbg'; 

        if($id == null){
            return redirect()->route('admin.home');
        }
        $id = base64_decode($id);
        $delobject=GBGCategory::where(['id'=>$id])->first();
        if($delobject->image != ''){
            unlink(public_path() . '/uploads/banner/'.$delobject->image);
        }

        if( GBGCategory::where(['id' => $id])->delete()){
            $request->session()->flash('alert-success', 'CMS deleted successfully.');
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
        if(GBGCategory::where(['id' => $id])->update(['is_block' => $block])){
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
        if($this->checkPermission('category','list') == false && $this->checkSuperPermission('category','list') == false ){
            $request->session()->flash('alert-danger', "You don't have permissions to access this page.");
            return redirect()->route('admin.home');
        }
        
        $websiteShortCode = 'gbg';

        if($id == null){
            return redirect()->route('admin.home');
        }
        $id = base64_decode($id);
        $dataDetails  = GBGCategory::find($id);
        unlink(public_path() . '/uploads/banner/'.$dataDetails->image);
        //$dataDetails->image='';
        //$dataDetails->update();

        if($dataDetails->update(['image' => ''])){
            $request->session()->flash('alert-success', 'Category image deleted successfully.');
            return redirect()->back();
        }else{
            $request->session()->flash('alert-danger', 'Sorry! There was an unexpected error. Try again!');
            return redirect()->back();
        }
        
    }
}