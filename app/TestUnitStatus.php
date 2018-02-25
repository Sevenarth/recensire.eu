<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestUnitStatus extends Model
{
    protected $table = "test_unit_status";
    protected $fillable = ['status'];
}
