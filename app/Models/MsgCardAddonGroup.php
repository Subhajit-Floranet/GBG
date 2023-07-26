<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsgCardAddonGroup extends Model
{
    protected $table = 'msg_card_addon_groups';

    protected $guarded = [];

    public function addonrelatedgroupdetails() {
    	return $this->hasMany('App\Models\MsgCardAddon', 'addon_group_id');
    }
}
