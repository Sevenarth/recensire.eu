<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public static function get($key, $default = null, $obj = false) {
        if($obj)
            return self::where('key', $key)->first();

        if($option = self::where('key', $key)->select('value')->first())
            return $option->value ?: $default;

        return $default;
    }

    public static function set($key, $value) {
        $option = self::get($key, null, true);
        if(!empty($option)) {
            if($option->value != $value) {
                $option->value = $value;
                $option->save();
            }
        } else {
            $option = new Option;
            $option->key = $key;
            $option->value = $value;
            $option->save();
        }

        return $option;
    }
}
