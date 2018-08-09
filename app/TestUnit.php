<?php

namespace App;

use App\Events\TestUnitCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Events\Dispatcher;
use Hashids;
use Carbon\Carbon;

class TestUnit extends Model
{
    protected $table = "test_unit";
    protected $fillable = [
      'amazon_order_id', 'review_url', 'reference_url',
      'instructions', 'status', 'paypal_account',
      'refunded_amount', 'expires_on_time', 'expires_on_space',
      'refunding_type', 'tester_notes'
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

    public function startsDate() {
      return (new Carbon($this->starts_on, config('app.timezone')))->toDateString();
    }

    public function startsTime() {
      return (new Carbon($this->starts_on, config('app.timezone')))->format("H:i");
    }

    public function getInstructionsAttribute() {
      if(!empty($this->attributes['instructions']))
        $instructions = $this->attributes['instructions'];
      else
        return null;
        
      foreach(Shortcode::all() as $sc)
        $instructions = preg_replace('/#'.preg_quote($sc->key).'(?![a-zA-Z0-9\-])/m', $sc->value, $instructions);
      return $instructions;
    }

    public function originalInstructions() {
      if(!empty($this->attributes['instructions']))
        return $this->attributes['instructions'];
      else
        return null;
    }
}
