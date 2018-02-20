<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seller extends Model
{
    use SoftDeletes;

    protected $table = "seller";
    protected $dates = ['deleted_at'];

    protected $fillable = [
      'nickname', 'name', 'email', 'facebook', 'wechat', 'profile_image'
    ];

    public function stores() {
      return $this->hasMany('App\Store');
    }
}