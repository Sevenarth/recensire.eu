<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Store;
use App\Http\Requests\StoreFormRequest;

class StoresController extends Controller
{
  public function index(Request $request) {
    $orderBy = $request->query('orderBy', null);
    $sort = $request->query('sort', 'asc');
    $search = trim($request->query('s', null));

    if(!empty($search)) {
        $stores = Store::where("id", $search)
          ->orWhere("name", "like", "%".$search."%")
          ->orWhere("company_name", "like", "%".$search."%")
          ->orWhere("company_registration_no", $search)
          ->orWhere("VAT", $search);

        if(!empty($orderBy))
          $stores = $stores->orderBy($orderBy, $sort)->paginate(15);
        else
          $stores = $stores->paginate(15);
    } else {
        if(!empty($orderBy))
          $stores = Store::orderBy($orderBy, $sort)->paginate(15);
        else
          $stores = Store::paginate(15);
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
