<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CountryLocation extends Model
{
    protected $table = 'country_locations';

    protected $guarded = [];

    public $timestamps = false;
}