<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
  public function index(Request $request) {

    return view("panel/products/home");
  }

  public function create(Request $request) {

    return view("panel/products/create");
  }
}
