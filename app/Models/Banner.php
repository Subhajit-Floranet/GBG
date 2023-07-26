<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';

    protected $guarded = [];

    public function BannerCategory() {
    	return $this->belongsTo('App\Model\BannerCategory', 'banner_categories_id');
    }
}
