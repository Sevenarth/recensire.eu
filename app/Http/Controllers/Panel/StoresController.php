<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Store;
use App\Http\Requests\StoreFormRequest;

class StoresController extends Controller
{
  public function index(Request $request) {
    $orderBy = $request->query('orderBy', null);
    if(!empty($orderBy) && !in_array($orderBy, ['seller.name', 'store.name', 'store.id']))
      $orderBy = null;
    $sort = $request->query('sort', 'asc');
    if(!empty($sort) && $sort != "asc" && $sort != "desc")
      $sort = "asc";
    $search = trim($request->query('s', null));

    if(!empty($search)) {
        $stores = DB::table('store')
            ->leftJoin('seller', 'store.seller_id', '=', 'seller.id')
            ->where("store.id", $search)
            ->orWhere("store.name", "like", "%".$search."%")
            ->orWhere("company_name", "like", "%".$search."%")
            ->orWhere("company_registration_no", $search)
            ->orWhere("VAT", $search)
            ->orWhere("seller.name", "like", "%".$search."%")
            ->select("store.*", "seller.id AS seller_id", "seller.name AS seller_name");

        if(!empty($orderBy))
          $stores = $stores->orderBy($orderBy, $sort)->paginate(15);
        else
          $stores = $stores->paginate(15);
    } else {
        if(!empty($orderBy)) {
          $stores = DB::table('store')
              ->leftJoin('seller', 'store.seller_id', '=', 'seller.id')
              ->orderBy($orderBy, $sort)
              ->select("store.*", "seller.id AS seller_id", "seller.name AS seller_name")
              ->paginate(15);
        } else
          $stores = DB::table('store')
              ->leftJoin('seller', 'store.seller_id', '=', 'seller.id')
              ->select("store.*", "seller.id AS seller_id", "seller.name AS seller_name")
              ->paginate(15);
    }

    return view("panel/stores/home")->with('stores', $stores);
  }

  public function create(Request $request) {

    return view("panel/stores/form");
  }

  public function put(StoreFormRequest $request) {
    Store::create($request->only([
      'name', 'company_name', 'company_registration_no', 'url', 'VAT', 'country', 'seller_id'
    ]))->save();

    return redirect()
      ->route('panel.stores.home')
      ->with('status', 'Nuovo negozio inserito con successo!');
  }
}
