<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $guarded = [];

    public function menus() {
    	return $this->hasMany('App\Model\Menu', 'parent_id');
    }
}