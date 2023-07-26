<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonGroup extends Model
{
    protected $table = 'addon_groups';

    protected $guarded = [];

    public function addonrelatedgroupdetails() {
    	return $this->hasMany('App\Models\Addon', 'addon_group_id');
    }
}
