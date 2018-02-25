<?php

namespace App;

use App\Events\TestUnitCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hashids;
use Carbon\Carbon;

class TestUnit extends Model
{
    use SoftDeletes;

    protected $table = "test_unit";
    protected $fillable = [
      'amazon_order_id', 'review_url', 'reference_url',
      'instructions', 'status', 'paypal_account', 'refunded_amount'
    ];

    public static function boot() {
      parent::boot();

      static::created(function($testUnit) {
          $testUnit->hash_code = Hashids::encode($testUnit->id);
          $testUnit->save();
      });
    }

    public function tester() {
      return $this->belongsTo('App\Tester');
    }

    public function testOrder() {
      return $this->belongsTo('App\TestOrder');
    }

    public function statuses() {
      return $this->hasMany('App\TestUnitStatus');
    }

    public function expiresDate() {
      return (new Carbon($this->expires_on, config('app.timezone')))->toDateString();
    }

    public function expiresTime() {
      return (new Carbon($this->expires_on, config('app.timezone')))->format("H:i");
    }
}
