<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppliedCoupon extends Model
{
    protected $table = 'applied_coupons';

    protected $guarded = [];

    public function coupon_detail() {
        return $this->belongsTo('App\Models\Coupon', 'coupon_id');
    }
}