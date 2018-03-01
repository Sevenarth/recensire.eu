<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestOrder extends Model
{
    protected $table = "test_order";
    protected $fillable = ['fee', 'quantity', 'description'];

    public function testUnits() {
      return $this->hasMany('App\TestUnit');
    }

    public function product() {
      return $this->belongsTo('App\Product');
    }

    public function store() {
      return $this->belongsTo('App\Store');
    }

    public function hasCompletes() {
      foreach($this->testUnits as $unit)
        if($unit->status == 3)
          return true;

      return false;
    }
}
