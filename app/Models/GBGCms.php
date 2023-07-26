<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GBGCms extends Model
{
    protected $table = 'cms_pages';
    protected $connection = "mysqlgbg";
    protected $guarded = [];

    
}
