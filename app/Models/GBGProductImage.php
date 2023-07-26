<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GBGProductImage extends Model
{
    protected $table = 'product_images';
    protected $connection = "mysqlgbg";
    protected $guarded = [];

    
}
