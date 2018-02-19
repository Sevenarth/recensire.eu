<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cartalyst\Tags\TaggableTrait;
use Cartalyst\Tags\TaggableInterface;

class Product extends Model implements TaggableInterface
{
    use SoftDeletes, TaggableTrait;

    protected $table = "product";

    public function categories() {
      return $this->belongsToMany('App\Category', 'category_product', 'product_id', 'category_id');
    }

    public function stores() {
      return $this->belongsToMany('App\Store', 'store_product', 'product_id', 'store_id');
    }

    public function testOrders() {
      return $this->hasMany('App\TestOrder');
    }
}
