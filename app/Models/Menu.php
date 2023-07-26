<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';

    protected $guarded = [];

    public function categories() {
    	return $this->belongsTo('App\Model\Category', 'id');
    }
}