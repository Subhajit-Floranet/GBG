<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $guarded = [];

    public function product_categories() {
    	return $this->hasMany('App\Models\ProductCategory', 'product_id');
    }
    
    public function product_image(){
    	return $this->hasMany('App\Models\ProductImage', 'product_id')->where('attr_id', 0);
    }

    public function default_product_image(){
    	return $this->hasOne('App\Models\ProductImage', 'product_id')->where('default_image', 'Y');
    }

    public function product_attribute(){
    	return $this->hasMany('App\Models\ProductAttribute', 'product_id')->where('is_block','N')->orderBy('sl_no');
    }

    public function product_attribute_without_condition(){
        return $this->hasMany('App\Models\ProductAttribute', 'product_id')->where('is_block','<>','D')->orderBy('sl_no');
    }

    public function order_product_all_attributes(){
        return $this->hasMany('App\Models\ProductAttribute', 'product_id');
    }

    //Related Countries Group
    public function product_related_country(){
        return $this->hasOne('App\Models\ProductRelatedCountry', 'product_id')->select('countries_id');
    }

    //Create unique slug
    public static function getUniqueSlug( $title, $id = 0 ) {
        // Normalize the title
        $slug = str_slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = Product::select('slug')->where('slug', 'like', $slug.'%')
                                ->where('id', '<>', $id)
                                ->get();

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