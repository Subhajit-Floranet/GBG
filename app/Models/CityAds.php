<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CityAds extends Model
{
    protected $table = 'city_ads';

    protected $guarded = [];

    public function city() {
    	return $this->belongsTo('App\Model\City', 'city_id');
    }
}
