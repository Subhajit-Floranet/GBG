<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';

    protected $guarded = [];

    // public function menus() {
    // 	return $this->hasMany('App\Model\Menu', 'parent_id');
    // }
}