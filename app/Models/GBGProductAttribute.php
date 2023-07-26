<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GBGProductAttribute extends Model
{
    protected $table = 'product_attributes';
    protected $connection = "mysqlgbg";
    protected $guarded = [];

    
}
