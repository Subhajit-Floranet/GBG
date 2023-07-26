<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProductExtraAddonGroup extends Model
{
    protected $table = 'product_extra_addon_groups';

    protected $guarded = [];

    public function relatedgroupdetails() {
    	return $this->hasMany('App\Model\ProductExtraAddon', 'extra_addon_group_id');
    }
}
