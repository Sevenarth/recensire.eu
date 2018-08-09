<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seller extends Model
{
    protected $table = "seller";
    protected $dates = ['deleted_at'];

    protected $fillable = [
      'nickname', 'name', 'email', 'facebook', 'wechat', 'profile_image', 'notes'
    ];

    public function stores() {
      return $this->hasMany('App\Store');
    }
    
    public function getNotesAttribute() {
      if(!empty($this->attributes['notes']))
        $notes = $this->attributes['notes'];
      else
        return null;
      
      foreach(Shortcode::all() as $sc)
        $notes = preg_replace('/#'.preg_quote($sc->key).'(?![a-zA-Z0-9\-])/m', $sc->value, $notes);
      return $notes;
    }

    public function originalNotes() {
      if(!empty($this->attributes['notes']))
        return $this->attributes['notes'];
      else
        return null;
    }
}
