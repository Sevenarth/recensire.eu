<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestUnit extends Model
{
    protected $table = "test_unit";

    public function tester() {
      return $this->belongsTo('App\Tester');
    }

    public function testOrder() {
      return $this->belongsTo('App\TestOrder');
    }

    public function statuses() {
      return $this->hasMany('App\TestUnitStatus');
    }
}
