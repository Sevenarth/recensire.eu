<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestersController extends Controller
{
  public function index(Request $request) {

    return view("panel/testers/home");
  }
}
