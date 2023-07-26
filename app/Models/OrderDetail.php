<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details';

    protected $guarded = [];

    public function product() {
    	return $this->belongsTo('App\Models\Product', 'product_id');
    }

    public function order_related_detail() {
        return $this->hasOne('App\Models\OrderDetail', 'order_details_id');
    }
    
    public function product_attribute_detail() {
        return $this->belongsTo('App\Models\ProductAttribute', 'product_attr_id');
    }

    // public function gift_addon_detail() {
    //     return $this->belongsTo('App\Model\GiftAddon', 'gift_addon_id');
    // }

    // public function extraaddon_detail() {
    //     return $this->belongsTo('App\Model\ProductExtra', 'gift_addon_id');
    // }

    public function extraaddon_detail() {
        return $this->belongsTo('App\Models\Product', 'gift_addon_id');
    }

    // public function addon_default_product_image(){
    // 	return $this->hasOne('App\Model\ProductImage', 'gift_addon_id')->where('default_image', 'Y');
    // }

    public function country_detail() {
        return $this->belongsTo('App\Models\Country', 'delivery_country_id');
    }
}
