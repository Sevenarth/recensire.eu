<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['title','preface','postface','subject','queries'];
    protected $casts = [
        'queries' => 'array'
    ];
}
