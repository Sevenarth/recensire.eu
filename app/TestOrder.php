<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TestOrder extends Model
{
    use SoftDeletes;
    
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
}
