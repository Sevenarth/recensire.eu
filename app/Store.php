<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = "store";
    protected $fillable = ['name', 'company_name', 'company_registration_no', 'url', 'VAT', 'country', 'seller_id'];

    public function seller() {
      return $this->belongsTo('App\Seller');
    }

    public function products() {
      return $this->belongsToMany('App\Product', 'store_product', 'store_id', 'product_id');
    }
}
