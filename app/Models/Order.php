<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $guarded = [];

    public function order_detail() {
        return $this->hasMany('App\Models\OrderDetail','order_id')->where('order_status','IP')->orderBy('order_details_id','DESC');
    }

    public function order_detail_site() {
        return $this->hasMany('App\Models\OrderDetail','order_id')->orderBy('id','ASC');
    }

    public function order_detail_admin() {
        return $this->hasMany('App\Models\OrderDetail','order_id')->orderBy('id','ASC');
    }

    public function order_coupon_data() {
        return $this->hasOne('App\Models\AppliedCoupon','order_id');
    }

    public function order_currency() {
        return $this->hasOne('App\Models\OrderCurrency','order_id');
    }

    public function order_message() {
        return $this->hasOne('App\Models\OrderMessage','order_id');
    }
}
