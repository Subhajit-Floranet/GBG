<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    protected $guarded = [];

    public function country() {
    	return $this->belongsTo('App\Models\Country','country_id');
	}
    
}