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
