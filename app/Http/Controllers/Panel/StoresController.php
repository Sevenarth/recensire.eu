<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Store;
use App\Http\Requests\StoreFormRequest;
use Validator;
use App\Product;

class StoresController extends Controller
{
  public function index(Request $request) {
    $orderBy = $request->query('orderBy', null);
    if(!empty($orderBy) && !in_array($orderBy, ['seller.name', 'store.name', 'store.id']))
      $orderBy = null;
    $sort = $request->query('sort', 'asc');
    if($sort != "asc" && $sort != "desc")
      $sort = "asc";
    $search = trim($request->query('s', null));

    $stores = DB::table('store')
        ->leftJoin('seller', 'store.seller_id', '=', 'seller.id');

    if(!empty($search))
      $stores = $stores->where("store.id", $search)
      ->orWhere("store.name", "like", "%".$search."%")
      ->orWhere("company_name", "like", "%".$search."%")
      ->orWhere("company_registration_no", $search)
      ->orWhere("VAT", $search)
      ->orWhere("seller.name", "like", "%".$search."%");

    if(!empty($orderBy))
      $stores = $stores->orderBy($orderBy, $sort);

    $stores = $stores
        ->select("store.*", "seller.id AS seller_id", "seller.name AS seller_name")
        ->paginate(15);

    return view("panel/stores/home")->with('stores', $stores);
  }

  public function create(Request $request) {

    return view("panel/stores/form");
  }

  public function edit(Request $request, Store $store) {
    return view("panel/stores/form", ['store' => $store]);
  }

  public function update(StoreFormRequest $request, Store $store) {
    $store->fill($request->only([
      'name', 'company_name', 'company_registration_no', 'url', 'VAT', 'country', 'seller_id'
    ]));
    $store->save();

    return redirect()
      ->route('panel.stores.view', $store->id)
      ->with('status', 'Negozio aggiornato con successo!');
  }

  public function delete(Request $request, Store $store) {
    $store->delete();

    return redirect()
      ->route('panel.stores.home')
      ->with('status', 'Negozio eliminato con successo!');
  }

  public function view(Request $request, Store $store) {
    return view("panel/stores/view", ['store' => $store]);
  }

  public function put(StoreFormRequest $request) {
    Store::create($request->only([
      'name', 'company_name', 'company_registration_no', 'url', 'VAT', 'country', 'seller_id'
    ]))->save();

    return redirect()
      ->route('panel.stores.home')
      ->with('status', 'Nuovo negozio inserito con successo!');
  }

  public function products(Request $request, Store $store) {
    $orderBy = $request->query('orderBy', null);
    if(!empty($orderBy) && !in_array($orderBy, ['ASIN', 'brand', 'title']))
      $orderBy = null;
    $sort = $request->query('sort', 'asc');
    if($sort != "asc" && $sort != "desc")
      $sort = "asc";
    $search = trim($request->query('s', null));

    $products = $store->products();

    if(!empty($search))
      $products = $products->where(function($query) use ($search) {
        $query->where('ASIN', $search)
          ->orWhere('title', 'like', '%'.$search.'%')
          ->orWhere('brand', 'like', $search.'%');
      });

    if(!empty($orderBy))
      $products = $products->orderBy($orderBy, $sort);

    $products = $products->paginate(15);

    return view("panel/stores/products", ['store' => $store, 'products' => $products]);
  }

  public function attachProduct(Request $request, Store $store) {
    Validator::extend('unassociated', function ($attribute, $value, $parameters, $validator) use($store) {
      return !(DB::table('store_product')->where('product_id', $value)->where('store_id', $store->id)->count() > 0);
    });
    $validator = Validator::make($request->all(), [
      'product_id' => 'required|exists:product,ASIN|unassociated'
    ], [
      'required' => 'Inserisci un ASIN prodotto valido.',
      'exists' => "Il prodotto richiesto non è nel sistema.",
      'unassociated' => "Il prodotto &egrave; gi&agrave; associato a questo negozio."
    ]);

    if ($validator->fails())
      return redirect()
        ->route('panel.stores.products', $store->id)
        ->withErrors($validator)
        ->withInput();

    $store->products()->attach(Product::where('ASIN', $request->input('product_id'))->first());

    return redirect()
      ->route('panel.stores.products', $store->id)
      ->with('status', 'Prodotto associato con successo!');
  }

  public function detachProduct(Request $request, Store $store, Product $product) {
    if(DB::table('store_product')->where('product_id', $product->id)->where('store_id', $store->id)->count() > 0) {
      $store->products()->detach($product);

      return redirect()
        ->route('panel.stores.products', $store->id)
        ->with('status', 'Prodotto disassociato con successo!');
    } else
      return response('Bad request', 400);
  }

  public function fetch(Request $request) {
    $search = trim($request->input('s', null));

    if(!empty($search)) {
        $stores = Store::where("id", $search)
          ->orWhere("name", "like", "%".$search."%")
          ->orWhere("company_name", "like", "%".$search."%")
          ->limit(15)
          ->get(['id', 'name']);
    } else
      $stores = Store::orderBy('name', 'asc')
        ->limit(15)
        ->get(['id', 'name']);

    return $stores;
  }
}
