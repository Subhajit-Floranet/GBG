<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryBanner extends Model
{
    protected $table = 'category_banners';

    protected $guarded = [];

    public function country_detail()
  	{
  	   return $this->belongsTo('App\Models\Country', 'country_id');
  	} 

    public function city_detail()
    {
       return $this->belongsTo('App\Models\City', 'city_id');
    } 
    
}