<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestOrder extends Model
{
    protected $table = "test_order";

    public function testUnits() {
      return $this->hasMany('App\TestUnit');
    }

    public function product() {
      return $this->belongsTo('App\Product');
    }

    public function store() {
      return $this->belongsTo('App\Store');
    }
}
