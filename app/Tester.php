<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tester extends Model
{
    protected $table = "tester";

    public function testUnits() {
      return $this->hasMany('App\TestUnit');
    }
}
