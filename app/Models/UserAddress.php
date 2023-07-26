<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table = 'user_addresses';

    protected $guarded = [];

    public function country() {
    	return $this->belongsTo('App\Models\Country', 'country_id');
    }
}