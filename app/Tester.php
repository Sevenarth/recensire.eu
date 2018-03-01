<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tester extends Model
{
    protected $table = "tester";
    protected $fillable = ['name', 'email', 'wechat', 'profile_image', 'amazon_profiles', 'facebook_profiles'];
    protected $casts = [
      'amazon_profiles' => 'array',
      'facebook_profiles' => 'array'
    ];

    public function testUnits() {
      return $this->hasMany('App\TestUnit');
    }
}
