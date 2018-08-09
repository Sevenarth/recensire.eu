<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cartalyst\Tags\TaggableTrait;
use Cartalyst\Tags\TaggableInterface;

class Product extends Model implements TaggableInterface
{
    use TaggableTrait;

    protected $table = "product";
    protected $fillable = ['title', 'brand', 'ASIN', 'URL', 'description', 'images'];
    protected $casts = ['images' => 'array'];

    public function categories() {
      return $this->belongsToMany('App\Category', 'category_product', 'product_id', 'category_id');
    }

    public function stores() {
      return $this->belongsToMany('App\Store', 'store_product', 'product_id', 'store_id');
    }

    public function testOrders() {
      return $this->hasMany('App\TestOrder');
    }

    public function storeTestOrders(Store $store) {
      return $this->hasMany('App\TestOrder')->where('store_id', $store->id);
    }

    public function getDescriptionAttribute() {
      if(!empty($this->attributes['description']))
        $description = $this->attributes['description'];
      else
        return null;

      foreach(Shortcode::all() as $sc)
        $description = preg_replace('/#'.preg_quote($sc->key).'(?![a-zA-Z0-9\-])/m', $sc->value, $description);
      return $description;
    }

    public function originalDescription() {
      if(!empty($this->attributes['description']))
        return $this->attributes['description'];
      else
        return null;
    }

    public function inlineTags() {
      if(!empty($this->tags->toArray()))
        return implode(",", array_map(function($elt) {
          return $elt['name'];
        }, $this->tags->toArray()));
      else
        return "";
    }

    public function catsIds() {
      if(!empty($this->categories->toArray()))
        return array_map(function($elt) {
          return $elt['id'];
        }, $this->categories->toArray());
      else
        return [];
    }
}
