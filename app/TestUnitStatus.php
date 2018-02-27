<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestUnitStatus extends Model
{
    protected $table = "test_unit_status";
    protected $fillable = ['status'];

    public function unit() {
      return $this->belongsTo('App\TestUnit', 'test_unit_id');
    }
}
