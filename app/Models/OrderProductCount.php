<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderProductCount extends Model
{
    protected $table = 'order_product_count';

    protected $guarded = [];

    public $timestamps = false;
}