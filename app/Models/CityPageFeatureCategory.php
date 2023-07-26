<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityPageFeatureCategory extends Model
{
    protected $table = 'city_page_feature_categories';

    protected $guarded = [];

    public function category(){
    	return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function city(){
    	return $this->belongsTo('App\Models\City', 'city_id');
    }

}
