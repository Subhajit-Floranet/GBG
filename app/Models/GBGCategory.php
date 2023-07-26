<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GBGCategory extends Model
{
    protected $table = 'categories';
    protected $connection = "mysqlgbg";
    protected $guarded = [];

    public function category_attribute($id){
        return $this->hasMany('App\Models\GBGProductCategory', 'category_id')->where('product_id',$id)->get();
    }

    public function category_product($catid){
        return $this->hasMany('App\Models\GBGProductCategory', 'category_id')->where('category_id',$catid)->get();
    }

}
