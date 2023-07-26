<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GBGProductCategory extends Model
{
    protected $table = 'product_categories';
    protected $connection = "mysqlgbg";
    protected $guarded = [];

    
}
