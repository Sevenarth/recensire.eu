<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tester extends Model
{
    protected $table = "tester";
    protected $fillable = ['name', 'email', 'wechat', 'profile_image', 'amazon_profiles', 'amazon_profiles_statuses', 'facebook_profiles', 'status', 'notes'];
    protected $casts = [
      'amazon_profiles' => 'array',
      'amazon_profiles_statuses' => 'array', 
      'facebook_profiles' => 'array'
    ];

    public function testUnits() {
      return $this->hasMany('App\TestUnit');
    }
}
