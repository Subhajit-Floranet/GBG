<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GBGFalseUrl extends Model
{
    protected $table = 'false_urls';
    protected $connection = "mysqlgbg";
    protected $guarded = [];

    
}
