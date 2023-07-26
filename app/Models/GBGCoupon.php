<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GBGCoupon extends Model
{
    protected $table = 'coupons';
    protected $connection = "mysqlgbg";
    protected $guarded = [];

    
}
