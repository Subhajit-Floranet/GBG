<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;



class GBGProduct extends Model
{
    protected $table = 'products';
    protected $connection = "mysqlgbg";
    protected $guarded = [];

    public function product_attribute(){
        return $this->hasMany('App\Models\GBGProductAttribute', 'product_id')->where('is_block','N')->orderBy('sl_no');
    }

    public function default_product_image(){
        return $this->hasOne('App\Models\GBGProductImage', 'product_id')->where('default_image','Y');
    }
    
    public function default_product_category(){
        return $this->hasMany('App\Models\GBGProductCategory', 'product_id');
    }

    public static function getUniqueSlug( $title, $id = 0 ) {
        // Normalize the title
        $slug = str_slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = GBGProduct::select('slug')->where('slug', 'like', $slug.'%')
                                ->where('id', '<>', $id)
                                ->get();
        // print_r($allSlugs);
        // die;
        // If we haven't used it before then we are all good.
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= count($allSlugs); $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }
    }
}
